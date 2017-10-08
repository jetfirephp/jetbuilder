<?php

namespace Jet\ApiBlock\Middleware;

use JetFire\Framework\App;
use JetFire\Framework\System\Request;
use JetFire\Framework\System\Response;

class Permission {

    /**
     * @param App $app
     * @param Request $request
     * @param Response $response
     * @return bool
     */
    public function handle(App $app, Request $request, Response $response){
        if($request->headers->has('AUTH-KEY')) {
            $key = (isset($app->data['app']['settings']['event_options']['AUTH-KEY'])) ? $app->data['app']['settings']['event_options']['AUTH-KEY'] : null;
            if(!is_null($key) && $request->headers->get('AUTH-KEY') == $key) {
                return true;
            }
        }
        return $response->setStatusCode(401);
    }

}
