<?php

namespace Jet\AdminBlock\Requests;

use JetFire\Framework\System\Request;

class ProfessionRequest extends Request
{

    public static $messages = [
        'required' => 'Tous les champs sont requis',
        'alnum' => 'Le nom doit contenir uniquement des lettres ou des chiffres',
        'noWhitespace' => 'Le slug ne doit pas contenir d\'espace'
    ];

    public static function rules()
    {
        return [
            'name' => 'required|alnum',
            'slug' => 'required|noWhitespace',
            'icon' => 'required',
        ];
    }
} 