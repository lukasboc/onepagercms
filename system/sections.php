<?php

/**
 * Generic section value object for plugin-provided section types.
 * Core code only relies on getType/getSuperid/getPosition/getTitle;
 * everything type-specific lives in the data bag.
 */
class PluginSection
{
    private $type;
    private $superid;
    private $position;
    private $title;
    private $data;

    public function __construct($type, $superid, $position, $title, array $data = array())
    {
        $this->type = $type;
        $this->superid = $superid;
        $this->position = $position;
        $this->title = $title;
        $this->data = $data;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getSuperid()
    {
        return $this->superid;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function get($key, $default = null)
    {
        return array_key_exists($key, $this->data) ? $this->data[$key] : $default;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getBackground()
    {
        return $this->get('background', '');
    }
}

if (!function_exists('opcms_register_section_type')) {
    /**
     * Registers a custom section type. Returns false (and does nothing) when the
     * type name is invalid, collides with a built-in type or is already taken.
     *
     * $config = array(
     *   'label'    => string   required — shown in the New Section dropdown
     *   'build'    => callable required — sections-registry row (id,type,specialid,position) → section object|null
     *   'render'   => callable optional — ($section, $bgcolor, $index) → frontend HTML;
     *                 a theme template 'section-<type>' always wins over this callback
     *   'form_url' => string   required — New/Edit/Delete redirect target; core appends action= and id=
     * )
     */
    function opcms_register_section_type($type, array $config): bool
    {
        if (!is_string($type) || !preg_match('/^[a-z0-9][a-z0-9\-]{1,49}$/', $type)) {
            return false;
        }
        if (in_array($type, array('standard', 'icons', 'contact'), true)) {
            return false;
        }
        if (empty($config['label']) || !is_string($config['label'])) {
            return false;
        }
        if (!isset($config['build']) || !is_callable($config['build'])) {
            return false;
        }
        if (empty($config['form_url']) || !is_string($config['form_url'])) {
            return false;
        }
        $registry = &opcms_section_type_registry();
        if (isset($registry[$type])) {
            return false;
        }
        $registry[$type] = $config;
        return true;
    }

    function opcms_get_section_types(): array
    {
        return opcms_section_type_registry();
    }

    function opcms_get_section_type($type): ?array
    {
        $registry = opcms_section_type_registry();
        return (is_string($type) && isset($registry[$type])) ? $registry[$type] : null;
    }

    function &opcms_section_type_registry(): array
    {
        static $registry = array();
        return $registry;
    }
}
