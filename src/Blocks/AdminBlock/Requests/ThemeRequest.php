<?php

namespace Jet\AdminBlock\Requests;

use JetFire\Framework\System\Request;

class ThemeRequest extends Request
{

    public static $messages = [
        'required' => 'Tous les champs sont requis',
    ];
    
    public function rules()
    {
        return [
            'name|society|thumbnail|professions' => 'required'
        ];
    }

}