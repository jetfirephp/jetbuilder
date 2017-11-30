<?php

namespace Jet\Services;

/**
 * Class Minify
 * @package Jet\Services
 */
class Minify
{

    /**
     * @var string
     */
    public $script_path;
    /**
     * @var string
     */
    public $cache_path;
    /**
     * @var Minify|null
     */
    private static $instance = null;


    /**
     * Minify constructor.
     * @param string $script_path
     * @param string $cache_path
     */
    public function __construct($script_path = '/min/static/', $cache_path = '/storage/cache/')
    {
        self::$instance = $this;
        $this->script_path = $script_path;
        $this->cache_path = $cache_path;
    }

    /**
     * @return Minify|null
     */
    public function getInstance()
    {
        return is_null(self::$instance) ? new self : self::$instance;
    }

    /**
     * Build a URI for the static cache
     *
     * @param string $query E.g. "b=scripts&f=1.js,2.js"
     * @param string $type "css" or "js"
     * @return string
     */
    public function build_uri($query, $type)
    {
        $static_uri = rtrim($this->script_path, '/');
        $query = ltrim($query, '?');

        $ext = '.' . $type;
        if (substr($query, -strlen($ext)) !== $ext) {
            $query .= '&z=' . $ext;
        }

        $cache_time = $this->get_cache_time();

        return (is_null($cache_time))
            ? $static_uri . '/' . $query
            : is_file(ROOT . $cache_time['path'] . $cache_time['dir'] . '/' . $query)
                ? $cache_time['path'] . $cache_time['dir'] . '/' . $query
                : $static_uri . '/' . $cache_time['dir'] . '/' . $query;
    }

    /**
     * Get the name of the current cache directory within static/. E.g. "1467089473"
     *
     * @param bool $auto_create Automatically create the directory if missing?
     * @return null|string null if missing or can't create
     */
    public function get_cache_time($auto_create = true)
    {
        foreach (scandir(ROOT . $this->cache_path) as $entry) {
            if (ctype_digit($entry)) {
                return ['path' => $this->cache_path, 'dir' => $entry];
                break;
            }
        }

        if (!$auto_create) {
            return null;
        }

        $time = (string)time();
        if (!mkdir(ROOT . $this->cache_path . $time)) {
            return null;
        }

        return ['path' => $this->script_path, 'dir' => $time];
    }

    /**
     *
     */
    public function flush_cache()
    {
        $time = $this->get_cache_time(false);
        if ($time) {
            $this->remove_tree(ROOT . $this->cache_path . $time);
        }
    }

    /**
     * @param $dir
     * @return bool
     */
    public function remove_tree($dir)
    {
        $files = array_diff(scandir($dir), array('.', '..'));

        foreach ($files as $file) {
            is_dir("$dir/$file") ? $this->remove_tree("$dir/$file") : unlink("$dir/$file");
        }

        return rmdir($dir);
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([self::getInstance(), $name], $arguments);
    }

}