<?php
if (!defined('OPCMS_ROOT')) {
    define('OPCMS_ROOT', dirname(__DIR__));
}

class ThemeEngine
{
    const DEFAULT_THEME = 'agency';

    private $activeTheme;

    private $manifest;

    private $optionValues;

    public function getActiveTheme(): string
    {
        if ($this->activeTheme === null) {
            $theme = '';
            try {
                if (is_file('../database/connect.php')) {
                    include '../database/connect.php';
                    if (isset($db)) {
                        $select = $db->prepare('SELECT value FROM settings WHERE setting = :setting;');
                        $select->bindValue(':setting', 'active-theme');
                        $select->execute();
                        $row = $select->fetch();
                        if ($row !== false && isset($row[0])) {
                            $theme = (string)$row[0];
                        }
                    }
                }
            } catch (Throwable $throwable) {
                $theme = '';
            }
            if (!preg_match('/^[a-z0-9][a-z0-9\-]{2,49}$/', $theme) || !is_dir(OPCMS_ROOT . '/themes/' . $theme . '/templates')) {
                $theme = self::DEFAULT_THEME;
            }
            $this->activeTheme = $theme;
        }
        return $this->activeTheme;
    }

    public function locate($template): ?string
    {
        if (!preg_match('/^[a-z0-9][a-z0-9\-]*$/', $template)) {
            return null;
        }
        $active = OPCMS_ROOT . '/themes/' . $this->getActiveTheme() . '/templates/' . $template . '.php';
        if (is_file($active)) {
            return $active;
        }
        $default = OPCMS_ROOT . '/themes/' . self::DEFAULT_THEME . '/templates/' . $template . '.php';
        return is_file($default) ? $default : null;
    }

    public function hasTemplate($template): bool
    {
        return $this->locate($template) !== null;
    }

    public function render($template, array $data = array()): void
    {
        $opcmsTemplateFile = $this->locate($template);
        if ($opcmsTemplateFile === null) {
            return;
        }
        $opcmsTheme = $this;
        $opcmsData = $data;
        extract($data, EXTR_SKIP);
        include $opcmsTemplateFile;
    }

    public function capture($template, array $data = array()): string
    {
        ob_start();
        $this->render($template, $data);
        return ob_get_clean();
    }

    public function assetUrl($path): string
    {
        return '../themes/' . $this->getActiveTheme() . '/assets/' . ltrim($path, '/');
    }

    public static function readManifest($slug): array
    {
        if (!preg_match('/^[a-z0-9][a-z0-9\-]{2,49}$/', (string)$slug)) {
            return array();
        }
        $file = OPCMS_ROOT . '/themes/' . $slug . '/theme.json';
        if (!is_file($file)) {
            return array();
        }
        $decoded = json_decode((string)file_get_contents($file), true);
        return is_array($decoded) ? $decoded : array();
    }

    /**
     * Normalize the optional "options" array of a theme manifest. Invalid
     * entries are skipped; every returned entry has key/type/label/default
     * (and choices for selects).
     */
    public static function declaredOptions(array $manifest): array
    {
        if (!isset($manifest['options']) || !is_array($manifest['options'])) {
            return array();
        }
        $options = array();
        foreach ($manifest['options'] as $entry) {
            if (!is_array($entry) || !isset($entry['key']) || !is_string($entry['key'])
                || !preg_match('/^[a-z0-9][a-z0-9\-]{0,49}$/', $entry['key'])) {
                continue;
            }
            $type = (isset($entry['type']) && is_string($entry['type'])) ? $entry['type'] : 'text';
            if (!in_array($type, array('color', 'text', 'select'), true)) {
                $type = 'text';
            }
            $option = array(
                'key' => $entry['key'],
                'type' => $type,
                'label' => (isset($entry['label']) && is_string($entry['label'])) ? $entry['label'] : $entry['key'],
                'default' => (isset($entry['default']) && is_scalar($entry['default'])) ? (string)$entry['default'] : '',
                'description' => (isset($entry['description']) && is_string($entry['description'])) ? $entry['description'] : '',
            );
            if ($type === 'select') {
                $choices = (isset($entry['choices']) && is_array($entry['choices'])) ? array_values(array_filter($entry['choices'], 'is_string')) : array();
                if (count($choices) === 0) {
                    continue;
                }
                $option['choices'] = $choices;
            }
            $options[] = $option;
        }
        return $options;
    }

    public function getManifest(): array
    {
        if ($this->manifest === null) {
            $this->manifest = self::readManifest($this->getActiveTheme());
        }
        return $this->manifest;
    }

    public function getDeclaredOptions(): array
    {
        return self::declaredOptions($this->getManifest());
    }

    /**
     * Saved value of a theme option (settings key theme-option:<slug>:<key>),
     * falling back to the default declared in the manifest.
     */
    public function getOption($key): string
    {
        if (!preg_match('/^[a-z0-9][a-z0-9\-]{0,49}$/', (string)$key)) {
            return '';
        }
        $values = $this->getOptionValues();
        if (isset($values[$key]) && $values[$key] !== '') {
            return $values[$key];
        }
        foreach ($this->getDeclaredOptions() as $option) {
            if ($option['key'] === $key) {
                return $option['default'];
            }
        }
        return '';
    }

    private function getOptionValues(): array
    {
        if ($this->optionValues === null) {
            $this->optionValues = array();
            $prefix = 'theme-option:' . $this->getActiveTheme() . ':';
            try {
                if (is_file('../database/connect.php')) {
                    include '../database/connect.php';
                    if (isset($db)) {
                        $select = $db->prepare('SELECT setting, value FROM settings WHERE setting LIKE :pattern;');
                        $select->bindValue(':pattern', $prefix . '%');
                        $select->execute();
                        foreach ($select->fetchAll() as $row) {
                            $this->optionValues[substr($row['setting'], strlen($prefix))] = (string)$row['value'];
                        }
                    }
                }
            } catch (Throwable $throwable) {
                $this->optionValues = array();
            }
        }
        return $this->optionValues;
    }
}
