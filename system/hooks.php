<?php

class Hooks
{
    private static $actions = array();
    private static $filters = array();

    public static function addAction($hook, $callback, $priority = 10): void
    {
        self::$actions[$hook][$priority][] = $callback;
        ksort(self::$actions[$hook]);
    }

    public static function doAction($hook, ...$args): void
    {
        if (!isset(self::$actions[$hook])) {
            return;
        }
        foreach (self::$actions[$hook] as $callbacks) {
            foreach ($callbacks as $callback) {
                call_user_func_array($callback, $args);
            }
        }
    }

    public static function addFilter($hook, $callback, $priority = 10): void
    {
        self::$filters[$hook][$priority][] = $callback;
        ksort(self::$filters[$hook]);
    }

    public static function applyFilters($hook, $value, ...$args)
    {
        if (!isset(self::$filters[$hook])) {
            return $value;
        }
        foreach (self::$filters[$hook] as $callbacks) {
            foreach ($callbacks as $callback) {
                $value = call_user_func_array($callback, array_merge(array($value), $args));
            }
        }
        return $value;
    }

    public static function removeAction($hook, $callback): void
    {
        self::removeCallback(self::$actions, $hook, $callback);
    }

    public static function removeFilter($hook, $callback): void
    {
        self::removeCallback(self::$filters, $hook, $callback);
    }

    private static function removeCallback(&$registry, $hook, $callback): void
    {
        if (!isset($registry[$hook])) {
            return;
        }
        foreach ($registry[$hook] as $priority => $callbacks) {
            foreach ($callbacks as $index => $registered) {
                if ($registered === $callback) {
                    unset($registry[$hook][$priority][$index]);
                }
            }
        }
    }
}

if (!function_exists('add_action')) {
    function add_action($hook, $callback, $priority = 10)
    {
        Hooks::addAction($hook, $callback, $priority);
    }

    function do_action($hook, ...$args)
    {
        Hooks::doAction($hook, ...$args);
    }

    function add_filter($hook, $callback, $priority = 10)
    {
        Hooks::addFilter($hook, $callback, $priority);
    }

    function apply_filters($hook, $value, ...$args)
    {
        return Hooks::applyFilters($hook, $value, ...$args);
    }

    function remove_action($hook, $callback)
    {
        Hooks::removeAction($hook, $callback);
    }

    function remove_filter($hook, $callback)
    {
        Hooks::removeFilter($hook, $callback);
    }
}
