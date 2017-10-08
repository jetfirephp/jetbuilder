<?php

namespace Jet\FrontBlock\Requests;

use JetFire\Framework\App;
use JetFire\Framework\System\Request;

class FrontRequest extends Request
{
    /**
     * @return array
     */
    public function getCurrentUrl()
    {
        $url = ($this->getServer()->has('REQUEST_SCHEME') ? $this->getServer()->get('REQUEST_SCHEME') : 'http') . '://' . $this->getServer()->get('SERVER_NAME') . WEBROOT;

        $this->attributes->set('_website_url', $url);
        $slug = explode('=', $this->getServer()->get('QUERY_STRING'));
        $slug = isset($slug[1]) ? explode('/', $slug[1]) : '';
        $slug = (!empty($slug) && $slug[0] == '@site@' && isset($slug[1])) ? $slug[1] : '';

        return empty($slug) ? rtrim($url, '/') : $slug;
    }
} 