<?php

namespace Jet\AdminBlock\Requests;

use JetFire\Framework\System\Request;

class ContentRequest extends Request
{

    public static $messages = [
        'required' => 'Tous les champs précédés d\'un astérisque sont obligatoires',
    ];
    
    public function updateRules()
    {
        return [
            'name|block|module.id' => 'required',
            'template.id|page' => 'optional'
        ];
    }

}