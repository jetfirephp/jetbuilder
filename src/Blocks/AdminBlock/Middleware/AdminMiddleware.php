<?php

namespace Jet\AdminBlock\Middleware;

use Jet\AdminBlock\Controllers\AuthController;
use Jet\Middleware\MainMiddleware;
use Jet\Services\Auth;
use JetFire\Framework\App;
use JetFire\Framework\Providers\ResponseProvider;
use JetFire\Framework\Providers\SessionProvider;
use JetFire\Framework\System\JsonResponse;
use JetFire\Framework\System\Request;
use JetFire\Routing\ResponseInterface;
use JetFire\Routing\Route;

/**
 * Class Admin
 * @package Jet\AdminBlock\Middleware
 */
class AdminMiddleware extends MainMiddleware
{

    /**
     * @var
     */
    private $session;

    /**
     * @param App $app
     * @param Route $route
     * @param Request $request
     * @param SessionProvider $sessionProvider
     * @param ResponseProvider $responseProvider
     * @param Auth $auth
     * @return bool|ResponseInterface
     */
    public function beforeHandle(App $app, Route $route, Request $request, SessionProvider $sessionProvider, ResponseProvider $responseProvider, Auth $auth)
    {
        $this->session = $sessionProvider->getSession();
        $redirect = $responseProvider->getRedirect();
        $admin_url = $this->getAdminUrl($app);

        if ($request->has('redirect_url') && $auth->check()) {
            return $redirect->url($admin_url . '#/' . $request->get('redirect_url'));
        }

        if ($auth->guest() && $route->getTarget('controller') != AuthController::class) {
            if ($request->has('redirect_url')) {
                $this->session->set('redirect_url', $request->get('redirect_url'));
            }
            return $redirect->to('admin.auth', ['_lang_code' => $app->data['_lang_code']]);
        }

        if ($auth->check() && $route->getTarget('controller') == AuthController::class && !in_array($route->getTarget('action'), ['logout', 'getAuth', 'loginAsUser'])) {
            return $redirect->to('admin.home', ['_lang_code' => $app->data['_lang_code']]);
        }
        
        return true;
    }

    /**
     * @param Route $route
     * @return bool|JsonResponse
     */
    public function afterHandle(Route $route)
    {
        return ($route->hasTarget('data') && isset($route->getParams()['ajax']) && $route->getParams()['ajax'] === true)
            ? new JsonResponse($route->getTarget('data'))
            : true;
    }
    
}
