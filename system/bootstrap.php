<?php
if (defined('OPCMS_BOOTSTRAPPED')) {
    return;
}
define('OPCMS_BOOTSTRAPPED', true);
define('OPCMS_ROOT', dirname(__DIR__));
define('OPCMS_VERSION', '1.2.0');

require_once __DIR__ . '/hooks.php';
if (is_file(__DIR__ . '/ThemeEngine.php')) {
    require_once __DIR__ . '/ThemeEngine.php';
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
