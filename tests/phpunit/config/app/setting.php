<?php
return [
    /* Api request key */
    'event_options' => [
        'AUTH-KEY' => 'CD2752619E8F14AAD42CA815FABC2'
    ],
    /* Account trial days */
    'trial_days' => '+15days',
    /* Check for module update*/
    'module_check' => false,

    'exclude_sub_domain' => ['admin','api','www','mail','info','contact','blog','support'],

    'custom_field_type' => [
        'basic_type' => [
            'name' => 'Champs basiques',
            'values' => [
                ['string', 'Texte', './components/CustomFieldRender/Default/DefaultCustomField.vue', './components/CustomFieldRender/Default/DefaultRenderCustomField.vue'],
                ['textarea', 'Zone de texte', './components/CustomFieldRender/Default/DefaultCustomField.vue', './components/CustomFieldRender/Textarea/TextareaRenderCustomField.vue'],
                ['number', 'Nombre', './components/CustomFieldRender/Number/NumberCustomField.vue', './components/CustomFieldRender/Number/NumberRenderCustomField.vue'],
                ['select', 'Select', './components/CustomFieldRender/Select/SelectCustomField.vue', './components/CustomFieldRender/Select/SelectRenderCustomField.vue'],
                ['date', 'Date', './components/CustomFieldRender/Date/DateCustomField.vue', './components/CustomFieldRender/Date/DateRenderCustomField.vue']
            ]
        ],
        'content' => [
            'name' => 'Contenu',
            'values' => [
                ['wysiwyg', 'Éditeur WYSIWYG', './components/CustomFieldRender/Default/DefaultCustomField.vue', './components/CustomFieldRender/Tinymce/TinymceRenderCustomField.vue'],
                ['media', 'Média', './components/CustomFieldRender/Media/MediaCustomField.vue', './components/CustomFieldRender/Media/MediaRenderCustomField.vue'],
            ]
        ],
        'plugin' => [
            'name' => 'Plugin',
            'values' => [
                ['color', 'Sélecteur de couleur', './components/CustomFieldRender/Default/DefaultCustomField.vue', './components/CustomFieldRender/Color/ColorRenderCustomField.vue'],
                ['json', 'Editeur json', './components/CustomFieldRender/Default/DefaultCustomField.vue', './components/CustomFieldRender/Json/JsonRenderCustomField.vue'],
                ['editor', 'Editeur', './components/CustomFieldRender/Editor/EditorCustomField.vue', './components/CustomFieldRender/Editor/EditorRenderCustomField.vue'],
            ]
        ],
        'disposition' => [
            'name' => 'Disposition',
            'values' => [
                ['repeater', 'Repeater']
            ]
        ]
    ],

    'custom_field_callback' => [
        'media' => '\\Jet\\AdminBlock\\Controllers\\MediaController@renderField',
    ],
    'publication_type' => [
        'page' =>
            [
                'id' => 'page',
                'name' => 'Page',
                'renderCallback' => '\\Jet\\AdminBlock\\Controllers\\PageController@listCustomFields'
            ]
    ],
    'recaptcha' => [
        'public_key' => '6LcqFCQTAAAAAAG_gnMpgTnE6809TroE30F4fMcp',
        'secret_key' => '6LcqFCQTAAAAAFCK_vTqCOS8slr8s5AI4Y3WwWNQ'
    ]
];