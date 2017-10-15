<?php

namespace Jet\AdminBlock\Middleware;

use Jet\AdminBlock\Controllers\AuthController;
use Jet\Services\Admin;
use Jet\Services\Auth;
use JetFire\Framework\App;
use JetFire\Framework\Providers\ResponseProvider;
use JetFire\Framework\System\Request;
use JetFire\Http\Session;
use JetFire\Routing\ResponseInterface;
use JetFire\Routing\Route;

/**
 * Class AdminCSRFMiddleware
 * @package Jet\AdminBlock\Middleware
 */
class AdminCSRFMiddleware
{

    use Admin;
    
    /**
     * @var Session
     */
    private $session;

    /**
     * @param Request $request
     * @param ResponseProvider $responseProvider
     * @param App $app
     * @param Route $route
     * @param Auth $auth
     * @return bool|ResponseInterface
     */
    public function handle(Request $request, ResponseProvider $responseProvider, App $app, Route $route, Auth $auth)
    {
        $admin_url = $this->getAdminUrl($app);
        $this->session = $app->get('session')->getSession();

        if($request->wantsJson()) {
            $route->addTarget('dispatcher', $route->getTarget('dispatcher')[0]);
        }

        if ($request->method() != 'GET' && ($request->has('_token') || $request->headers->has('X-CSRF-TOKEN'))) {
            $token = '';
            if ($request->has('_token')) $token = $request->get('_token');
            elseif ($request->headers->has('X-CSRF-TOKEN')) $token = $request->headers->get('X-CSRF-TOKEN');
            if (!$this->hasXss(['token' => $token, 'time' => 7200])) {
                $auth->logout();
                return [
                    'call' => $app->data['app']['middleware']['after']['global_middleware'],
                    'response' => ($request->headers->has('X-CSRF-TOKEN'))
                        ? $responseProvider->getResponse()->answer(json_encode(['redirect' => $admin_url]), 302, 'application/json')
                        : $responseProvider->getRedirect()->url($admin_url)
                    ];
            } elseif ($auth->guest() && $route->getTarget('controller') != AuthController::class) {
                return [
                    'call' => $app->data['app']['middleware']['after']['global_middleware'],
                    'response' => ($request->headers->has('X-CSRF-TOKEN'))
                        ? $responseProvider->getResponse()->answer(json_encode(['redirect' => $admin_url]), 401, 'application/json')
                        : $responseProvider->getResponse()->setStatusCode(401)
                    ];
            }
        }
        return true;
    }

    /**
     * @param array $token
     * @return bool
     */
    private function hasXss($token = [])
    {
        if (isset($token['token'])) {
            if (!isset($token['time'])) $token['time'] = 600;
            return $this->isToken($token['token'], $token['time']);
        }
        return false;
    }

    /**
     * @param $token
     * @param $time
     * @return bool
     */
    private function isToken($token, $time)
    {
        if ($this->session->get('_token_') && $token != '') {
            if ($this->session->get('_token_')['key'] == $token) {
                if ($this->session->get('_token_')['time'] >= (time() - $time)) {
                    return true;
                }
            }
        }
        return false;
    }


}
