<?php

namespace Jet\Services;

use JetFire\Framework\App;

/**
 * Class LoadFixture
 * @package Jet\Services
 */
trait Admin
{

    protected function getAdminUrl(App $app){
        $prefix = isset($app->data['app']['blocks']['Admin']['prefix']) ? $app->data['app']['blocks']['Admin']['prefix'] : '';
        $prefix = str_replace(':_lang_code', $app->data['_lang_code'], $prefix);
        return isset($app->data['setting']['admin_domain']) ? rtrim($app->data['setting']['admin_domain'], '/') . '/' . trim($prefix, '/') . '/' : '';
    }
}