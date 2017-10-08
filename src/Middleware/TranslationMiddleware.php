<?php

namespace Jet\Middleware;

use Jet\Services\AppProvider\AppTranslationProvider;
use JetFire\Framework\App;
use JetFire\Framework\System\Request;
use JetFire\Framework\System\Response;
use JetFire\Routing\Route;

/**
 * Class TranslationMiddleware
 * @package Jet\Middleware
 */
class TranslationMiddleware
{

    /**
     * @var App
     */
    private $app;

    /**
     * TranslationMiddleware constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param Route $route
     * @return bool|Response
     */
    public function beforeHandle(Request $request, Response $response, Route $route)
    {
        if($this->setLocale($this->app, $route, $request) == false) {
            /** @var Response $response */
            $response->setStatusCode(404);
            return $response;
        }
        return true;
    }

    /**
     * @param App $app
     * @param Route $route
     * @param Request $request
     * @return bool
     */
    private function setLocale(App $app, Route $route, Request $request)
    {
        $app->data['_lang_code'] = '';
        $app->data['_locale'] = $app->data['_default_locale'];
        $params = $route->getTarget('params');

        if(isset($route->getDetail()['keys'][':_lang_code']) && isset($params['locale_domain'])){
            if(!isset($params['lang_codes'][$route->getDetail()['keys'][':_lang_code']])) return false;
            $app->data['_lang_code'] = $route->getDetail()['keys'][':_lang_code'];
            $app->data['_locale'] = $params['lang_codes'][$app->data['_lang_code']];
        }

        $request->setLocale($app->data['_locale']) ;
        return true;
    }

    /**
     * @param Route $route
     * @param Response $response
     * @param AppTranslationProvider $translator
     * @return Response
     */
    public function afterHandle(Route $route, Response $response, AppTranslationProvider $translator)
    {
        if ($route->hasTarget('data') && isset($route->getTarget('data')['message'])) {
            $params = $route->getTarget('params');
            $placeholder =  isset($route->getTarget('data')['placeholder']) ? $route->getTarget('data')['placeholder'] : [];
            if(isset($this->app->data['_locale']) && isset($params['lang_codes'][$this->app->data['_lang_code']])) {
                $content = $route->getTarget('data');
                $content['message'] = $translator->translate($content['message'], $placeholder, $params['locale_domain'], $this->app->data['_locale']);
                return $response->setContent(json_encode($content));
            }
        }
        return true;
    }

} 