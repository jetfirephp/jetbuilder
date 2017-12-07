<?php

return [
    'left_sidebar' => [
        'CMS' => [
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
            'Themes' => [
                'icon' => 'ti-layout',
                'link' => '/themes'
            ],
            'Tools' => [
                'icon' => 'ion-wrench',
                'items' => [
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
            'Accounts' => [
                'icon' => 'fa fa-user',
                'link' => '/accounts'
            ],
            'Websites' => [
                'icon' => 'fa fa-tv',
                'link' => '/websites'
            ],
            'Permissions' => [
                'icon' => 'fa fa-unlock-alt',
                'items' => [
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