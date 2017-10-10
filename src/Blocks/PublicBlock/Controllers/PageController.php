<?php

namespace Jet\PublicBlock\Controllers;

use Jet\Models\Theme;
use JetFire\Framework\System\Controller;


class PageController extends Controller
{

    /**
     * @return array
     */
    public function index()
    {
        $domain = (isset($this->app->data['setting']['domain'])) ? $this->app->data['setting']['domain'] : '';
        $path = $domain . WEBROOT . 'site/';
        return compact('path');
    }

    /**
     * @return array
     */
    public function price()
    {
        $price = $this->app->data['setting']['payment'];
        unset($price['stripe']);
        return compact('price');
    }

    /**
     * @return array
     */
    public function theme()
    {
        $domain = (isset($this->app->data['setting']['domain'])) ? $this->app->data['setting']['domain'] : '';
        $path = $domain . WEBROOT . 'site/';
        $themes = Theme::repo()->frontList();
        return compact('themes', 'path');
    }
} 