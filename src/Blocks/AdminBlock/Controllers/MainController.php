<?php

namespace Jet\AdminBlock\Controllers;

use Jet\Services\Admin;
use JetFire\Framework\System\Controller;

/**
 * Class MainController
 * @package Jet\AdminBlock\Controllers
 */
class MainController extends Controller
{
    use Admin;

    public function render($path, $data = [])
    {
        $admin_url = $this->getAdminUrl($this->app);
        $app_name = (isset($this->app->data['setting']['name'])) ? $this->app->data['setting']['name'] : 'JetBuilder';
        $data = array_merge([
            'app_name' => $app_name,
            'admin_url' => $admin_url,
            'locale' => $this->app->data['_locale'],
            'libs' => $this->app->data['admin']['libs']
        ], $data);
        return $this->app->get('response')->getView()->render($path, $data);
    }

} 