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

$extensionActions = new SQLExtensionActions();
$extensionActions->ensureMessages();

if (!isset($_FILES['extension']) || $_FILES['extension']['error'] !== UPLOAD_ERR_OK) {
    header('Location: ../core/error.php?reason=zipinvalid');
    die();
}

$extension = strtolower(pathinfo($_FILES['extension']['name'], PATHINFO_EXTENSION));
if ($extension !== 'zip') {
    header('Location: ../core/error.php?reason=zipinvalid');
    die();
}

if ($_FILES['extension']['size'] > ExtensionInstaller::MAX_ZIP_SIZE) {
    header('Location: ../core/error.php?reason=extensiontoobig');
    die();
}

$result = ExtensionInstaller::installFromZip($_FILES['extension']['tmp_name'], null, 'upload');

($result['ok'])
    ? header('Location: ../core/success.php?reason=' . $result['reason'])
    : header('Location: ../core/error.php?reason=' . $result['reason']);
