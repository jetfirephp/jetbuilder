<?php

namespace Jet\AdminBlock\Controllers;

use JetFire\Framework\System\Controller;


/**
 * Class PermissionController
 * @package Jet\AdminBlock\Controllers
 */
class PermissionController extends Controller
{

    public function index(){
        return ['routes' => $this->app->data['routes']['src/Blocks/AdminBlock/']];
    }
}