<?php

namespace Jet\Extensions\Twig;

use JetFire\Framework\App;
use Twig_Extension;
use Twig_Extension_GlobalsInterface;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

/**
 * Class DefaultExtension
 * @package Jet\Extensions\Twig
 */
class DefaultExtension extends Twig_Extension implements Twig_Extension_GlobalsInterface
{

    /**
     * @var App
     */
    private $app;

    /**
     * DefaultExtension constructor.
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
            '_data' => $this->app->data,
        );
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter('dateFr', function ($date) {
                $Jour = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
                $Mois = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
                $datefr = $Jour[$date->format("w")] . " " . $date->format("d") . " " . $Mois[$date->format("n")] . " " . $date->format("Y");
                echo $datefr;
            }),
        );
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('token', function ($name = '') {
                return generate_token($name);
            }),
            new Twig_SimpleFunction('path', function ($path = null, $params = []) {
                $view = $this->app->get('response')->getView();
                if(isset($this->app->data['_lang_code'])) {
                    $params = array_merge(['_lang_code' => $this->app->data['_lang_code']], $params);
                }
                $url = is_null($url = $view->path($path, $params))
                    ? $this->app->get('Jet\Services\Asset')->getBaseUrl($path)
                    : $url;
                return $url;
            }),
            new Twig_SimpleFunction('url', function ($path = '') {
                $server = $this->app->get('request')->getServer();
                return urlencode($server->has('REQUEST_SCHEME') ? $server->get('REQUEST_SCHEME') : 'http') . '://' . $server->get('HTTP_HOST') . (($server->has('SERVER_PORT') && $server->get('SERVER_PORT') !== '80') ? ':' . $server->get('SERVER_PORT') : '') . $server->get('REQUEST_URI') . $path;
            }),
            new Twig_SimpleFunction('asset', function ($value, $full_path = false) {
                return $this->app->get('Jet\Services\Asset')->getPublicPath($value, $full_path);
            }),
            new Twig_SimpleFunction('request', function () {
                return $this->app->get('request');
            }),
            new Twig_SimpleFunction('get', function ($value) {
                return $this->app->get('request')->getQuery()->get($value);
            }),
            new Twig_SimpleFunction('post', function ($value) {
                return $this->app->get('request')->getPost()->get($value);
            }),
            new Twig_SimpleFunction('session', function ($value) {
                return $this->app->get('session')->getSession()->get($value);
            }),
            new Twig_SimpleFunction('cookie', function ($value) {
                return $this->app->get('request')->getCookies()->get($value);
            }),
            new Twig_SimpleFunction('debug_bar_header', function ($enable = true) {
                return ($enable) ? $this->app->get('debug_toolbar')->getDebugBarRenderer()->renderHead() : '';
            }),
            new Twig_SimpleFunction('debug_bar_footer', function ($enable = true, $ajax = false) {
                if($enable) {
                    return ($ajax == true)
                        ? $this->app->get('debug_toolbar')->getDebugBarRenderer()->render(false)
                        : $this->app->get('debug_toolbar')->getDebugBarRenderer()->render();
                }
                return '';
            })
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'twig_default_extension';
    }

} 