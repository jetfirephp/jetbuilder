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
        401 => 'Response/400.html.twig',
        404 => 'Response/400.html.twig',
        405 => 'Response/400.html.twig',
        500 => 'Response/500.html.twig',
        503 => 'Response/500.html.twig',
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
            $response->setStatusCode(404);
            return [
                'call' => $app->data['app']['middleware']['after']['global_middleware'],
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
     * @param Request $request
     * @param Response $response
     * @param Route $route
     * @param App $app
     * @param View $view
     * @return bool
     */
    public function afterHandle(Request $request, Response $response, Route $route, App $app, View $view)
    {
        if($route->hasTarget('data') && empty($route->getTarget('template')) && !$request->headers->has('X-CSRF-TOKEN')){
            $response->setStatusCode(405);
        }
        if (isset($this->response_code_templates[$response->getStatusCode()])) {
            $view->setPath(ROOT . '/src/Blocks/PublicBlock/Views');
            $view->addData('response_code', $response->getStatusCode());
            $view->setTemplate($this->response_code_templates[$response->getStatusCode()]);
            $view->setExtension($app->data['template_extension']);
            $response->setContent($app->get('template')->getTemplate()->render($view));
            return $response;
        }
        return true;
    }

} 