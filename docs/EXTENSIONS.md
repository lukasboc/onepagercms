# OnePagerCMS Extensions (Plugins & Themes)

Since version 1.2.0, OnePagerCMS supports plugins and themes. They can be installed
from the marketplace (admin backend → Extensions → Marketplace) or by uploading a
ZIP archive (Extensions → Upload). The CMS install/update process is unchanged:
the `extensions` database table is created lazily on first use, and the site works
exactly as before when no extensions are installed.

## Directory layout

```
extensions/<slug>/        one directory per plugin
  plugin.json             manifest (required)
  <main>.php              entry file, included on every request while active

themes/<slug>/            one directory per theme
  theme.json              manifest (required)
  screenshot.png          shown on the Design page (optional)
  templates/*.php         overridable templates (fall back to the default theme)
  assets/                 css/js/images, reachable as ../themes/<slug>/assets/...
```

`plugins/` (lowercase, in the web root) is **not** related to this system — it holds
vendored JS libraries for the admin backend (Trumbowyg etc.).

## Manifest (plugin.json / theme.json)

```json
{
  "slug": "my-plugin",
  "type": "plugin",
  "name": "My Plugin",
  "version": "1.0.0",
  "description": "What it does.",
  "author": "Jane Dev",
  "author_url": "https://janedev.example",
  "main": "my-plugin.php",
  "requires_opcms": "1.2.0",
  "requires_php": "7.4",
  "paid": false,
  "update_endpoint": null,
  "requires_license": false
}
```

Rules enforced by the installer:

- `slug`: `^[a-z0-9][a-z0-9-]{2,49}$`; must match the marketplace listing.
- `type`: `plugin` or `theme`.
- `version`: `1.0` or `1.0.0` style; updates must increase it.
- `main` (plugins only): relative PHP file, no `..`.
- Archives are checked against zip-slip paths; max size 20 MB.
- Paid items must set `"paid": true` and an `update_endpoint` (see below).

Themes use the same schema in `theme.json` (no `main`).

## Hook API

Plugins register callbacks through WordPress-style helpers (defined in
`system/hooks.php`):

```php
add_action($hook, $callback, $priority = 10);
do_action($hook, ...$args);
add_filter($hook, $callback, $priority = 10);
$value = apply_filters($hook, $value, ...$args);
```

### Frontend hooks

| Hook | Type | Description |
|---|---|---|
| `opcms_head` | action | end of `<head>` on all frontend pages |
| `opcms_before_nav` / `opcms_after_nav` | action | around the navigation |
| `opcms_before_sections` / `opcms_after_sections` | action | around the sections loop |
| `opcms_sections` | filter | the array of section objects before rendering |
| `opcms_section_html` | filter | `($html, $section, $index)` per rendered section |
| `opcms_custom_css` | filter | the custom CSS injected into the inline `<style>` block |
| `opcms_footer` | action | after the footer |
| `opcms_body_end` | action | before `</body>` |

### Admin hooks

| Hook | Type | Description |
|---|---|---|
| `opcms_admin_head` | action | end of the admin `<head>` |
| `opcms_admin_nav_items` | filter | array of `['label' => ..., 'href' => ...]` nav entries |
| `opcms_admin_pages` | filter | map `pageslug => ['title' => ..., 'render' => callable]` |
| `opcms_admin_footer` | action | before the admin footer |
| `opcms_extension_handlers` | filter | map `handlerslug => callable` — POST handlers dispatched by `misc/extension.php?handler=<slug>` (session-guarded, runs before any output so the callback can redirect via `header()`) |

A plugin admin page registered via `opcms_admin_pages` is reachable at
`core/extension.php?page=<pageslug>`; add a matching nav item via
`opcms_admin_nav_items` with `href => '../core/extension.php?page=<pageslug>'`.

### Lifecycle hooks

`opcms_activate_{slug}`, `opcms_deactivate_{slug}`, `opcms_uninstall_{slug}`.
Create your own tables in the activate hook using `CREATE TABLE IF NOT EXISTS`
(get a PDO handle with `include '../database/connect.php';` — the same pattern
the core uses). Clean up in the uninstall hook.

### Practical notes for plugin code

- Your main file is included on every request (frontend, admin, handlers) while
  active. Only register hooks at load time; do real work inside the callbacks.
- Use `OPCMS_ROOT` for absolute paths and `OPCMS_VERSION` for version checks.
- Everything runs without namespaces or an autoloader — prefix your functions
  and classes with your slug to avoid collisions.
- Plugin code runs with full CMS privileges. Marketplace submissions are
  reviewed, but users should only install code they trust.

## Custom section types

Plugins can register entirely new section types (e.g. Portfolio, image gallery)
that behave like the built-in ones: they appear in the "New Section" dropdown,
get their own admin form, participate in position ordering and navigation, and
render on the one-pager.

```php
opcms_register_section_type('gallery', array(
    'label'    => 'Image Gallery',      // shown in the New Section dropdown
    'build'    => function (array $registryRow) { ... },
    'render'   => function ($section, $bgcolor, $index) { return '<section>...</section>'; },
    'form_url' => '../core/extension.php?page=gallery-form',
));
```

