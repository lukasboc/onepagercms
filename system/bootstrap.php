<?php
if (defined('OPCMS_BOOTSTRAPPED')) {
    return;
}
define('OPCMS_BOOTSTRAPPED', true);
define('OPCMS_ROOT', dirname(__DIR__));
define('OPCMS_VERSION', '1.2.1');

if (!function_exists('opcms_esc')) {
    /**
     * Escape a value for safe output in HTML text/attribute context.
     * Use for any admin-controlled string that is not intentionally raw HTML
     * (section titles, background paths, etc.). Rich-text bodies and the
     * custom-css/analytics settings are raw by design and must NOT be passed here.
     */
    function opcms_esc($value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

require_once __DIR__ . '/hooks.php';
if (is_file(__DIR__ . '/ThemeEngine.php')) {
    require_once __DIR__ . '/ThemeEngine.php';
}
if (is_file(__DIR__ . '/sections.php')) {
    require_once __DIR__ . '/sections.php';
}

if (class_exists('ThemeEngine') && !function_exists('opcms_theme')) {
    function opcms_theme(): ThemeEngine
    {
        static $opcmsThemeEngine = null;
        if ($opcmsThemeEngine === null) {
            $opcmsThemeEngine = new ThemeEngine();
        }
        return $opcmsThemeEngine;
    }
}

if (class_exists('ThemeEngine') && !function_exists('opcms_theme_option')) {
    /**
     * Value of a theme option declared in the active theme's theme.json,
     * saved on the Design page. Falls back to the manifest default, then
     * to $default when the option is unknown or unset.
     */
    function opcms_theme_option($key, $default = '')
    {
        try {
            $value = opcms_theme()->getOption($key);
        } catch (Throwable $opcmsThemeOptionError) {
            $value = '';
        }
        return ($value === '') ? $default : $value;
    }
}

// Load active plugins. Every step is guarded: without the extensions/ dir,
// the actions class or a readable extensions table the site must render as before.
if (is_dir(OPCMS_ROOT . '/extensions') && is_file(OPCMS_ROOT . '/database/SQLExtensionActions.php')) {
    include_once OPCMS_ROOT . '/database/SQLExtensionActions.php';
    try {
        $opcmsExtensionActions = new SQLExtensionActions();
        foreach ($opcmsExtensionActions->getActivePlugins() as $opcmsActiveExtension) {
            $opcmsPluginMain = OPCMS_ROOT . '/extensions/' . $opcmsActiveExtension['slug'] . '/' . $opcmsActiveExtension['main'];
            if (is_file($opcmsPluginMain)) {
                include_once $opcmsPluginMain;
            }
        }
        unset($opcmsExtensionActions, $opcmsActiveExtension, $opcmsPluginMain);
    } catch (Throwable $opcmsBootstrapError) {
        unset($opcmsBootstrapError);
    }
}
