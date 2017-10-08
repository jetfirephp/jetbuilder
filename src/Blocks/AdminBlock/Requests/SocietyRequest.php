<?php

namespace Jet\AdminBlock\Requests;

use JetFire\Framework\System\Request;

/**
 * Class SocietyRequest
 * @package Jet\AdminBlock\Requests
 */
class SocietyRequest extends Request
{

    /**
     * @var array
     */
    public static $messages = [
        'required' => 'Tous les champs sont requis',
        'numeric' => 'L\'identifiant du compte est requis pour assigner cette société à calui-ci'
    ];

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'account' => 'required|numeric'
        ];
    }

}