Registration rules: call it at the top level of your main file (it must run on
every request). The type name must match `^[a-z0-9][a-z0-9-]{1,49}$`, must not
be `standard`/`icons`/`contact`, and the first registration of a name wins —
the function returns `false` on any conflict. Accessors:
`opcms_get_section_types()` and `opcms_get_section_type($type)`.

### How the pieces fit together

- **Storage**: the core `sections` table is only a registry (`id`, `type`,
  `specialid`, `position`). Your plugin owns its data table (create it in the
  activate hook with `CREATE TABLE IF NOT EXISTS`). To create a section: insert
  your data row first (allocate `specialid` as max+1 of *your* table), then call
  `SQLSectionActions::addSectionEntry($type, $specialid)` — that order never
  leaves a dangling registry row.
- **`build`** (required): called for every registry row of your type with
  `array('id' => ..., 'type' => ..., 'specialid' => ..., 'position' => ...)`.
  Return a `PluginSection` (or `null` to skip the section):
  `new PluginSection($type, $registryRow['id'], $registryRow['position'], $title, $dataBag)`.
  The object exposes `getType/getSuperid/getPosition/getTitle`, plus
  `get($key, $default)` / `getData()` for your fields. Because your sections go
  through the same pipeline as built-ins, navigation links and position
  ordering work automatically. Use the section title as the `id` attribute of
  your `<section>` element so the nav anchor (`index.php#<title>`) works.
- **`render`** (optional): returns the frontend HTML
  (`function ($section, $bgcolor, $index)`; prepend `$bgcolor` to your section
  class for the alternating background). If the active theme ships a
  `templates/section-<type>.php` template, **the theme template wins** and your
  callback is not called — that is how themes can restyle plugin sections.
  Without both, the section renders empty (the `opcms_section_html` filter
  still runs).
- **`form_url`** (required): where the admin is redirected for New/Edit/Delete.
  Core appends `action=New|Edit|Delete` and (for Edit/Delete) `id=<sections.id>`
  — `?` vs `&` is auto-detected. Typically this is an `opcms_admin_pages` page
  (`../core/extension.php?page=...`). Map `id` to your `specialid` via
  `SQLSectionActions::getSectionRow($id)`. Render Delete as a readonly
  confirmation form, like the built-in types do.
- **Saving**: point your form's POST at
  `../misc/extension.php?handler=<yourslug>` and register the callback via the
  `opcms_extension_handlers` filter. The dispatcher checks the admin session and
  runs before any output, so your handler can finish with a
  `header('Location: ../core/sections.php')` (or
  `../core/success.php?reason=sectionchanged`) redirect.
- **Cleanup**: in your uninstall hook, drop your table and call
  `SQLSectionActions::deleteSectionEntriesByType($type)`.
- **Deactivation semantics**: while your plugin is inactive, the frontend
  silently skips your sections (no errors, no nav entries). The admin Sections
  page lists them greyed out with a delete-only button so users can remove
  orphans — deleting there only removes the registry row, never your data.

A complete working reference is the **gallery-example** plugin (separate
repository next to the CMS): title + image-URL list rendered as a responsive
grid, with activate/uninstall hooks, admin form and POST handler.

## Themes

The default theme lives in `themes/agency/` and reproduces the classic
OnePagerCMS output byte-for-byte. A custom theme only needs `theme.json` plus
the templates it wants to override — anything missing falls back to the default
theme. Available templates:

`index` (whole page), `head`, `styles`, `nav`, `header`, `section-standard`,
`section-icons`, `section-contact`, `sections-empty`, `footer`, `jsembed`,
`page` (additional pages).

Inside a template you have `$opcmsTheme` (the engine: `render()`, `assetUrl()`)
and `$opcmsData` plus the extracted data variables (see the default templates
for what each receives). The active theme is switched on the Design page and
stored in the `active-theme` setting.

## Paid extensions & license keys

The marketplace only *lists* paid items with a purchase link — payment, license
validation and update delivery run entirely through the developer's own server:

1. The customer buys on your site and receives the ZIP + a license key.
2. They install the ZIP via Upload and enter the license key on the Extensions
   page (stored per extension).
3. Updates: the CMS calls your `update_endpoint`:

```
GET {update_endpoint}?opcms_action=check_update&slug=&version=&license=&site=
  → { "slug", "new_version", "package": "<authenticated zip url>", "changelog", "requires_opcms" }
  → { "success": false, "error": "invalid|expired|site_limit" }  when the license is bad

GET {update_endpoint}?opcms_action=activate_license&slug=&license=&site=
  → { "success": true } | { "success": false, "error": "..." }
```

A ready-to-adapt reference implementation is available in the marketplace
repository at `docs/license-server-example.php`.

## Marketplace API consumed by the CMS

Base URL: setting `marketplace-url` (defaults to the official marketplace).

```
GET /api/v1/items?type=&search=&page=      list approved items
GET /api/v1/items/{slug}                   detail incl. download_url (null for paid)
GET /api/v1/items/{slug}/download          free items only
GET /api/v1/updates?items[]=slug:version   bulk update check
```

Responses are cached in the `settings` table for 6 hours; stale cache is served
when the marketplace is unreachable.
