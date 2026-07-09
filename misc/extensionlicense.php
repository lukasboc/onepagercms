<?php
session_start();
if (!isset($_SESSION['profile'])) {
    header('Location: ../core/opcms-login.php');
    die();
}
require_once '../system/bootstrap.php';
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

$licenseKey = isset($_POST['license_key']) ? trim($_POST['license_key']) : '';

if (!$extensionActions->setLicenseKey($slug, $licenseKey)) {
    header('Location: ../core/error.php?reason=dberror');
    die();
}

// Best-effort validation against the developer's server when possible.
$updateEndpoint = $extension['update_endpoint'];
if ($licenseKey !== '' && $updateEndpoint !== null && preg_match('#^https?://#i', (string)$updateEndpoint)) {
    $marketplaceClient = new MarketplaceClient();
    $activateUrl = $updateEndpoint . (strpos($updateEndpoint, '?') === false ? '?' : '&') . http_build_query(array(
        'opcms_action' => 'activate_license',
        'slug' => $slug,
        'license' => $licenseKey,
        'site' => isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '',
    ));
    $body = $marketplaceClient->httpGet($activateUrl);
    if ($body !== null) {
        $response = json_decode($body, true);
        if (is_array($response) && isset($response['success']) && $response['success'] === false) {
            header('Location: ../core/error.php?reason=licenseinvalid');
            die();
        }
    }
}

header('Location: ../core/success.php?reason=licensesaved');
