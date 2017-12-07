<?php

namespace Jet\Services\AppProvider;

use JetFire\Framework\Providers\RoutingProvider;

/**
 * Class AppRoutingProvider
 * @package Jet\Services\AppProvider
 */
class AppRoutingProvider extends RoutingProvider
{

    /**
     *
     */
    public function setAppRoutes()
    {
        $this->setRoutes($this->app->data['app']['blocks']);
    }
}