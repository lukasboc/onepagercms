<?php
session_start();
if (!isset($_SESSION['profile'])) {
    header('Location: ../core/opcms-login.php');
    die();
}
require_once '../system/bootstrap.php';

// Generic POST dispatcher for plugin form handlers. Runs before any output so
// plugin callbacks can redirect via header(); admin-only by design.
$opcmsHandlers = apply_filters('opcms_extension_handlers', array());
$opcmsHandler = isset($_GET['handler']) ? $_GET['handler'] : '';

if (isset($opcmsHandlers[$opcmsHandler]) && is_callable($opcmsHandlers[$opcmsHandler])) {
    call_user_func($opcmsHandlers[$opcmsHandler]);
} else {
    header('Location: ../core/error.php?reason=unknownextensionpage');
}
