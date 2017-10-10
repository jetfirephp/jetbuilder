<?php

namespace Jet\Middleware;

use JetFire\Framework\App;
use JetFire\Framework\System\Request;
use JetFire\Framework\System\Response;
use JetFire\Framework\System\View;
use JetFire\Routing\Route;

/**
 * Class SystemMiddleware
 * @package Jet\Middleware
 */
class SystemMiddleware
{

    private $response_code_templates = [
        404 => 'Response/404.html.twig',
        405 => 'Response/405.html.twig',
        500 => 'Response/500.html.twig',
        503 => 'Response/503.html.twig',
    ];

    /**
     * @param Request $request
     * @param Response $response
     * @param App $app
     * @param Route $route
     * @return bool|array
     */
    public function beforeHandle(Request $request, Response $response, App $app, Route $route)
    {
        $domain = ($request->getServer()->has('REQUEST_SCHEME') ? $request->getServer()->get('REQUEST_SCHEME') : 'http') . '://' . $request->getServer()->get('SERVER_NAME');
        if($this->canAccessDomain($domain, $app, $route) == false){
            return [
                'call' => [
                    'Jet\Middleware\TranslationMiddleware@afterHandle',
                    'Jet\Middleware\SystemMiddleware@afterHandle'
                ],
                'response' => $response->setStatusCode(404)
            ];
        }
        $this->bootBlock($app, $route);
        return true;
    }

    /**
     * @param $app
     * @param $route
     */
    private function bootBlock(App $app, Route $route)
    {
        foreach ($app->data['app']['blocks'] as $block) {
            if (strpos($route->getBlock(), $block['path']) !== false) {
                if (is_file($path = rtrim($block['path'], '/') . '/Config/boot.php')) {
                    $app->load(include($path));
                }
            }
        }
    }

    /**
     * @param $domain
     * @param App $app
     * @param Route $route
     * @return bool
     */
    private function canAccessDomain($domain, App $app, Route $route)
    {
        if (isset($route->getTarget('params')['domain_key']) && $app->data['setting']['environment'] != 'dev') {
            $domain_key = $route->getTarget('params')['domain_key'];
            if (isset($app->data['setting'][$domain_key]) && rtrim($app->data['setting'][$domain_key], '/') !== rtrim($domain, '/')) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param Response $response
     * @param App $app
     * @param View $view
     * @return bool
     */
    public function afterHandle(Response $response, App $app, View $view)
    {
        if (isset($this->response_code_templates[$response->getStatusCode()])) {
            $view->setPath(ROOT . '/src/Blocks/PublicBlock/Views');
            $view->setExtension($app->data['template_extension']);
            $view->setTemplate($this->response_code_templates[$response->getStatusCode()]);
            $response->setContent($app->get('template')->getTemplate()->render($view));
            return $response;
        }
        return true;
    }

} 