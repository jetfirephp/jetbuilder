<?php

namespace Jet\AdminBlock\Requests;

use JetFire\Framework\System\Request;

class LogRequest extends Request
{

    public static $messages = [];
    
    public static function rules()
    {
        return [];
    }
} 