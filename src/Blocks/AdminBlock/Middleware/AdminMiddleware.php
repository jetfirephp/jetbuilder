<?php

namespace Jet\AdminBlock\Middleware;

use Jet\AdminBlock\Controllers\AuthController;
use Jet\AdminBlock\Services\Admin;
use Jet\AdminBlock\Services\Auth;
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
        // redirect to login page if user is not connected and try to call an admin url
        /*if ($auth->guest() && $route->getTarget('controller') != AuthController::class) {
            return $redirect->url($this->getAdminUrl($app, '/auth'));
        }*/
        // redirect to admin dashboard if user is connected and try to call the login page
        /*if ($auth->check() && $route->getTarget('controller') == AuthController::class && !in_array($route->getTarget('action'), ['logout', 'getAuth'])) {
            return $redirect->url($this->getAdminUrl($app, '/dashboard'));
        }*/
        // convert json data to post data
        if ($request->wantsJson()) {
            $_POST = $request->json()->all();
            $request->request->add($request->json()->all());
        }

        // remove trailing slash in route keys
        $route_keys = $route->getKeys();
        if(isset($route_keys[':_website'])){
            $route_keys[':_website'] = trim($route_keys[':_website'], '/');
            $route->addDetail('keys', $route_keys);
        }

        return true;
    }


    /**
     * @param Request $request
     * @param Redirect $redirect
     * @param Route $route
     * @param App $app
     * @param Auth $auth
     * @return \Symfony\Component\HttpFoundation\Response|bool
     */
    public function betweenHandle(Request $request, Redirect $redirect, Route $route, App $app, Auth $auth)
    {
        // if we return an array from controllers
        if ($route->hasTarget('data')) {
            // if we request a json response, we don't add additional data
            if ($request->wantsJson()) {
                return true;
            }
            // if there is a redirect argument in the array we redirect to the target
            if (isset($route->getTarget('data')['redirect'])) {
                $params = isset($route->getTarget('data')['redirect_with']) ? $route->getTarget('data')['redirect_with'] : [];
                $code = isset($route->getTarget('data')['redirect_code']) ? $route->getTarget('data')['redirect_code'] : 302;
                return $redirect->to($route->getTarget('data')['redirect'], $params, $code);
            }
            // we add additional data for the admin template
            $admin_url = $this->getAdminUrl($app);
            $app_name = (isset($app->data['setting']['name'])) ? $app->data['setting']['name'] : 'JetBuilder';

            $data = [
                'app_name' => $app_name,
                'admin_url' => $admin_url,
                'locale' => $app->data['_locale'],
                'libs' => $app->data['admin']['libs']
            ];
            $data = (/*$auth->check() && */$route->getTarget('controller') != AuthController::class && !in_array($route->getTarget('action'), ['logout', 'getAuth'])) ? array_merge($this->getAdminData($app, $auth), $data) : $data;

            $route->addTarget('data', array_merge($data, $route->getTarget('data')));

        }
        return true;
    }

    /**
     * @param App $app
     * @param Auth $auth
     * @return array
     */
    private function getAdminData(App $app, Auth $auth)
    {
        $data = [];

        $env = (isset($app->data['setting']['environment'])) ? $app->data['setting']['environment'] : 'prod';
        $custom_field_type = (isset($app->data['app']['settings']['custom_field_type'])) ? $app->data['app']['settings']['custom_field_type'] : [];

        $data['env'] = $env;
        $data['auth'] = $auth->get();
        $data['public_path'] = rtrim(WEBROOT, '/');
        $data['field_types'] = $custom_field_type;
        $data['hook'] = $app->data['admin']['hook'];

        return $data;
    }
}
