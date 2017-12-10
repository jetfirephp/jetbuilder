<?php

return [

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