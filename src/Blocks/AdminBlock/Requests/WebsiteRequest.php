<?php

namespace Jet\AdminBlock\Requests;

use JetFire\Framework\System\Request;

class WebsiteRequest extends Request
{

    public static $messages = [
        'required' => 'Tous les champs sont requis',
        'numeric:state' => 'La valeur de l\'état du site doit être numérique',
        'noWhitespace' => 'Le nom de domaine ne peut contenir d\'espace',
        'regex' => 'Le type de rendu doit être soit php ou js',
    ];
    
    public function rules()
    {
        return [
            'domain' => 'required|noWhitespace',
            'render_system' => 'required|regex:/^(php`OR`js)$/',
            'state' => 'required|numeric',
            'modules' => 'required',
            'expiration_date' => 'required',
            'theme|layout|society' => 'required|numeric'
        ];
    }

}