<?php
/**
 * Central guard for state-changing admin handlers in misc/.
 *
 * Every handler that writes/deletes data or settings must `require` this file
 * as its first statement. It enforces an authenticated session and, for POST
 * requests, a valid CSRF token. Public endpoints (login, contact form, password
 * reset, first-run install) must NOT include it.
 */

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['profile'])) {
    header('Location: ../core/opcms-login.php');
    die();
}

require_once __DIR__ . '/../../system/csrf.php';
require_once __DIR__ . '/../../system/uploads.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && !opcms_csrf_verify()) {
    header('Location: ../core/error.php?reason=csrf');
    die();
}
