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
                'route' => include 'routes.php',
                'namespace' => '\\Jet\\AdminBlock',
                'view_dir' => [
                    'admin' => 'src/Blocks/AdminBlock/Views/',
                    'module' => 'src/Modules/',
                    'theme' => 'src/Themes/'
                ],
                'prefix' => 'admin/:_lang_code[/:_website]',
                //'subdomain' => 'admin',
                'params' => [
                    'arguments' => ['_website' => '?(1)([0-9]+)'],
                    'domain_key' => 'admin_domain',
                    'locale_domain' => 'admin',
                    'lang_codes' => [
                        'en' => 'en_GB',
                        'fr' => 'fr_FR',
                    ]
                ]
            ]
        ],
        'locales' => [
            'admin' => [
                'en_GB' => [ROOT . '/src/Blocks/AdminBlock/Resources/locale/en_GB.php'],
                'fr_FR' => [ROOT . '/src/Blocks/AdminBlock/Resources/locale/fr_FR.php'],
            ]
        ]
    ],

    'admin' => [

        'libs' => [
            'basePath' => 'src/Blocks/AdminBlock/Resources/public/',
            'css' => [
                'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,300i',
                'core.min.css',
                'app.min.css',
                'style.min.css',
                'admin.css'
            ],
            'js' => [
                'https://unpkg.com/vue',
                'https://unpkg.com/axios/dist/axios.min.js',
                'core.min.js',
                'app.min.js',
                'script.min.js',
                'admin.js',
            ]
        ],

        'module_check' => false,

        'hook' => include 'hook.php',

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