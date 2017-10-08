<?php

namespace Jet\AdminBlock\Requests;

use JetFire\Framework\System\Request;

class TemplateRequest extends Request
{

    public static $messages = [
        'required' => 'Tout les champs sont requis',
        'length' => 'Le nom et le titre doivent comporter au plus 2 caractères et au moins 50 caractères',
        'noWhitespace' => 'Le nom ne doit pas contenir d\'espace',
        'regex:category' => 'La catégorie n\'est pas reconnue',
        'regex:type' => 'Le type n\'est pas reconnu',
        'regex:scope' => 'Le scope n\'est pas reconnu'
    ];


    public static function rules()
    {
        return [
            'name' => 'required|noWhitespace|length:2,50',
            'title' => 'required|length:2,50',
            'category' => 'required|regex:/^(layout`OR`stylesheet`OR`partial)$/',
            'type' => 'required|regex:/^(file`OR`content)$/',
            'scope' => 'required|regex:/^(global`OR`specified)$/',
            'content' => 'required',
            'path' => 'optional'
        ];
    }
} 