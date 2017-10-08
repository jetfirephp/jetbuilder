<?php

namespace Jet\PublicBlock\Requests;

use JetFire\Framework\System\Request;

class AccountRequest extends Request
{

    public static $messages = [
        'required' => 'Tout les champs précédé d\'un astérix doivent être remplis',
        'mail' => 'Le format de l\'email est incorrect',
        'length' => 'Le nom, prénom et le nom de la société doivent comporter au moins 2 caractères et au plus 20 caractères',
        'phone' => 'Le format du numéro de téléphone est incorrect',
        'postalCode' => 'Le code postal n\'est pas correct',
        'noWhitespace' => 'L\'identifiant et le mot de passe ne doivent pas contenir d\'espace',
        'same' => 'Les 2 mots de passe doivent être identiques',
    ];


    public static function rules()
    {
        return [
            'account.first_name|account.last_name' => 'required|length:2,20',
            'society.name' => 'required|length:3,20',
            'account.phone' => 'required|phone',
            'address.address|address.city' => 'required',
            'address.postal_code' => 'required|postalCode:FR',
            'account.email' => 'required|mail',
            'account.confirm_pass' => 'required|noWhitespace',
            'account.password' => 'required|noWhitespace|same:account.confirm_pass|assign:crypt,password_hash',
            'account.state' => 'assign:0',
            'status' => 'assign:user',
            'captcha' => 'required',
            '_token' => 'required',
            'token' => 'assign:' . md5(uniqid(rand(), true)),
        ];
    }

}