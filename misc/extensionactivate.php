<?php
session_start();
if (!isset($_SESSION['profile'])) {
    header('Location: ../core/opcms-login.php');
    die();
}
require_once '../system/csrf.php';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && !opcms_csrf_verify()) {
    header('Location: ../core/error.php?reason=csrf');
    die();
}
require_once '../system/bootstrap.php';
include_once '../database/SQLExtensionActions.php';
include_once '../database/SQLSettingActions.php';

$slug = isset($_POST['slug']) ? $_POST['slug'] : '';
if (!preg_match('/^[a-z0-9][a-z0-9\-]{2,49}$/', $slug)) {
    header('Location: ../core/error.php?reason=extensionnotfound');
    die();
}

$extensionActions = new SQLExtensionActions();
$extensionActions->ensureMessages();
$extension = $extensionActions->getExtension($slug);
if ($extension === null) {
    header('Location: ../core/error.php?reason=extensionnotfound');
    die();
}

if ($extension['type'] === 'theme') {
    if (!is_file(OPCMS_ROOT . '/themes/' . $slug . '/theme.json')) {
        header('Location: ../core/error.php?reason=themenotfound');
        die();
    }
    $settingActions = new SQLSettingActions();
    $extensionActions->activateTheme($slug);
    ($settingActions->setSettingValue('active-theme', $slug))
        ? header('Location: ../core/success.php?reason=themeactivated')
        : header('Location: ../core/error.php?reason=dberror');
    die();
}

if ($extensionActions->setActive($slug, true)) {
    $main = OPCMS_ROOT . '/extensions/' . $slug . '/' . $extension['main'];
    if (is_file($main)) {
        include_once $main;
    }
    do_action('opcms_activate_' . $slug);
    header('Location: ../core/success.php?reason=extensionactivated');
} else {
    header('Location: ../core/error.php?reason=dberror');
}
