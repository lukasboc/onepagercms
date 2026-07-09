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

$extension = $extensionActions->getExtension($slug);
if ($extension === null) {
    header('Location: ../core/error.php?reason=extensionnotfound');
    die();
}

$marketplaceClient = new MarketplaceClient();

if ((int)$extension['paid'] === 1) {
    // Paid extensions update from the developer's own server (EDD-style protocol).
    $updateEndpoint = $extension['update_endpoint'];
    if ($updateEndpoint === null || $updateEndpoint === '' || !preg_match('#^https?://#i', $updateEndpoint)) {
        header('Location: ../core/error.php?reason=downloadfailed');
        die();
    }

    $checkUrl = $updateEndpoint . (strpos($updateEndpoint, '?') === false ? '?' : '&') . http_build_query(array(
        'opcms_action' => 'check_update',
        'slug' => $slug,
        'version' => $extension['version'],
        'license' => (string)$extension['license_key'],
        'site' => isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '',
    ));

    $body = $marketplaceClient->httpGet($checkUrl);
    if ($body === null) {
        header('Location: ../core/error.php?reason=downloadfailed');
        die();
    }
    $updateInfo = json_decode($body, true);
    if (!is_array($updateInfo)) {
        header('Location: ../core/error.php?reason=downloadfailed');
        die();
    }
    if (isset($updateInfo['error']) || (isset($updateInfo['success']) && $updateInfo['success'] === false)) {
        header('Location: ../core/error.php?reason=licenseinvalid');
        die();
    }
    if (empty($updateInfo['new_version']) || version_compare($updateInfo['new_version'], $extension['version'], '<=')) {
        // Nothing newer available — go back without an error.
        header('Location: ../core/success.php?reason=extensionupdated');
        die();
    }
    if (empty($updateInfo['package'])) {
        header('Location: ../core/error.php?reason=downloadfailed');
        die();
    }
    $downloadUrl = $updateInfo['package'];
} else {
    $item = $marketplaceClient->getItem($slug);
    if ($item === null) {
        header('Location: ../core/error.php?reason=marketplaceunreachable');
        die();
    }
    if (empty($item['download_url'])) {
        header('Location: ../core/error.php?reason=extensionnotfree');
        die();
    }
    $downloadUrl = $item['download_url'];
}

$tempFile = $marketplaceClient->downloadToTemp($downloadUrl);
if ($tempFile === null) {
    header('Location: ../core/error.php?reason=downloadfailed');
    die();
}

$result = ExtensionInstaller::installFromZip($tempFile, $slug, $extension['source']);
unlink($tempFile);

($result['ok'])
    ? header('Location: ../core/success.php?reason=extensionupdated')
    : header('Location: ../core/error.php?reason=' . $result['reason']);
