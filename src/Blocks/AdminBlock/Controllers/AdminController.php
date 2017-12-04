<?php

namespace Jet\AdminBlock\Controllers;

use Jet\Models\Account;
use Jet\AdminBlock\Services\Auth;
use JetFire\Framework\System\Controller;

/**
 * Class AdminController
 * @package Jet\AdminBlock\Controllers
 */
class AdminController extends Controller
{
    /**
     * @param Auth $auth
     * @return mixed
     */
    public function index(Auth $auth)
    {
        if ($auth->hasRemember()) {
            $account = Account::where('id', $auth->getRemember('id'))->where('token', $auth->getRemember('token'))->get(true);
            if (!is_null($account) && isset($account['id'])) $auth->log($account->_getTable());
        }
        return [];
    }

} 