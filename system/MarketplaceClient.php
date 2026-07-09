<?php
if (!defined('OPCMS_ROOT')) {
    define('OPCMS_ROOT', dirname(__DIR__));
}

class MarketplaceClient
{
    const DEFAULT_API_BASE = 'https://marketplace.onepagercms.de/api/v1';
    const CACHE_TTL = 21600; // 6 hours
    const HTTP_TIMEOUT = 10;

    public function apiBase(): string
    {
        $configured = $this->getSettingSafe('marketplace-url');
        return ($configured !== null && $configured !== '') ? rtrim($configured, '/') : self::DEFAULT_API_BASE;
    }

    public function isAvailable(): bool
    {
        return function_exists('curl_init') || ini_get('allow_url_fopen');
    }

    public function listItems($type = '', $search = '', $page = 1): ?array
    {
        $query = array('page' => max(1, (int)$page));
        if (in_array($type, array('plugin', 'theme'), true)) {
            $query['type'] = $type;
        }
        if ($search !== '') {
            $query['search'] = $search;
        }
        $body = $this->httpGetCached($this->apiBase() . '/items?' . http_build_query($query), self::CACHE_TTL);
        if ($body === null) {
            return null;
        }
        $decoded = json_decode($body, true);
        return (is_array($decoded) && isset($decoded['data'])) ? $decoded : null;
    }

    public function getItem($slug): ?array
    {
        if (!preg_match('/^[a-z0-9][a-z0-9\-]{2,49}$/', $slug)) {
            return null;
        }
        $body = $this->httpGet($this->apiBase() . '/items/' . rawurlencode($slug));
        if ($body === null) {
            return null;
        }
        $decoded = json_decode($body, true);
        return (is_array($decoded) && isset($decoded['slug'])) ? $decoded : null;
    }

    /**
     * @param array $slugVersionPairs e.g. array('my-plugin' => '1.0.0')
     * @return array map slug => update info (empty when none or unreachable)
     */
    public function checkUpdates(array $slugVersionPairs): array
    {
        if (count($slugVersionPairs) === 0) {
            return array();
        }
        $params = array();
        foreach ($slugVersionPairs as $slug => $version) {
            $params[] = 'items[]=' . rawurlencode($slug . ':' . $version);
        }
        $body = $this->httpGetCached($this->apiBase() . '/updates?' . implode('&', $params), self::CACHE_TTL);
        if ($body === null) {
            return array();
        }
        $decoded = json_decode($body, true);
        return (is_array($decoded) && isset($decoded['updates']) && is_array($decoded['updates'])) ? $decoded['updates'] : array();
    }

    public function downloadToTemp($url): ?string
    {
        if (!preg_match('#^https?://#i', $url)) {
            return null;
        }
        $tempFile = tempnam(sys_get_temp_dir(), 'opcms-download-');
        if ($tempFile === false) {
            return null;
        }

        if (function_exists('curl_init')) {
            $out = fopen($tempFile, 'wb');
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_FILE, $out);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
            curl_setopt($curl, CURLOPT_TIMEOUT, 60);
            curl_setopt($curl, CURLOPT_USERAGENT, 'OnePagerCMS/' . (defined('OPCMS_VERSION') ? OPCMS_VERSION : '1.x'));
            $ok = curl_exec($curl);
            $status = (int)curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
            curl_close($curl);
            fclose($out);
            if ($ok === false || $status >= 400 || filesize($tempFile) === 0) {
                unlink($tempFile);
                return null;
            }
            return $tempFile;
        }

        $context = stream_context_create(array('http' => array('timeout' => 60, 'follow_location' => 1)));
        $content = @file_get_contents($url, false, $context);
        if ($content === false || $content === '') {
            unlink($tempFile);
            return null;
        }
        file_put_contents($tempFile, $content);
        return $tempFile;
    }

    public function httpGet($url): ?string
    {
        if (function_exists('curl_init')) {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
            curl_setopt($curl, CURLOPT_TIMEOUT, self::HTTP_TIMEOUT);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json'));
            curl_setopt($curl, CURLOPT_USERAGENT, 'OnePagerCMS/' . (defined('OPCMS_VERSION') ? OPCMS_VERSION : '1.x'));
            $body = curl_exec($curl);
            $status = (int)curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
            curl_close($curl);
            return ($body === false || $status >= 400) ? null : $body;
        }

        if (!ini_get('allow_url_fopen')) {
            return null;
        }
        $context = stream_context_create(array('http' => array(
            'timeout' => self::HTTP_TIMEOUT,
            'follow_location' => 1,
            'header' => "Accept: application/json\r\nUser-Agent: OnePagerCMS/" . (defined('OPCMS_VERSION') ? OPCMS_VERSION : '1.x') . "\r\n",
        )));
        $body = @file_get_contents($url, false, $context);
        return ($body === false) ? null : $body;
    }

    private function httpGetCached($url, $ttl): ?string
    {
        $cacheKey = 'marketplace-cache-' . md5($url);
        $cached = $this->getSettingSafe($cacheKey);
        if ($cached !== null && $cached !== '') {
            $entry = json_decode($cached, true);
            if (is_array($entry) && isset($entry['t'], $entry['body']) && (time() - (int)$entry['t']) < $ttl) {
                return $entry['body'];
            }
        }

        $body = $this->httpGet($url);
        if ($body === null) {
            // Serve stale cache when the marketplace is unreachable.
            if (isset($entry) && is_array($entry) && isset($entry['body'])) {
                return $entry['body'];
            }
            return null;
        }

        $this->setSettingSafe($cacheKey, json_encode(array('t' => time(), 'body' => $body)));
        return $body;
    }

    private function getSettingSafe($setting): ?string
    {
        try {
            if (!is_file('../database/connect.php')) {
                return null;
            }
            include '../database/connect.php';
            if (!isset($db)) {
                return null;
            }
            $select = $db->prepare('SELECT value FROM settings WHERE setting = :setting;');
            $select->bindValue(':setting', $setting);
            $select->execute();
            $row = $select->fetch();
            return ($row === false || !isset($row[0])) ? null : (string)$row[0];
        } catch (Throwable $throwable) {
            return null;
        }
    }

    private function setSettingSafe($setting, $value): void
    {
        try {
            include_once '../database/SQLSettingActions.php';
            $settingActions = new SQLSettingActions();
            $settingActions->setSettingValue($setting, $value);
        } catch (Throwable $throwable) {
            // Caching is best-effort only.
        }
    }
}
