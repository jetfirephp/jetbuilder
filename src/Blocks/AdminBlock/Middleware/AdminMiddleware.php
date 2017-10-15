<?php

namespace Jet\AdminBlock\Middleware;

use Jet\AdminBlock\Controllers\AuthController;
use Jet\Services\Admin;
use Jet\Services\Auth;
use JetFire\Framework\App;
use JetFire\Framework\System\Redirect;
use JetFire\Framework\System\Request;
use JetFire\Routing\ResponseInterface;
use JetFire\Routing\Route;

/**
 * Class Admin
 * @package Jet\AdminBlock\Middleware
 */
class AdminMiddleware
{

    use Admin;

    /**
     * @param Request $request
     * @param Route $route
     * @param Redirect $redirect
     * @param App $app
     * @param Auth $auth
     * @return bool|ResponseInterface
     */
    public function beforeHandle(Request $request, Route $route, Redirect $redirect, App $app, Auth $auth)
    {
        if ($auth->guest() && $route->getTarget('controller') != AuthController::class) {
            return $redirect->url($this->getAdminUrl($app, '/auth'));
        }

        if ($auth->check() && $route->getTarget('controller') == AuthController::class && !in_array($route->getTarget('action'), ['logout', 'getAuth'])) {
            return $redirect->url($this->getAdminUrl($app, '/dashboard'));
        }
        
        if($request->wantsJson()){
            $_POST = $request->json()->all();
            $request->request->add($request->json()->all());
        }
        
        return true;
    }


    /**
     * @param Request $request
     * @param Redirect $redirect
     * @param Route $route
     * @param App $app
     * @return bool
     */
    public function betweenHandle(Request $request, Redirect $redirect, Route $route, App $app)
    {
        if($route->hasTarget('data')) {

            if($request->wantsJson()){
                return true;
            }

            if(isset($route->getTarget('data')['redirect'])){
                $params = isset($route->getTarget('data')['redirect_with']) ? $route->getTarget('data')['redirect_with'] : [];
                $code = isset($route->getTarget('data')['redirect_code']) ? $route->getTarget('data')['redirect_code'] : 302;
                return $redirect->to($route->getTarget('data')['redirect'], $params, $code);
            }

            $admin_url = $this->getAdminUrl($app);
            $app_name = (isset($app->data['setting']['name'])) ? $app->data['setting']['name'] : 'JetBuilder';
            $route->addTarget('data', array_merge([
                'app_name' => $app_name,
                'admin_url' => $admin_url,
                'locale' => $app->data['_locale'],
                'libs' => $app->data['admin']['libs']
            ], $route->getTarget('data')));
            
        }
        return true;
    }
}
