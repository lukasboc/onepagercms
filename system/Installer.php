<?php
if (!defined('OPCMS_ROOT')) {
    define('OPCMS_ROOT', dirname(__DIR__));
}

class ExtensionInstaller
{
    const MAX_ZIP_SIZE = 20971520; // 20 MB

    /**
     * Installs (or updates) a plugin/theme from a ZIP archive.
     * Returns array('ok' => bool, 'reason' => string, 'manifest' => array|null, 'updated' => bool).
     */
    public static function installFromZip($zipPath, $expectedSlug = null, $source = 'upload'): array
    {
        if (!class_exists('ZipArchive')) {
            return self::fail('zipmissing');
        }
        if (!is_file($zipPath) || filesize($zipPath) > self::MAX_ZIP_SIZE) {
            return self::fail('extensiontoobig');
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            return self::fail('zipinvalid');
        }

        $entries = array();
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);
            if ($name === false || $name === '') {
                $zip->close();
                return self::fail('zipinvalid');
            }
            if (strpos($name, '..') !== false || strpos($name, '\\') !== false || strpos($name, "\0") !== false || $name[0] === '/') {
                $zip->close();
                return self::fail('zipslip');
            }
            $entries[] = $name;
        }

        $rootPrefix = self::detectRootPrefix($entries);
        $manifest = self::readManifestFromZip($zip, $rootPrefix);
        if ($manifest === null) {
            $zip->close();
            return self::fail('manifestmissing');
        }

        $manifestError = self::validateManifest($manifest);
        if ($manifestError !== null) {
            $zip->close();
            return self::fail($manifestError);
        }
        if ($expectedSlug !== null && $manifest['slug'] !== $expectedSlug) {
            $zip->close();
            return self::fail('slugmismatch');
        }

        $baseDir = OPCMS_ROOT . '/' . ($manifest['type'] === 'theme' ? 'themes' : 'extensions');
        if (!is_dir($baseDir) && !mkdir($baseDir, 0755, true)) {
            $zip->close();
            return self::fail('extensionwritefailed');
        }

        $stagingDir = $baseDir . '/.staging-' . uniqid('', true);
        if (!mkdir($stagingDir, 0755, true)) {
            $zip->close();
            return self::fail('extensionwritefailed');
        }

        foreach ($entries as $entry) {
            $relative = ($rootPrefix === '') ? $entry : substr($entry, strlen($rootPrefix));
            if ($relative === '' || $relative === false) {
                continue;
            }
            $target = $stagingDir . '/' . $relative;
            if (substr($entry, -1) === '/') {
                if (!is_dir($target) && !mkdir($target, 0755, true)) {
                    self::deleteDir($stagingDir);
                    $zip->close();
                    return self::fail('extensionwritefailed');
                }
                continue;
            }
            $targetDir = dirname($target);
            if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true)) {
                self::deleteDir($stagingDir);
                $zip->close();
                return self::fail('extensionwritefailed');
            }
            $content = $zip->getFromName($entry);
            if ($content === false || file_put_contents($target, $content) === false) {
                self::deleteDir($stagingDir);
                $zip->close();
                return self::fail('extensionwritefailed');
            }
        }
        $zip->close();

        $targetDir = $baseDir . '/' . $manifest['slug'];
        $isUpdate = is_dir($targetDir);
        $backupDir = null;

        if ($isUpdate) {
            $backupDir = $baseDir . '/.old-' . uniqid('', true);
            if (!rename($targetDir, $backupDir)) {
                self::deleteDir($stagingDir);
                return self::fail('extensionwritefailed');
            }
        }
        if (!rename($stagingDir, $targetDir)) {
            if ($backupDir !== null) {
                rename($backupDir, $targetDir);
            }
            self::deleteDir($stagingDir);
            return self::fail('extensionwritefailed');
        }
        if ($backupDir !== null) {
            self::deleteDir($backupDir);
        }

        include_once OPCMS_ROOT . '/database/SQLExtensionActions.php';
        $extensionActions = new SQLExtensionActions();
        $extensionActions->registerExtension($manifest, $source);

        return array('ok' => true, 'reason' => $isUpdate ? 'extensionupdated' : 'extensioninstalled', 'manifest' => $manifest, 'updated' => $isUpdate);
    }

    /**
     * If every entry lives inside exactly one top-level directory, returns that
     * prefix (with trailing slash) so it can be stripped during extraction.
     */
    private static function detectRootPrefix(array $entries): string
    {
        $prefix = null;
        foreach ($entries as $entry) {
            $slash = strpos($entry, '/');
            if ($slash === false) {
                return '';
            }
            $top = substr($entry, 0, $slash + 1);
            if ($prefix === null) {
                $prefix = $top;
            } elseif ($prefix !== $top) {
                return '';
            }
        }
        return ($prefix === null) ? '' : $prefix;
    }

    public static function readManifestFromZip(ZipArchive $zip, $rootPrefix = ''): ?array
    {
        foreach (array('plugin.json', 'theme.json') as $manifestName) {
            $content = $zip->getFromName($rootPrefix . $manifestName);
            if ($content !== false) {
                $manifest = json_decode($content, true);
                return is_array($manifest) ? $manifest : null;
            }
        }
        return null;
    }

    /**
     * Returns an error reason string or null when the manifest is valid.
     */
    public static function validateManifest(array $manifest): ?string
    {
        if (!isset($manifest['slug'], $manifest['type'], $manifest['name'], $manifest['version'])) {
            return 'manifestinvalid';
        }
        if (!preg_match('/^[a-z0-9][a-z0-9\-]{2,49}$/', $manifest['slug'])) {
            return 'manifestinvalid';
        }
        if (!in_array($manifest['type'], array('plugin', 'theme'), true)) {
            return 'manifestinvalid';
        }
        if (!preg_match('/^\d+\.\d+(\.\d+)?$/', $manifest['version'])) {
            return 'manifestinvalid';
        }
        if ($manifest['type'] === 'plugin') {
            if (empty($manifest['main']) || !is_string($manifest['main'])) {
                return 'manifestinvalid';
            }
            if (strpos($manifest['main'], '..') !== false || $manifest['main'][0] === '/' || substr($manifest['main'], -4) !== '.php') {
                return 'manifestinvalid';
            }
        }
        if (!empty($manifest['requires_opcms']) && defined('OPCMS_VERSION') && version_compare(OPCMS_VERSION, $manifest['requires_opcms'], '<')) {
            return 'requiresnewercms';
        }
        if (!empty($manifest['requires_php']) && version_compare(PHP_VERSION, $manifest['requires_php'], '<')) {
            return 'manifestinvalid';
        }
        return null;
    }

    public static function removeExtensionDir($slug, $type): bool
    {
        if (!preg_match('/^[a-z0-9][a-z0-9\-]{2,49}$/', $slug)) {
            return false;
        }
        $baseDir = realpath(OPCMS_ROOT . '/' . ($type === 'theme' ? 'themes' : 'extensions'));
        if ($baseDir === false) {
            return false;
        }
        $target = realpath($baseDir . '/' . $slug);
        if ($target === false || strpos($target, $baseDir . DIRECTORY_SEPARATOR) !== 0) {
            return false;
        }
        return self::deleteDir($target);
    }

    private static function deleteDir($dir): bool
    {
        if (!is_dir($dir)) {
            return false;
        }
        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir . '/' . $item;
            if (is_dir($path) && !is_link($path)) {
                self::deleteDir($path);
            } else {
                unlink($path);
            }
        }
        return rmdir($dir);
    }

    private static function fail($reason): array
    {
        return array('ok' => false, 'reason' => $reason, 'manifest' => null, 'updated' => false);
    }
}
