<?php

return [

    '/asset/:path'	=> [
        'use' => 'AssetController@asset',
        'arguments' => ['path' => '(.*)']
    ],

    /* PROD */
    '/' => [
        'use' => 'FrontController@dispatch',
    ],
    '{subdomain}.{host}/test/*' => [
        'use' => 'FrontController@dispatch',
        /*'subdomain' => '((?!admin|api|www|mail|info|contact|support).)*'*/
    ],
    /* DEV */
    '/@site@/*' => [
        'use' => 'FrontController@dispatcher'
    ]
];