<?php

namespace Jet\AdminBlock\Requests;

use JetFire\Framework\System\Request;

class AuthRequest extends Request
{

    public static $messages = [
        'required' => 'Tout les champs précédé d\'un astérix doivent être remplis',
        'noWhitespace' => 'L\'identifiant et le mot de passe ne doivent pas contenir d\'espace',
        'email' => 'L\'email n\'est pas au bon format'
    ];

    public static function loginRules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|noWhitespace',
            'remember' => 'required|boolean'
        ];
    }

} 