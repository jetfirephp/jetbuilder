<?php

namespace Jet\AdminBlock\Requests;

use JetFire\Framework\System\Request;

class LibraryRequest extends Request
{

    public static $messages = [
        'required' => 'Tout les champs sont requis',
        'length' => 'Le nom doit comporter au plus 2 caractères et au moins 30 caractères',
        'regex:category' => 'La catégorie n\'est pas reconnue',
        'regex:type' => 'Le type n\'est pas reconnu'
    ];
    
    public static function rules()
    {
        return [
            'name' => 'required|length:2,30',
            'path' => 'required',
            'type' => 'required|regex:/^(file`OR`cdn)$/',
            'category' => 'required|regex:/^(js`OR`css)$/'
        ];
    }
} 