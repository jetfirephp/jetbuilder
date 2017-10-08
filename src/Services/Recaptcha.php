<?php

namespace Jet\Services;

/**
 * Class Recaptcha
 * @package Jet\Services
 */
class Recaptcha
{

    /**
     * @var bool|string
     */
    private $secret_key = '';
    /**
     * @var string
     */
    private $public_key = '';

    /**
     * Recaptcha constructor.
     * @param $public_key
     * @param $secret_key
     */
    public function __construct($public_key, $secret_key = null)
    {
        $this->public_key = $public_key;
        if (!is_null($secret_key)) $this->secret_key = $secret_key;
    }

    /**
     * @return bool|string
     */
    public function getSecretKey()
    {
        return $this->secret_key;
    }

    /**
     * @return string
     */
    public function getPublicKey()
    {
        return $this->public_key;
    }

    /**
     * @param $id
     */
    public function html($id)
    {
        echo '<div id="' . $id . '"class="g-recaptcha" data-sitekey="' . $this->public_key . '"></div>';
    }

    /**
     *
     */
    public function js()
    {
        echo '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
    }

    /**
     * @param null $code
     * @param null $ip
     * @return bool
     */
    public function isValid($code = null, $ip = null)
    {
        if (is_null($code)) $code = $_POST['g-recaptcha-response'];
        if (!is_null($ip)) $params['remoteip'] = $ip;
        $url = "https://www.google.com/recaptcha/api/siteverify?secret={$this->secret_key}&response={$code}";
        if (function_exists('curl_version')) {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_TIMEOUT, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // Evite les problèmes, si le ser
            $response = curl_exec($curl);
        } else {
            $response = file_get_contents($url);
        }

        if (empty($response) || is_null($response)) {
            return false;
        }

        $json = json_decode($response);
        return $json->success;
    }

}