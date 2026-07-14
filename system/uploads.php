<?php
/**
 * Upload path helpers.
 *
 * Deletion of user-supplied paths must be constrained to the known upload
 * directories, otherwise a crafted `background-image` value such as
 * `../database/SQLiteDatabase.db` would let a request delete arbitrary files.
 */

/**
 * Return a safe same-origin redirect target derived from the Referer header,
 * or $fallback when the Referer is absent, cross-origin or malformed. Prevents
 * open redirects / header injection via a user-controlled Referer.
 */
function opcms_safe_referer(string $fallback): string
{
    $ref = $_SERVER['HTTP_REFERER'] ?? '';
    $parts = parse_url($ref);
    $host = $_SERVER['HTTP_HOST'] ?? '';
    if (is_array($parts) && isset($parts['path'])
        && (!isset($parts['host']) || $parts['host'] === $host)
        && preg_match('#^[A-Za-z0-9/_.\-]+\.php$#', $parts['path'])) {
        $query = isset($parts['query']) ? preg_replace('/[^A-Za-z0-9=&_%\-.]/', '', $parts['query']) : '';
        return $parts['path'] . ($query !== '' ? '?' . $query : '');
    }
    return $fallback;
}

function opcms_upload_dirs(): array
{
    return array(
        realpath(__DIR__ . '/../img/backgrounds'),
        realpath(__DIR__ . '/../img/logo'),
        realpath(__DIR__ . '/../img/favicon'),
    );
}

/**
 * Delete a file only if it resolves to a real file inside one of the upload
 * directories. Returns true on successful deletion, false otherwise.
 */
function opcms_delete_upload($path): bool
{
    if (!is_string($path) || $path === '') {
        return false;
    }
    $real = realpath($path);
    if ($real === false || !is_file($real)) {
        return false;
    }
    foreach (opcms_upload_dirs() as $dir) {
        if ($dir !== false && strncmp($real, $dir . DIRECTORY_SEPARATOR, strlen($dir) + 1) === 0) {
            return @unlink($real);
        }
    }
    return false;
}
