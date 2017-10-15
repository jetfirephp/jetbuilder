<?php

return [

    'before' => [

        'global_middleware' => [
            'Jet\Middleware\SystemMiddleware@beforeHandle',
            'Jet\Middleware\TranslationMiddleware@beforeHandle'
        ],

        'block_middleware' => [
            'src/Blocks/AdminBlock/' => [
                'Jet\AdminBlock\Middleware\AdminMiddleware@beforeHandle',
                'Jet\AdminBlock\Middleware\AdminCSRFMiddleware',
            ],
            //'src/Blocks/PublicBlock/' => 'Jet\Middleware\CSRFMiddleware'
        ],

        'class_middleware' => [
            
        ],

        'route_middleware' => [
            'admin' => 'Jet\AdminBlock\Middleware\AdminMiddleware@beforeHandle',
        ],

    ],

    'between' => [

        'global_middleware' => [
            'Jet\Middleware\TranslationMiddleware@betweenHandle',
        ],

        'block_middleware' => [
            'src/Blocks/AdminBlock/' => [
                'Jet\AdminBlock\Middleware\AdminMiddleware@betweenHandle',
            ],
        ],

        'class_middleware' => [

        ],

        'route_middleware' => [

        ],

    ],

    'after' => [

        'route_middleware' => [
            
        ],

        'class_middleware' => [

        ],
        
        'block_middleware' => [

        ],

        'global_middleware' => [
            'Jet\Middleware\TranslationMiddleware@afterHandle',
            'Jet\Middleware\SystemMiddleware@afterHandle'
        ],

    ]

];