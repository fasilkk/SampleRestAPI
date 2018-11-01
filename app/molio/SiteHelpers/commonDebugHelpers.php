<?php

/**
 * Print out debug informations.
 *
 * @param mixed $value
 * @param bool  $stop
 *
 * @return string echoed
 */
function D($value, $stop = false)
{
    echo '<pre>'.print_r($value, true).'</pre>';

    if ($stop) {
        die();
    }
}

/**
 * Print out debug informations for JavaScript.
 *
 * @param mixed $value
 *
 * @return string
 */
function DJ($value)
{
    return '<pre>'.print_r($value, true).'</pre>';

    die();
}

/**
 * Print out Carbon format date time.
 *
 * @param object $attr
 * @param string $format
 */
function DT($attr, $format)
{
    return $attr->formatLocalized($format);
    // return D($attr);
}

/**
 * Get config key.
 *
 * @param string $config
 * @param string $key
 *
 * @return string
 */
function CONFIG($config, $key)
{
    if ($key == '') {
        return [];
    }

    $conf = Config::get($config);

    return $conf[$key];
}

/*
 * SYSTEM
 */

if (!function_exists('env')) {
    /**
     * Get environment.
     *
     * @param string $env
     *
     * @return string
     */
    function env($env)
    {
        return app()->env == $env;
    }
}

if (!function_exists('public_path')) {
    /**
     * Get public path.
     *
     * @return string
     */
    function public_path($path = '')
    {
        return app()->make('path.public').($path ? '/'.$path : $path);
    }
}

/*
 * PATHS
 */

if (!function_exists('config_path')) {
    /**
     * Get the path to the cms/config folder.
     *
     * @param string $path
     *
     * @return string
     */
    function config_path($file = '')
    {
        if (WB()) {
            return __DIR__.'/config/'.$file;
        } else {
            return app_path('config/packages/ewizycms/cms/'.$file);
        }
    }
}

if (!function_exists('themes_path')) {
    /**
     * Get the path to the storage folder.
     *
     * @param string $path
     *
     * @return string
     */
    function themes_path($path = '')
    {
        return app('themes.path').($path ? '/'.$path : $path);
    }
}

if (!function_exists('theme_settings')) {
    /**
     * Get the path to the storage folder.
     *
     * @param string $path
     *
     * @return string
     */
    function theme_settings($path = '')
    {
        require $path.'/theme.php';

        return $THEME_SETTINGS;
    }
}

/*
 * TOOLS
 */

if (!function_exists('array_min_key')) {
    /**
     * Reduce an array with minimal key.
     *
     * @param array $array
     * @param mixed $min_key
     *
     * @return array
     */
    function array_min_key($array, $min_key)
    {
        $min_val = $array[$min_key];

        foreach ($array as $key => $value) {
            if ($value >= $min_val) {
                $tmp_array[$key] = $value;
            }
        }

        return $tmp_array;
    }
}

if (!function_exists('active')) {
    /**
     * Print out class=active if true.
     *
     * @param string $var
     * @param string $fix
     *
     * @return bool
     */
    function active($var, $fix)
    {
        return Tool::isActive($var, $fix);
    }
}

if (!function_exists('checked')) {
    /**
     * Print out checked=checked if true.
     *
     * @param string $var
     * @param string $fix
     *
     * @return bool
     */
    function checked($var, $fix)
    {
        return Tool::isChecked($var, $fix);
    }
}

if (!function_exists('selected')) {
    /**
     * Print out selected=selected if true.
     *
     * @param string $var
     * @param string $fix
     *
     * @return bool
     */
    function selected($var, $fix)
    {
        return Tool::isSelected($var, $fix);
    }
}

if (!function_exists('get_json')) {
    /**
     * Get json input.
     *
     * @param string $key
     * @param bool   $array
     *
     * @return array
     */
    function get_json($key, $array = true)
    {
        return Tool::getJson($key, $array);
    }
}

if (!function_exists('is_empty')) {
    /**
     * Check object is empty.
     *
     * @param string $var
     *
     * @return bool
     */
    function is_empty($var)
    {
        return (count($var) === 0) ? true : false;
    }
}

if (!function_exists('link_to_cms')) {
    /**
     * Link to cms route.
     *
     * @param string $var
     * @param string $fix
     *
     * @return bool
     */
    function link_to_cms($link, $title, $attributes = [], $secure = null)
    {
        return link_to('cms/'.$link, $title, $attributes, $secure);
    }
}

if (!function_exists('selected')) {
    /**
     * Print out selected=selected if true.
     *
     * @param string $var
     * @param string $fix
     *
     * @return bool
     */
    function selected($var, $fix)
    {
        return Tool::isSelected($var, $fix);
    }
}

if (!function_exists('system_role_class')) {
    /**
     * Print class if system role.
     *
     * @param int    $level
     * @param string $class_yes
     * @param string $class_not
     *
     * @return string
     */
    function system_role_class($level, $class_yes, $class_not)
    {
        return (LEVEL > $level) ? $class_yes : $class_not;
    }
}
