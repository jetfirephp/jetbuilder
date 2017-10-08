<?php

namespace Jet\AdminBlock\Requests;

use JetFire\Framework\System\Request;

class AccountRequest extends Request
{

    public static $messages = [
        'required' => 'Tout les champs précédé d\'un astérix doivent être remplis',
        'email' => 'Le format de l\'email est incorrect',
        'length' => 'Le nom et le prénom doivent comporter au plus 2 caractères et au moins 20 caractères',
        'same' => 'Les 2 mots de passe doivent être identiques',
        'noWhitespace' => 'Le mot de passe ne doit pas contenir d\'espace'
    ];


    public static function createRules()
    {
        return [
            'first_name|last_name' => 'required|length:2,20',
            'email' => 'required|email',
            'phone' => 'required',
            'confirm_pass' => 'required|noWhitespace|requiredWith:password',
            'password' => 'required|same:confirm_pass|noWhitespace|assign:crypt,password_hash',
            'status.id' => 'required|numeric',
            'photo.id' => 'optional|numeric',
            'state' => 'assignIf:empty,1'
        ];
    }

    public function updateRules()
    {
        return [
            // rules when we update an account
            'first_name|last_name' => 'required|length:2,20',
            'email' => 'required|email',
            'phone' => 'required',
            'status.id' => 'required|numeric',
            'status.level' => 'optional',
            'photo.id' => 'optional|numeric',
            'confirm_pass' => 'optional|requiredWith:password',
            'password' => 'optional|requiredWith:confirm_pass|same:confirm_pass|noWhitespace|assign:crypt,password_hash',
        ];
    }

} 