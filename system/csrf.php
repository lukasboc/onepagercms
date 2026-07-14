<?php
/**
 * CSRF protection helpers.
 *
 * Standalone and side-effect free: a session must already be started by the
 * caller (core/inc/header.php for admin pages, misc/inc/auth.php for handlers).
 */

function opcms_csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function opcms_csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="'
        . htmlspecialchars(opcms_csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}

function opcms_csrf_verify(): bool
{
    $token = $_POST['csrf_token'] ?? '';
    return is_string($token) && $token !== ''
        && !empty($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}
