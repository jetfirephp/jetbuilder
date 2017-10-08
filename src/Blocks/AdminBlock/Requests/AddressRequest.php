<?php

namespace Jet\AdminBlock\Requests;

use JetFire\Framework\System\Request;

/**
 * Class AddressRequest
 * @package Jet\AdminBlock\Requests
 */
class AddressRequest extends Request
{

    /**
     * @var array
     */
    public static $messages = [
        'required' => 'Tous les champs sont requis',
        'numeric' => 'L\'id du compte est requis pour assigner cette adresse à celui-ci'
    ];

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'address|city|postal_code|country' => 'required',
            'alias' => 'optional',
            'account' => 'required|numeric',
            'latitude|longitude' => 'optional|assignIf:empty,0'
        ];
    }

}