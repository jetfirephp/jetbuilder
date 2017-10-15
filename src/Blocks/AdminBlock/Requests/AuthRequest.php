<?php

namespace Jet\AdminBlock\Requests;

use JetFire\Framework\System\Request;

class AuthRequest extends Request
{

    public static $messages = [
        'required:email' => 'E-mail is required',
        'required:password' => 'Password is required',
        'noWhitespace' => 'Password cannot contain whitespace',
        'email' => 'Invalid email format'
    ];

    public static function loginRules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|noWhitespace',
            'remember' => 'optional|boolean'
        ];
    }

} 