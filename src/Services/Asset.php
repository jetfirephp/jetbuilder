<?php

namespace Jet\Services;

use Jet\Models\Website;
use JetFire\Framework\App;


/**
 * Class Asset
 * @package Jet\Services
 */
class Asset
{

    /**
     * @var App
     */
    private $app;
    /**
     * @var Auth
     */
    private static $instance;

    /**
     * Auth constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        self::$instance = $this;
    }

    /**
     * @return Auth
     */
    public static function getInstance()
    {
        return self::$instance;
    }

    /**
     * @param $value
     * @param $full_path
     * @return string
     */
    public function getPublicPath($value, $full_path){
        if(substr($value, 0, 4) === 'http' && strpos($value, '://') !== false) return $value;
        $blocks = $this->app->data['app']['blocks'];
        $path = ($full_path) ? $this->app->data['setting']['domain'] . WEBROOT : WEBROOT;
        foreach ($blocks as $block) {
            if (substr($value, -3) == '.js' && is_file(ROOT . '/' . $block['path'] . 'Resources/public/js/' . $value)) return $path . $block['path'] . 'Resources/public/js/' . $value;
            if (substr($value, -4) == '.css' && is_file(ROOT . '/' . $block['path'] . 'Resources/public/css/' . $value)) return $path . $block['path'] . 'Resources/public/css/' . $value;
            if ((substr($value, -4) == '.png' || substr($value, -4) == '.jpg' || substr($value, -5) == '.jpeg' || substr($value, -4) == '.gif') && is_file(ROOT . '/' . $block['path'] . 'Resources/public/img/' . $value)) return $path . $block['path'] . 'Resources/public/img/' . $value;
            if (is_file(ROOT . '/' . $block['path'] . 'Resources/public/' . $value)) return $path . $block['path'] . 'Resources/public/' . $value;
        }
        if (substr($value, -3) == '.js' && is_file(ROOT . '/public/js/' . $value)) return $path . 'public/js/' . $value;
        if (substr($value, -4) == '.css' && is_file(ROOT . '/public/css/' . $value)) return $path . 'public/css/' . $value;
        $ext = explode('.', $value);
        if (in_array(end($ext), ['png', 'jpg', 'jpeg', 'gif', 'ico', 'svg']) && is_file(ROOT . '/public/img/' . $value)) return $path . 'public/img/' . $value;
        return is_file(ROOT . '/public/' . $value) ? $path . 'public/' . $value : rtrim($path, '/') . $value;
    }

    /**
     * @param Website $website
     * @param $value
     * @param null $theme
     * @return string
     */
    public function getThemePath(Website $website, $value, $theme = null){
        $theme = is_null($theme) ? $this->getRecursiveThemeName($website) : $theme;
        if(is_dir(ROOT. '/src/Themes/' . $theme . '/Resources/public/')) {
            $dir = ROOT . '/src/Themes/' . $theme . '/Resources/public/';
            $web_dir = WEBROOT . 'src/Themes/' . $theme . '/Resources/public/';
            if (substr($value, -3) == '.js' && is_file($dir . 'js/' . $value)) return $web_dir . 'js/' . $value;
            if (substr($value, -4) == '.css' && is_file($dir . 'css/' . $value)) return $web_dir . 'css/' . $value;
            $ext = explode('.', $value);
            if (in_array(end($ext), ['png', 'jpg', 'jpeg', 'gif', 'ico', 'svg']) && is_file($dir . 'img/' . $value)) return $web_dir . 'img/' . $value;
            return is_file($dir . $value) ? $web_dir . $value : rtrim($web_dir, '/') . $value;
        }
        return $value;
    }

    /**
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        $params = preg_split('/(?=[A-Z])/', $method, -1, PREG_SPLIT_NO_EMPTY);
        if (!isset($arguments[0])) $arguments[0] = null;
        return $this->$params[0]($arguments[0], strtolower($params[1]));
    }

    /**
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        $params = preg_split('/(?=[A-Z])/', $method, -1, PREG_SPLIT_NO_EMPTY);
        if (!isset($arguments[0])) $arguments[0] = null;
        return self::getInstance()->$params[0]($arguments[0], strtolower($params[1]));
    }

    /**
     * @param Website $website
     * @return bool
     */
    private function getRecursiveThemeName(Website $website)
    {
        $theme = $website->getTheme();
        $theme_website = $theme->getWebsite();

        return ($theme_website->getId() != $website->getId())
            ? $this->getRecursiveThemeName($theme_website)
            : $theme->getName();
    }


}