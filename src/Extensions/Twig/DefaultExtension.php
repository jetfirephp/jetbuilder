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
            new Twig_SimpleFunction('path', function ($path = null, $params = [], $subdomain = '') {
                $view = $this->app->get('response')->getView();
                $full_url = is_null($url = $view->path($path, $params, $subdomain))
                    ? (isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http') . '://' . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME']) . str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']) . '/' . trim($path, '/')
                    : $url;
                if(strpos($full_url, ':_locale') !== false && isset($this->app->data['_locale'])) {
                    $full_url = str_replace(':_locale', $this->app->data['_locale'], $full_url);
                }
                return $full_url;
            }),
            new Twig_SimpleFunction('asset', function ($value, $full_path = false) {
                return $this->app->get('JetFire\Framework\System\Controller')->callMethod('Jet\Services\Asset', 'getPublicPath', [$value, $full_path]);
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
            new Twig_SimpleFunction('url', function () {
                $server = $this->app->get('request')->getServer();
                return urlencode($server->has('REQUEST_SCHEME') ? $server->get('REQUEST_SCHEME') : 'http') . '://' . $server->get('HTTP_HOST') . (($server->has('SERVER_PORT') && $server->get('SERVER_PORT') !== '80') ? ':'.$server->get('SERVER_PORT') : '') . $server->get('REQUEST_URI');
            }),
            new Twig_SimpleFunction('debug_bar_header', function () {
                return $this->app->get('debug_toolbar')->getDebugBarRenderer()->renderHead();
            }),
            new Twig_SimpleFunction('debug_bar_footer', function ($ajax = false) {
                if ($ajax == true)
                    return $this->app->get('debug_toolbar')->getDebugBarRenderer()->render(false);
                return $this->app->get('debug_toolbar')->getDebugBarRenderer()->render();
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