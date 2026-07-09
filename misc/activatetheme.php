<?php
session_start();
if (!isset($_SESSION['profile'])) {
    header('Location: ../core/opcms-login.php');
    die();
}
require_once '../system/bootstrap.php';
include_once '../database/SQLExtensionActions.php';
include_once '../database/SQLSettingActions.php';

$slug = isset($_POST['slug']) ? $_POST['slug'] : '';
if (!preg_match('/^[a-z0-9][a-z0-9\-]{2,49}$/', $slug)) {
    header('Location: ../core/error.php?reason=themenotfound');
    die();
}

$extensionActions = new SQLExtensionActions();
$extensionActions->ensureMessages();

if (!is_file(OPCMS_ROOT . '/themes/' . $slug . '/theme.json')) {
    header('Location: ../core/error.php?reason=themenotfound');
    die();
}

$extensionActions->activateTheme($slug);

$settingActions = new SQLSettingActions();
($settingActions->setSettingValue('active-theme', $slug))
    ? header('Location: ../core/success.php?reason=themeactivated')
    : header('Location: ../core/error.php?reason=dberror');
