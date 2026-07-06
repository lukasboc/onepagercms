<?php
session_start();
if (!isset($_SESSION['profile'])) {
    header('Location: ../core/opcms-login.php');
    die();
}
require_once '../system/bootstrap.php';
require_once '../system/Installer.php';
require_once '../system/MarketplaceClient.php';
include_once '../database/SQLExtensionActions.php';

$extensionActions = new SQLExtensionActions();
$extensionActions->ensureMessages();

$slug = isset($_POST['slug']) ? $_POST['slug'] : '';
if (!preg_match('/^[a-z0-9][a-z0-9\-]{2,49}$/', $slug)) {
    header('Location: ../core/error.php?reason=extensionnotfound');
    die();
}

$marketplaceClient = new MarketplaceClient();
$item = $marketplaceClient->getItem($slug);
if ($item === null) {
    header('Location: ../core/error.php?reason=marketplaceunreachable');
    die();
}
if (!empty($item['is_paid']) || empty($item['download_url'])) {
    header('Location: ../core/error.php?reason=extensionnotfree');
    die();
}

$tempFile = $marketplaceClient->downloadToTemp($item['download_url']);
if ($tempFile === null) {
    header('Location: ../core/error.php?reason=downloadfailed');
    die();
}

$result = ExtensionInstaller::installFromZip($tempFile, $slug, 'marketplace');
unlink($tempFile);

($result['ok'])
    ? header('Location: ../core/success.php?reason=' . $result['reason'])
    : header('Location: ../core/error.php?reason=' . $result['reason']);
