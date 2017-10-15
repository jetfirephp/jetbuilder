<?php

namespace Jet\Middleware;

use Jet\Services\Admin;
use JetFire\Framework\Providers\ResponseProvider;
use JetFire\Framework\System\Request;
use JetFire\Http\Session;
use JetFire\Routing\ResponseInterface;

class CSRFMiddleware
{
    use Admin;
    /**
     * @var
     */
    private $session;
    /**
     * @var
     */
    private $request;

    /**
     * @param Request $request
     * @param ResponseProvider $responseProvider
     * @param Session $session
     * @return bool | ResponseInterface
     */
    public function handle(Request $request, ResponseProvider $responseProvider, Session $session){
        $this->request = $request;
        $this->session = $session;
        if ($this->request->method() != 'GET' && $this->request->has('_token')) {
            if (!$this->hasXss(['token' => $this->request->get('_token'), 'time' => 7200])) {
                if($this->request->ajax()){
                    return $responseProvider->getResponse()->answer(json_encode(['status' => 'error', 'message' => 'Invalid token']), 401, 'application/json');
                }else {
                    return $responseProvider->getRedirect()->back();
                }
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
        if (!isset($token['token'])) {
            if (!isset($token['time'])) $token['time'] = 600;
            if (!$this->isToken($token['time'])) return false;
        }
        return true;
    }

    /**
     * @param $time
     * @return bool
     */
    private function isToken($time)
    {
        if ($this->session->get('_token_') && $this->request->get('_token') != '') {
            if ($this->session->get('_token_')['key'] == $this->request->get('_token')) {
                if ($this->session->get('_token_')['time'] >= (time() - $time))
                    return true;
            }
        }
        return false;
    }

}