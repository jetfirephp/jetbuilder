<?php

namespace Jet\Test\Blocks\AdminBlock\Controllers;

use Jet\Models\Account;
use Jet\Models\Website;
use Jet\AdminBlock\Services\Auth;
use Jet\Test\BaseTest;

/**
 * Class AdminControllerTest
 * @package Jet\Test\Controllers
 */
abstract class AdminControllerTest extends BaseTest
{
    /**
     * @var Auth
     */
    protected $auth = null;

    /**
     *
     */
    public function tearDown()
    {
        parent::tearDown();
        $_POST = [];
        $_GET = [];
        $_SERVER['REQUEST_METHOD'] = 'GET';
    }

    /**
     * @param int $id
     * @return bool
     */
    protected function logAuth($id = 1)
    {
        $this->auth = $this->app->get('auth');
        $account = Account::where('id', $id)->get(true);
        if (!is_null($account) && isset($account['id'])) {
            $this->auth->log($account->_getTable());
            $this->auth->getSession()->set('_auth_websites', Website::repo()->getAccountWebsites($account['id']));
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    protected function logoutAuth(){
        if(!is_null($this->auth)) {
            $this->auth->logout();
            $this->auth->getSession()->clear();
            return true;
        }
        return false;
    }

}