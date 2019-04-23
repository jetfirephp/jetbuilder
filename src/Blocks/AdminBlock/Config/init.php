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
                    'admin' => ROOT . '/src/Blocks/AdminBlock/Views/',
                    'module' => ROOT . '/src/Modules/',
                    'theme' => ROOT . '/src/Themes/'
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
        ],
        'assets' => [
            'admin' => [
                'targetPath' => [
                    'css' => 'admin.css',
                    'js' => 'admin.js'
                ],
                'css' => [
                    //'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,300i',
                    'src/Blocks/AdminBlock/Resources/public/css/*.css',
                    /*'src/Blocks/AdminBlock/Resources/public/css/app.min.css',
                    'src/Blocks/AdminBlock/Resources/public/css/style.min.css',
                    'src/Blocks/AdminBlock/Resources/public/css/admin.css'*/
                ],
                'js' => [
                    'https://unpkg.com/vue',
                    'https://unpkg.com/axios/dist/axios.min.js',
                    'src/Blocks/AdminBlock/Resources/public/js/core.min.js',
                    'src/Blocks/AdminBlock/Resources/public/js/app.min.js',
                    'src/Blocks/AdminBlock/Resources/public/js/script.min.js',
                    'src/Blocks/AdminBlock/Resources/public/js/admin.js',
                ]
            ],
        ]
    ],

    'admin' => [

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