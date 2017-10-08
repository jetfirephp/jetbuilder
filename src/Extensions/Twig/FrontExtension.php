<?php
namespace Jet\Extensions\Twig;

use Jet\Models\Media;
use JetFire\Framework\App;
use Twig_Extension;
use Twig_Extension_GlobalsInterface;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

/**
 * Class FrontExtension
 * @package Jet\Extensions\Twig
 */
class FrontExtension extends Twig_Extension implements Twig_Extension_GlobalsInterface
{

    /**
     * @var App
     */
    private $app;


    /**
     * FrontExtension constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * @return array
     */
    public function getGlobals()
    {
        return array(
            '_seo' => new \ArrayObject()
        );
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter('to_attributes', function ($attributes) {
                return join(' ', array_map(function($key) use ($attributes)
                {
                    if(is_bool($attributes[$key]))
                    {
                        return $attributes[$key]?$key:'';
                    }
                    return $key.'="'.$attributes[$key].'"';
                }, array_keys($attributes)));
            }),
        );
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('data_render', function ($table, $keys) {
                return $this->app->get('Jet\FrontBlock\Controllers\FrontPhpController')->renderData($table, $keys);
            }),
            new Twig_SimpleFunction('page_render', function ($page = '', $type = 'all') {
                return $this->app->get('Jet\FrontBlock\Controllers\FrontPhpController')->renderPage($page, $type);
            }),
            new Twig_SimpleFunction('content_render', function ($name, $options = []) {
                $options = is_array($options) ? $options : [];
                return $this->app->get('Jet\FrontBlock\Controllers\FrontPhpController')->renderContent($name, $options);
            }),
            new Twig_SimpleFunction('field_render', function ($name, $key = 'value') {
                return $this->app->get('Jet\FrontBlock\Controllers\FrontPhpController')->renderCustomField($name, $key);
            }),

            new Twig_SimpleFunction('get_table', function ($table, $id, $keys) {
                if(empty($keys))
                    return forward_static_call_array([$table, 'findOneById'], [$id]);
                return $this->app->get('Jet\FrontBlock\Controllers\FrontPhpController')->getTable($table, $id, $keys);
            }),
            new Twig_SimpleFunction('get_media', function ($id) {
                return Media::findOneById($id);
            }),
            new Twig_SimpleFunction('theme_asset', function ($value, $theme = null) {
                return $this->app->get('Jet\FrontBlock\Controllers\FrontPhpController')->callContent('Jet\Services\Asset', 'getThemePath', null, ['value' => $value, 'theme' => $theme] );
            }),
            new Twig_SimpleFunction('img', function ($value) {
                return rtrim(WEBROOT, '/') . $value;
            }),

            new Twig_SimpleFunction('link', function ($path = null, $params = [], $subdomain = '') {
                return is_null($url = $this->app->get('Jet\FrontBlock\Controllers\FrontPhpController')->link($path, $params, $subdomain))
                    ? (isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http') . '://' . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME']) . str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) . '/' . trim($path, '/')
                    : $url;
            }),
            new Twig_SimpleFunction('full_url', function ($url) {
                return $this->app->get('Jet\FrontBlock\Controllers\FrontPhpController')->fullUrl($url);
            })
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'twig_front_extension';
    }
}