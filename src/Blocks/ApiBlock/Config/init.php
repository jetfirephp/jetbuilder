<?php
return [

    'app' => [
        'loader' => [
            'namespaces' => [
                'Jet\\ApiBlock' => 'src/Blocks/ApiBlock',
            ]
        ],
        'blocks' => [
            'Api' => [
                'path' => 'src/Blocks/ApiBlock/',
                'namespace' => '\\Jet\\ApiBlock',
                'view_dir' => 'src/Blocks/ApiBlock/Views/',
                'prefix' => 'api',
                'params' => [
                    'locale_domain' => 'api',
                    'lang_codes' => [
                        'en' => 'en_GB'
                    ]
                ]
            ]
        ]
    ],

    'api' => [
        /* Api request key */
        'event_options' => [
            'AUTH-KEY' => 'CD2752619E8F14AAD42CA815FABC2'
        ]
    ]
];