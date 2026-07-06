<?php
if (!defined('OPCMS_ROOT')) {
    define('OPCMS_ROOT', dirname(__DIR__));
}

class ThemeEngine
{
    const DEFAULT_THEME = 'agency';

    private $activeTheme;

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
}
