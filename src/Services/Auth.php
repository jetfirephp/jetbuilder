<?php

namespace Jet\Services;

use JetFire\Framework\System\Cookie;
use JetFire\Http\Session;

/**
 * Class Auth
 * @package Jet\Services
 */
class Auth
{

    /**
     * @var Session
     */
    protected $session;
    /**
     * @var Cookie
     */
    protected $cookie;
    /**
     * @var Auth
     */
    private static $instance;

    /**
     * Auth constructor.
     * @param Session $session
     * @param Cookie $cookie
     */
    public function __construct(Session $session, Cookie $cookie)
    {
        $this->session = $session;
        $this->cookie = $cookie;
        self::$instance = $this;
    }

    /**
     * return Session
     */
    public function getSession(){
        return $this->session;
    }

    /**
     * return Cookie
     */
    public function getCookie(){
        return $this->cookie;
    }

    /**
     * @return Auth
     */
    public static function getInstance()
    {
        return self::$instance;
    }

    /**
     * @param $account
     * @param string $role
     */
    public function log($account, $role = 'admin')
    {
        $this->session->set('_' . $role . '_logged_', true);
        is_object($account)
            ? $this->session->set('_' . $role . '_auth_', json_decode(json_encode($account)))
            : $this->session->set('_' . $role . '_auth_', $account);
    }

    /**
     * @param $account
     * @param string $role
     */
    public function update($account, $role = 'admin')
    {
        is_object($account)
            ? $this->session->set('_' . $role . '_auth_', json_decode(json_encode($account)))
            : $this->session->set('_' . $role . '_auth_', $account);
    }

    /**
     * @param null $key
     * @param string $role
     * @return mixed
     */
    public function get($key = null, $role = 'admin')
    {
        if (is_null($key)) return $this->session->get('_' . $role . '_auth_');
        if($this->session->has('_' . $role . '_auth_')) {
            return is_array($this->session->get('_' . $role . '_auth_'))
                ? $this->session->get('_' . $role . '_auth_')[$key]
                : $this->session->get('_' . $role . '_auth_')->$key;
        }
        return null;
    }

    /**
     * @param string $role
     * @return bool
     */
    public function check($role = 'admin')
    {
        return ($this->session->get('_' . $role . '_logged_')) ? true : false;
    }

    /**
     * @param string $role
     * @return bool
     */
    public function guest($role = 'admin')
    {
        return ($this->session->get('_' . $role . '_logged_')) ? false : true;
    }

    /**
     * @param string $role
     */
    public function logout($role = 'admin')
    {
        $this->session->destroy('_' . $role . '_logged_');
        $this->session->destroy('_' . $role . '_auth_');
        $this->cookie->destroy('_' . $role . '_remember_id');
        $this->cookie->destroy('_' . $role . '_remember_token');
    }

    /**
     * @param $account
     * @param int $time
     * @param string $role
     */
    public function setRemember($account, $time = 3600, $role = 'admin')
    {
        $this->cookie->init('_' . $role . '_remember_id', $account['id'], time() + $time);
        $this->cookie->init('_' . $role . '_remember_token', $account['token'], time() + $time);
    }

    /**
     * @param string $role
     * @return bool
     */
    public function hasRemember($role = 'admin')
    {
        return (!is_null($this->cookie->get('_' . $role . '_remember_id')) && !is_null($this->cookie->get('_' . $role . '_remember_token')))
            ? true
            : false;
    }

    /**
     * @param $value
     * @param string $role
     * @return mixed
     */
    public function getRemember($value, $role = 'admin')
    {
        return $this->cookie->get('_' . $role . '_remember_' . $value);
    }

    /**
     * @param string $role
     */
    public function removeRemember($role = 'admin')
    {
        $this->cookie->destroy('_' . $role . '_remember_id');
        $this->cookie->destroy('_' . $role . '_remember_token');
    }

    /**
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        $params = preg_split('/(?=[A-Z])/', $method, -1, PREG_SPLIT_NO_EMPTY);
        if (!isset($arguments[0])) $arguments[0] = null;
        return $this->$params[0]($arguments[0], strtolower($params[1]));
    }

    /**
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        $params = preg_split('/(?=[A-Z])/', $method, -1, PREG_SPLIT_NO_EMPTY);
        if (!isset($arguments[0])) $arguments[0] = null;
        return self::getInstance()->$params[0]($arguments[0], strtolower($params[1]));
    }


}