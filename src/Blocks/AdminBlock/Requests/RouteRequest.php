<?php

namespace Jet\AdminBlock\Requests;

use JetFire\Framework\System\Request;

class RouteRequest extends Request
{

    public static $messages = [
        'required' => 'Tout les champs sont requis',
        'noWhitespace' => 'L\'url et le nom ne doivent pas comporter d\'espace',
    ];
    
    public static function rules()
    {
        return [
            'url|name' => 'required|noWhitespace',
            'method|position' => 'required',
            'middleware|subdomain' => 'optional'
        ];
    }
}