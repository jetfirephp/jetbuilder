<?php

return [

    'app' => [
        'loader' => [
            'namespaces' => [
                'Jet\\AdminBlock' => 'src/Blocks/AdminBlock',
            ]
        ],
        'blocks' => [
            'Admin' => [
                'path' => 'src/Blocks/AdminBlock/',
                'namespace' => '\\Jet\\AdminBlock',
                'view_dir' => 'src/Blocks/AdminBlock/Views/',
                'prefix' => 'admin/:_lang_code',
                //'subdomain' => 'admin',
                'params' => [
                    'domain_key' => 'admin_domain',
                    'locale_domain' => 'admin',
                    'lang_codes' => [
                        'en' => 'en_GB',
                        'fr' => 'fr_FR',
                        'it' => 'it_IT',
                    ]
                ]
            ]
        ],
        'locales' => [
            'admin' => [
                /*'en_GB' => ROOT . '/src/Blocks/AdminBlock/Resources/locale/en_GB.php',*/
                'fr_FR' => ROOT . '/src/Blocks/AdminBlock/Resources/locale/fr_FR.php',
            ]
        ]
    ],

    'admin' => [

        'libs' => [
            'css' => [
                'libs/bootstrap/bootstrap.css',
                'libs/material/materialadmin.css',
                'libs/material/material-design-iconic-font.min.css',
                'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
                'https://fonts.googleapis.com/css?family=Roboto:300italic,400italic,300,400,500,700,900',
            ],
            'js' => [
                'https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyArNGSSl-KPS23l24EilPNQhfmEw_V8BtI&libraries=places&region=FR'
            ]
        ],

        'module_check' => false,

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
    ]
];