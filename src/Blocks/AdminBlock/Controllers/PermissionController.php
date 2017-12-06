<?php

namespace Jet\AdminBlock\Controllers;

use Jet\AdminBlock\Requests\ProfessionRequest;
use Jet\Models\Profession;


/**
 * Class PermissionController
 * @package Jet\AdminBlock\Controllers
 */
class PermissionController extends AdminController
{

    public function listAll(){
        return ['routes' => $this->app->data['routes']['src/Blocks/AdminBlock/']];
    }
}