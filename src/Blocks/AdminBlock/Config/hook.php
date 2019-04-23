<?php

return [
    'left_sidebar' => [
        'Main' => [
            'Dashboard' => [
                'icon' => 'fa fa-home',
                'link' => '/dashboard'
            ],
            'Pages' => [
                'icon' => 'fa fa-file',
                'link' => '/pages'
            ],
        ],
        'Extras' => [
            'Medias' => [
                'icon' => 'fa fa-image',
                'link' => '/medias'
            ],
            'Modules' => [
                'icon' => 'fa fa-puzzle-piece',
                'link' => '/pages'
            ],
            'Design' => [
                'icon' => 'ti-layout',
                'items' => [
                    'Themes' => [
                        'link' => '/themes'
                    ],
                    'Templates' => [
                        'link' => '/templates',
                    ],
                    'Custom Fields' => [
                        'link' => '/custom-fields',
                    ]
                ]
            ]
        ],
        'Configuration' => [
            'Websites' => [
                'icon' => 'fa fa-tv',
                'link' => '/websites'
            ],
            'Administration' => [
                'icon' => 'ion-gear-b',
                'items' => [
                    'Accounts' => [
                        'link' => '/accounts'
                    ],
                    'Roles' => [
                        'link' => '/roles'
                    ],
                    'Permissions' => [
                        'link' => '/permissions',
                    ]
                ]
            ],
        ],
        'divider@1' => [
            'Help' => [
                'icon' => 'fa fa-question-circle',
                'items' => [
                    'FAQ' => [
                        'link' => '/dashboard/test'
                    ],
                    'Changelog' => [
                        'link' => '/dashboard/test'
                    ]
                ]
            ]
        ]
    ],
    'header' => [
        'left' => [
            'Box' => [
                'template' => 'Include/header_box_dropdown.html.twig',
                'items' => [
                    'Dashboard' => [
                        'icon' => 'home',
                        'link' => '/dashboard'
                    ],
                    'Gallery' => [
                        'icon' => 'stack_of_photos',
                        'link' => ''
                    ]
                ]
            ]
        ],
        'right' => [
            'Profile' => [
                'image' => 'avatar/1.jpg',
                'items' => [
                    'Profile' => [
                        'icon' => 'ti-user',
                        'link' => '/'
                    ],
                    'Inbox' => [
                        'icon' => 'ti-email',
                        'link' => '/'
                    ],
                    'Settings' => [
                        'icon' => 'ti-settings',
                        'link' => '/'
                    ],
                    'Logout' => [
                        'divider' => true,
                        'icon' => 'ti-power-off',
                        'link' => '/'
                    ]
                ]
            ]
        ]
    ]
];