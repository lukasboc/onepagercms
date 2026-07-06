<?php

class SQLExtensionActions
{
    private function ensureTable($db): void
    {
        $db->exec("CREATE TABLE IF NOT EXISTS extensions
            (
            slug VARCHAR(100) PRIMARY KEY,
            type VARCHAR(10) NOT NULL,
            name VARCHAR(255) NOT NULL,
            version VARCHAR(20) NOT NULL,
            main VARCHAR(255) NULL,
            active int NOT NULL DEFAULT 0,
            paid int NOT NULL DEFAULT 0,
            update_endpoint TEXT NULL,
            license_key TEXT NULL,
            source VARCHAR(20) NOT NULL DEFAULT 'upload',
            installed_at TEXT NOT NULL,
            meta TEXT NULL
            )");
    }

    public function getAllExtensions(): array
    {
        include '../database/connect.php';
        try {
            $this->ensureTable($db);
            $select = $db->prepare('SELECT * FROM extensions ORDER BY type, name;');
            $select->execute();
            return $select->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
            return array();
        }
    }

    public function getActivePlugins(): array
    {
        include '../database/connect.php';
        $this->ensureTable($db);
        $select = $db->prepare("SELECT * FROM extensions WHERE type = 'plugin' AND active = 1 ORDER BY name;");
        $select->execute();
        return $select->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getExtension($slug): ?array
    {
        include '../database/connect.php';
        try {
            $this->ensureTable($db);
            $select = $db->prepare('SELECT * FROM extensions WHERE slug = :slug;');
            $select->bindValue(':slug', $slug);
            $select->execute();
            $extension = $select->fetch(PDO::FETCH_ASSOC);
            return ($extension === false) ? null : $extension;
        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
            return null;
        }
    }

    public function registerExtension(array $manifest, $source): bool
    {
        include '../database/connect.php';
        try {
            $this->ensureTable($db);
            $existing = $this->getExtension($manifest['slug']);

            if ($existing === null) {
                $insert = $db->prepare('INSERT INTO extensions (`slug`, `type`, `name`, `version`, `main`, `active`, `paid`, `update_endpoint`, `license_key`, `source`, `installed_at`, `meta`) VALUES (:slug, :type, :name, :version, :main, 0, :paid, :update_endpoint, NULL, :source, :installed_at, :meta)');
                $insert->bindValue(':source', $source);
                $insert->bindValue(':installed_at', date('Y-m-d H:i:s'));
            } else {
                $insert = $db->prepare('UPDATE extensions SET type = :type, name = :name, version = :version, main = :main, paid = :paid, update_endpoint = :update_endpoint, meta = :meta WHERE slug = :slug');
            }

            $insert->bindValue(':slug', $manifest['slug']);
            $insert->bindValue(':type', $manifest['type']);
            $insert->bindValue(':name', $manifest['name']);
            $insert->bindValue(':version', $manifest['version']);
            $insert->bindValue(':main', isset($manifest['main']) ? $manifest['main'] : null);
            $insert->bindValue(':paid', empty($manifest['paid']) ? 0 : 1);
            $insert->bindValue(':update_endpoint', isset($manifest['update_endpoint']) ? $manifest['update_endpoint'] : null);
            $insert->bindValue(':meta', json_encode($manifest));

            return $insert->execute() ? true : false;
        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
            return false;
        }
    }

    public function setActive($slug, $active): bool
    {
        include '../database/connect.php';
        try {
            $this->ensureTable($db);
            $update = $db->prepare('UPDATE extensions SET active = :active WHERE slug = :slug;');
            $update->bindValue(':slug', $slug);
            $update->bindValue(':active', $active ? 1 : 0);
            return $update->execute() ? true : false;
        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
            return false;
        }
    }

    public function activateTheme($slug): bool
    {
        include '../database/connect.php';
        try {
            $this->ensureTable($db);
            $db->exec("UPDATE extensions SET active = 0 WHERE type = 'theme'");
            $update = $db->prepare("UPDATE extensions SET active = 1 WHERE slug = :slug AND type = 'theme';");
            $update->bindValue(':slug', $slug);
            return $update->execute() ? true : false;
        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
            return false;
        }
    }

    public function setVersion($slug, $version): bool
    {
        include '../database/connect.php';
        try {
            $this->ensureTable($db);
            $update = $db->prepare('UPDATE extensions SET version = :version WHERE slug = :slug;');
            $update->bindValue(':slug', $slug);
            $update->bindValue(':version', $version);
            return $update->execute() ? true : false;
        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
            return false;
        }
    }

    public function setLicenseKey($slug, $key): bool
    {
        include '../database/connect.php';
        try {
            $this->ensureTable($db);
            $update = $db->prepare('UPDATE extensions SET license_key = :license_key WHERE slug = :slug;');
            $update->bindValue(':slug', $slug);
            $update->bindValue(':license_key', ($key === null || $key === '') ? null : $key);
            return $update->execute() ? true : false;
        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
            return false;
        }
    }

    public function deleteExtension($slug): bool
    {
        include '../database/connect.php';
        try {
            $this->ensureTable($db);
            $delete = $db->prepare('DELETE FROM extensions WHERE slug = :slug;');
            $delete->bindValue(':slug', $slug);
            return $delete->execute() ? true : false;
        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
            return false;
        }
    }

    public function ensureMessages(): void
    {
        include '../database/connect.php';
        try {
            $errors = array(
                array(500, 'zipinvalid', 'Invalid file', 'The uploaded file is not a valid ZIP archive.'),
                array(501, 'zipmissing', 'ZIP support missing', 'The PHP zip extension (ZipArchive) is not available on this server.'),
                array(502, 'zipslip', 'Unsafe archive', 'The archive contains unsafe file paths and was rejected.'),
                array(503, 'manifestmissing', 'Manifest missing', 'The archive does not contain a plugin.json or theme.json manifest.'),
                array(504, 'manifestinvalid', 'Manifest invalid', 'The manifest of this extension is invalid or incomplete.'),
                array(505, 'extensiontoobig', 'File too big', 'The uploaded archive exceeds the maximum allowed size.'),
                array(506, 'extensionnotfound', 'Extension not found', 'The requested extension is not installed.'),
                array(507, 'extensionwritefailed', 'Write failed', 'The extension could not be written to disk. Please check directory permissions.'),
                array(508, 'requiresnewercms', 'CMS update required', 'This extension requires a newer version of OnePagerCMS.'),
                array(509, 'downloadfailed', 'Download failed', 'The extension package could not be downloaded.'),
                array(510, 'licenseinvalid', 'License invalid', 'The license key was rejected by the developer\'s server.'),
                array(511, 'unknownextensionpage', 'Unknown page', 'The requested extension page is not registered.'),
                array(512, 'themenotfound', 'Theme not found', 'The requested theme is not installed.'),
                array(513, 'slugmismatch', 'Slug mismatch', 'The archive contains a different extension than expected.'),
                array(514, 'marketplaceunreachable', 'Marketplace unreachable', 'The marketplace could not be reached. Please try again later.'),
                array(515, 'extensionnotfree', 'Paid extension', 'This extension is paid and must be obtained from the developer.'),
            );
            $successes = array(
                array(500, 'extensioninstalled', 'Extension installed', 'The extension was installed successfully. Activate it in the list of installed extensions.'),
                array(501, 'extensionactivated', 'Extension activated', 'The extension was activated successfully.'),
                array(502, 'extensiondeactivated', 'Extension deactivated', 'The extension was deactivated successfully.'),
                array(503, 'extensiondeleted', 'Extension deleted', 'The extension was deleted successfully.'),
                array(504, 'extensionupdated', 'Extension updated', 'The extension was updated successfully.'),
                array(505, 'themeactivated', 'Theme activated', 'The theme was activated successfully.'),
                array(506, 'licensesaved', 'License saved', 'The license key was saved successfully.'),
            );

            $insertError = $db->prepare('INSERT OR IGNORE INTO error (`id`, `reason`, `headline`, `message`) VALUES (:id, :reason, :headline, :message)');
            foreach ($errors as $row) {
                $insertError->bindValue(':id', $row[0]);
                $insertError->bindValue(':reason', $row[1]);
                $insertError->bindValue(':headline', $row[2]);
                $insertError->bindValue(':message', $row[3]);
                $insertError->execute();
            }

            $insertSuccess = $db->prepare('INSERT OR IGNORE INTO success (`id`, `reason`, `headline`, `message`) VALUES (:id, :reason, :headline, :message)');
            foreach ($successes as $row) {
                $insertSuccess->bindValue(':id', $row[0]);
                $insertSuccess->bindValue(':reason', $row[1]);
                $insertSuccess->bindValue(':headline', $row[2]);
                $insertSuccess->bindValue(':message', $row[3]);
                $insertSuccess->execute();
            }
        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }
    }
}
