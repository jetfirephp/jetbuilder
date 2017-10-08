<?php

namespace Jet\AdminBlock\Requests;

use JetFire\Framework\System\Request;

/**
 * Class PageRequest
 * @package Jet\AdminBlock\Requests
 */
class PageRequest extends Request
{

    /**
     * @var array
     */
    public static $messages = [
        'required' => 'Tout les champs sont requis',
    ];

    /**
     * @return array
     */
    public static function createRules()
    {
        return [
            'type' => 'required|regex:/^(static`OR`dynamic)$/',
            'builder|published' => 'required|boolean',
            'title|route|layout' => 'required'
        ];
    }

    /**
     * @return array
     */
    public static function updateRules()
    {
        return [
            'id' => 'required|numeric',
            'type' => 'required|regex:/^(static`OR`dynamic)$/',
            'builder|published' => 'required|boolean',
            'title|route|layout' => 'required'
        ];
    }
}