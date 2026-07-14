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
require_once '../system/Installer.php';
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

if ($extension['type'] === 'plugin') {
    $main = OPCMS_ROOT . '/extensions/' . $slug . '/' . $extension['main'];
    if (is_file($main)) {
        include_once $main;
    }
    do_action('opcms_uninstall_' . $slug);
} else {
    $settingActions = new SQLSettingActions();
    if ($settingActions->getSettingValue('active-theme') === $slug) {
        $settingActions->setSettingValue('active-theme', '');
    }
}

ExtensionInstaller::removeExtensionDir($slug, $extension['type']);

($extensionActions->deleteExtension($slug))
    ? header('Location: ../core/success.php?reason=extensiondeleted')
    : header('Location: ../core/error.php?reason=dberror');
