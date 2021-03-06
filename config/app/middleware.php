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
                'Jet\AdminBlock\Middleware\AjaxCSRFMiddleware',
            ],
            'src/Blocks/PublicBlock/' => 'Jet\Middleware\CSRFMiddleware'
        ],

        'class_middleware' => [
            
        ],

        'route_middleware' => [
            'admin' => 'Jet\AdminBlock\Middleware\AdminMiddleware@beforeHandle',
        ],

    ],

    'after' => [

        'global_middleware' => [
            'Jet\Middleware\TranslationMiddleware@afterHandle',
            'Jet\Middleware\SystemMiddleware@afterHandle'
        ],

        'block_middleware' => [
            'src/Blocks/AdminBlock/' => 'Jet\AdminBlock\Middleware\AdminMiddleware@afterHandle'
        ],

    ]

];