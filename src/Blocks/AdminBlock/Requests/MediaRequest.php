<?php

namespace Jet\AdminBlock\Requests;

use JetFire\Framework\System\Request;

class MediaRequest extends Request
{

    public static $messages = [
        'required:file' => 'Fichier introuvable',
        'required:dir' => 'Dossier introuvable',
        'required:name' => 'Le nom du fichier est requis',
        'noWhitespace' => 'Le nom du fichier ne doit pas contenir d\'espace',
        'size' => 'Le poids du fichier doit être inférieur à 20Mo',
        'format' => 'Le format du fichier est incorrect. Fomat accepté : ',
    ];

    public function createRules()
    {
        return [
            'dir' => 'required',
            'title|alt' => 'optional',
            'website' => 'optional|numeric',
            'file' => 'required|size:<20971520',
        ];
    }

    public function updateRules()
    {
        return [
            'path|name' => 'required|noWhitespace',
            'title' => 'required',
            'alt' => 'optional',
            'type' => 'required',
            'size' => 'required',
        ];
    }

}