# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What is OnePagerCMS

OnePagerCMS (OPCMS) is a flat-file-style CMS for one-page websites. It requires no traditional database setup — data is stored in a SQLite file at `database/SQLiteDatabase.db`, which is created automatically on first access.

## Frontend Build (pages/ theme assets)

The `pages/` directory contains the Agency Bootstrap theme with a Gulp-based build:

```bash
cd pages
npm install        # install dependencies
npm start          # gulp watch (compiles SCSS, minifies JS, starts BrowserSync on :3000)
```

There are no build steps required at the root level — PHP files are served directly by a web server (Apache/Nginx with PHP).

## Architecture Overview

### Three-layer structure

**Public frontend** — `pages/index.php`  
Renders the live one-pager. Checks for installed users; if none, redirects to `core/install.php`. Pulls all sections, header, footer, and settings from the database and renders them inline.

**Admin backend** — `core/*.php`  
Each file is a backend page (sections list, design settings, account, FAQ, additional pages, etc.). Pages include `core/inc/head.php`, `core/inc/header.php`, and `core/inc/footer.php` for the admin shell.

**Action handlers** — `misc/*.php`  
These are POST/redirect handlers. Forms in `core/` submit to `misc/`, which performs the action then redirects back to `core/`. There is no REST API or AJAX layer.

### Database layer — `database/`

All data access goes through `SQL*Actions.php` classes:

| Class | Responsibility |
|---|---|
| `SQLSectionActions` | CRUD for sections (standard/icons/contact types) + HTML rendering |
| `SQLHeaderActions` | Header section |
| `SQLFooterActions` | Footer section |
| `SQLSettingActions` | Key-value settings table |
| `SQLUserActions` | User accounts |
| `SQLAdditionalPagesActions` | Standalone extra pages |
| `SQLFAQActions`, `SQLContactActions`, `SQLErrorActions`, `SQLSuccessActions` | Other content |

Model classes (`Standard.php`, `Icons.php`, `Contact.php`, `User.php`, `QuestionAndAnswer.php`) are simple value objects with getters/setters.

**Important**: `connect.php` is `include`d inside each method body individually — it creates `$db` as a local PDO variable every time. There is no dependency injection or singleton. New methods must follow this same pattern.

### Section types

Sections are stored in a `sections` table that acts as a registry (`id`, `type`, `specialid`, `position`). Each type has its own data table (`standard`, `icons`, `contact`). The `specialid` links a `sections` row to its type-specific row.

- **standard** — title, muted title, rich text body, optional background image
- **icons** — up to 8 Font Awesome icon + headline + text combinations
- **contact** — configurable contact form with optional name/email/message/reCAPTCHA fields

### Authentication

Session-based. Login submits to `misc/login.php`, which sets a session and redirects to `core/home.php`. Admin pages in `core/` check the session. `core/install.php` is only accessible when no users exist.

### Additional Pages

Standalone pages (not part of the one-pager scroll) managed via `core/additionalPages.php` and rendered at `pages/additionalpage.php?id=N`.

### Settings stored in database

Key-value pairs in the `settings` table control: primary/button/nav colors, logo, logo CSS, website title, custom CSS, Google Analytics ID, reCAPTCHA key, meta description, favicon paths.

## Key path relationships

- `index.php` (root) → redirects to `pages/index.php`
- `opcms-login.php` (root) → entry point for login, also at `core/opcms-login.php`
- Backend URLs all live under `core/`; form actions point to `misc/`
- Database path from `core/` or `misc/` is `../database/`; from `pages/` it is `../database/`
