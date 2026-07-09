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

$manifest = ThemeEngine::readManifest($slug);
$declaredOptions = ThemeEngine::declaredOptions($manifest);
if (count($declaredOptions) === 0) {
    header('Location: ../core/error.php?reason=themenotfound');
    die();
}

$settingActions = new SQLSettingActions();

if (isset($_POST['reset']) && $_POST['reset'] === '1') {
    ($settingActions->deleteSettingsByPrefix('theme-option:' . $slug . ':'))
        ? header('Location: ../core/success.php?reason=themeoptionssaved')
        : header('Location: ../core/error.php?reason=dberror');
    die();
}

$posted = (isset($_POST['options']) && is_array($_POST['options'])) ? $_POST['options'] : array();

// Only keys declared in the theme manifest are ever written.
foreach ($declaredOptions as $option) {
    if (!array_key_exists($option['key'], $posted) || !is_string($posted[$option['key']])) {
        continue;
    }
    $value = trim($posted[$option['key']]);
    if ($option['type'] === 'select' && $value !== '' && !in_array($value, $option['choices'], true)) {
        header('Location: ../core/error.php?reason=criticalinput');
        die();
    }
    if (!$settingActions->setSettingValue('theme-option:' . $slug . ':' . $option['key'], $value)) {
        header('Location: ../core/error.php?reason=dberror');
        die();
    }
}

header('Location: ../core/success.php?reason=themeoptionssaved');
