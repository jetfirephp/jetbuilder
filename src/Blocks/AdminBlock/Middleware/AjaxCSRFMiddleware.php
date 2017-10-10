<?php

namespace Jet\AdminBlock\Middleware;

use Jet\AdminBlock\Controllers\AuthController;
use Jet\Middleware\MainMiddleware;
use Jet\Services\Auth;
use JetFire\Framework\App;
use JetFire\Framework\Providers\ResponseProvider;
use JetFire\Framework\System\Request;
use JetFire\Http\Session;
use JetFire\Routing\ResponseInterface;
use JetFire\Routing\Route;

/**
 * Class AjaxCSRFMiddleware
 * @package Jet\AdminBlock\Middleware
 */
class AjaxCSRFMiddleware extends MainMiddleware
{

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
        if ($request->ajax() || $request->pjax()) {
            $response = $responseProvider->getResponse();
            if (!$this->hasXss(['token' => $request->headers->get('X-CSRF-TOKEN'), 'time' => 7200])) {
                $auth->logout();
                return $response->answer(json_encode(['redirect' => true, 'target' => $admin_url]), 302, 'application/json');
            } elseif ($auth->guest() && $route->getTarget('controller') != AuthController::class) {
                return $response->answer(json_encode(['redirect' => true, 'target' => $admin_url]), 401, 'application/json');
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
            if ($this->isToken($token['token'], $token['time'])) return true;
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
                if ($this->session->get('_token_')['time'] >= (time() - $time))
                    return true;
            }
        }
        return false;
    }


}
