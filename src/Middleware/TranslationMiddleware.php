<?php

namespace Jet\Middleware;

use Jet\Services\AppProvider\AppTranslationProvider;
use JetFire\Framework\App;
use JetFire\Framework\System\JsonResponse;
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
        if ($this->setLocale($this->app, $route, $request) == false) {
            return [
                'call' => $this->app->data['app']['middleware']['after']['global_middleware'],
                'response' => $response->setStatusCode(404)
            ];
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

        if (isset($route->getDetail()['keys'][':_lang_code']) && isset($params['locale_domain'])) {
            if (!isset($params['lang_codes'][$route->getDetail()['keys'][':_lang_code']])) return false;
            $app->data['_lang_code'] = $route->getDetail()['keys'][':_lang_code'];
            $app->data['_locale'] = $params['lang_codes'][$app->data['_lang_code']];
        }

        $request->setLocale($app->data['_locale']);
        return true;
    }

    /**
     * @param Route $route
     * @param AppTranslationProvider $translator
     * @return Response
     */
    public function afterHandle(Route $route, AppTranslationProvider $translator)
    {
        return is_array($data = $this->translate($route, $translator))
            ? new JsonResponse($data)
            : true;
    }

    /**
     * @param Route $route
     * @param AppTranslationProvider $translator
     * @return Response
     */
    public function betweenHandle(Route $route, AppTranslationProvider $translator)
    {
        if(is_array($data = $this->translate($route, $translator))){
            $route->addTarget('data', $data);
        } 
        return true;
    }

    /**
     * @param Route $route
     * @param AppTranslationProvider $translator
     * @return Response
     */
    private function translate(Route $route, AppTranslationProvider $translator)
    {
        if($route->hasTarget('data') && isset($route->getTarget('data')['message'])) {
            $params = $route->getTarget('params');
            $placeholder = isset($route->getTarget('data')['placeholder']) ? $route->getTarget('data')['placeholder'] : [];
            if (isset($this->app->data['_locale']) && isset($params['lang_codes'][$this->app->data['_lang_code']])) {
                $data = $route->getTarget('data');
                $data['message'] = (is_array($data['message']))
                    ? $this->translateMessage($translator, $data['message'], $placeholder, $params)
                    : $translator->translate($data['message'], $placeholder, $params['locale_domain'], $this->app->data['_locale']);
                return $data;
            }
        }
        return true;
    }


    /**
     * @param AppTranslationProvider $translator
     * @param array $messages
     * @param array $placeholder
     * @param array $params
     * @return array
     */
    private function translateMessage(AppTranslationProvider $translator, $messages = [], $placeholder = [], $params = [])
    {
        $new_messages = [];
        foreach ($messages as $key => $message) {
            $new_messages[$key] = (is_array($message))
                ? $this->translateMessage($translator, $message, $placeholder, $params)
                : $translator->translate($message, $placeholder, $params['locale_domain'], $this->app->data['_locale']);
        }
        return $new_messages;
    }
} 