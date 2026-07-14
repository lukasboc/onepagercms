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
        // Extension ZIPs are executed after install, so the download must be
        // authenticated (HTTPS to a public host) and free of SSRF redirects.
        if (!$this->isSafeUrl($url)) {
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
            $this->restrictToHttps($curl);
            curl_setopt($curl, CURLOPT_USERAGENT, 'OnePagerCMS/' . (defined('OPCMS_VERSION') ? OPCMS_VERSION : '1.x'));
            $ok = curl_exec($curl);
            $status = (int)curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
            $primaryIp = (string)curl_getinfo($curl, CURLINFO_PRIMARY_IP);
            curl_close($curl);
            fclose($out);
            // Reject if any (post-redirect) hop landed on a non-public address.
            if ($ok === false || $status >= 400 || filesize($tempFile) === 0
                || ($primaryIp !== '' && !$this->isPublicIp($primaryIp))) {
                unlink($tempFile);
                return null;
            }
            return $tempFile;
        }

        // No curl: disable redirects entirely (we cannot re-validate their target).
        $context = stream_context_create(array('http' => array('timeout' => 60, 'follow_location' => 0, 'max_redirects' => 0)));
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
        if (!$this->isSafeUrl($url)) {
            return null;
        }
        if (function_exists('curl_init')) {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
            curl_setopt($curl, CURLOPT_TIMEOUT, self::HTTP_TIMEOUT);
            $this->restrictToHttps($curl);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json'));
            curl_setopt($curl, CURLOPT_USERAGENT, 'OnePagerCMS/' . (defined('OPCMS_VERSION') ? OPCMS_VERSION : '1.x'));
            $body = curl_exec($curl);
            $status = (int)curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
            $primaryIp = (string)curl_getinfo($curl, CURLINFO_PRIMARY_IP);
            curl_close($curl);
            if ($primaryIp !== '' && !$this->isPublicIp($primaryIp)) {
                return null;
            }
            return ($body === false || $status >= 400) ? null : $body;
        }

        if (!ini_get('allow_url_fopen')) {
            return null;
        }
        $context = stream_context_create(array('http' => array(
            'timeout' => self::HTTP_TIMEOUT,
            'follow_location' => 0,
            'max_redirects' => 0,
            'header' => "Accept: application/json\r\nUser-Agent: OnePagerCMS/" . (defined('OPCMS_VERSION') ? OPCMS_VERSION : '1.x') . "\r\n",
        )));
        $body = @file_get_contents($url, false, $context);
        return ($body === false) ? null : $body;
    }

    /**
     * Restrict a curl handle to HTTPS for both the initial request and any
     * followed redirects, blocking downgrade/file/gopher SSRF vectors.
     */
    private function restrictToHttps($curl): void
    {
        if (defined('CURLPROTO_HTTPS')) {
            curl_setopt($curl, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
            curl_setopt($curl, CURLOPT_REDIR_PROTOCOLS, CURLPROTO_HTTPS);
        }
    }

    /**
     * True only for an HTTPS URL whose host resolves exclusively to public IP
     * addresses. Blocks plaintext HTTP (MITM) and requests to private/reserved
     * ranges such as cloud metadata (169.254.169.254) and localhost.
     */
    private function isSafeUrl($url): bool
    {
        $parts = parse_url((string)$url);
        if (!is_array($parts) || empty($parts['host']) || empty($parts['scheme'])) {
            return false;
        }
        if (strtolower($parts['scheme']) !== 'https') {
            return false;
        }
        $host = $parts['host'];
        $ips = array();
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            $ips[] = $host;
        } else {
            $records = @dns_get_record($host, DNS_A | DNS_AAAA);
            if (is_array($records)) {
                foreach ($records as $record) {
                    if (isset($record['ip'])) {
                        $ips[] = $record['ip'];
                    }
                    if (isset($record['ipv6'])) {
                        $ips[] = $record['ipv6'];
                    }
                }
            }
            if (count($ips) === 0) {
                $resolved = gethostbynamel($host);
                if (is_array($resolved)) {
                    $ips = $resolved;
                }
            }
        }
        if (count($ips) === 0) {
            return false;
        }
        foreach ($ips as $ip) {
            if (!$this->isPublicIp($ip)) {
                return false;
            }
        }
        return true;
    }

    private function isPublicIp($ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false;
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
