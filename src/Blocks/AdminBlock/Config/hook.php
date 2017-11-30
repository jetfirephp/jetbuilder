<?php

return [
    'left_sidebar' => [
        'Preview' => [
            'Dashboard' => [
                'icon' => 'fa fa-home',
                'link' => '/dashboard'
            ],
            'Samples' => [
                'icon' => 'fa fa-tv',
                'items' => [
                    'Invoicer' => '/dashboard/test',
                    'Job Management'  => '/dashboard/test'
                ]
            ],
        ],
        'Framework' => [
            'Layout' => [
                'icon' => 'ti-layout',
                'items' => [
                    'Grid' => '/dashboard/test',
                    'Layout'  => '/dashboard/test'
                ]
            ]
        ],
        'divider@1' => [
            'Help' => [
                'icon' => 'fa fa-question-circle',
                'items' => [
                    'FAQ' => '/dashboard/test',
                    'Changelog'  => '/dashboard/test'
                ]
            ]
        ]
    ],
    ''
];