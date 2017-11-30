<?php
return [

    'required_files' => [
        ROOT . '/vendor/jetfirephp/framework/src/Helpers/system.php',
        ROOT . '/src/Helpers/app.php',
    ],

    'include_files' => [
        'setting' => ROOT . '/tests/phpunit/config/setting.inc.json',
        'app' => ROOT . '/tests/phpunit/config/app.inc.php',
        'config' => ROOT . '/tests/phpunit/config/config.inc.php',
        'middleware' => ROOT . '/config/middleware.inc.php',
        /* custom files */
        'introjs' => ROOT . '/config/intro.php'
    ],

    'providers' => [

        //|-----------------------------------|
        //| System Bootstrap providers        |
        //|-----------------------------------|
        'autoload' => [
            'use' => 'JetFire\Framework\Providers\AutoloadProvider',
            'rule' => [
                'shared' => true,
                'call' => ['init' => ['#app.loader']]
            ],
            'boot' => true,
        ],
        'app' => [
            'use' => 'Jet\Services\AppProvider\AppProvider',
            'rule' => [
                'shared' => true,
                'call' => [
                    'init' => []
                ]
            ],
            'boot' => true
        ],
        'system' => [
            'use' => 'JetFire\Framework\Providers\SystemProvider',
            'rule' => [
                'shared' => true,
                'call' => [
                    'init' => ['#setting.environment'],
                    'setDebugger' => ['#config.debugger'],
                    'handleError' => [''],
                ]
            ],
            'boot' => true
        ],
        'session' => [
            'use' => 'JetFire\Framework\Providers\SessionProvider',
            'rule' => [
                'shared' => true,
                'call' => [
                    'init' => ['#config.session', '#setting.environment'],
                    'start' => []
                ]
            ],
            'boot' => true,
        ],
        'response' => [
            'use' => 'JetFire\Framework\Providers\ResponseProvider',
            'rule' => [
                'shared' => true,
                'call' => [
                    'setViewClass' => ['#config.template.view'],
                    'setResponseClass' => ['#config.router.response'],
                    'setRedirectClass' => ['#config.router.redirect']
                ]
            ],
            'boot' => true,
        ],
        'routing' => [
            'use' => 'JetFire\Framework\Providers\RoutingProvider',
            'rule' => [
                'shared' => true,
                'call' => [
                    'init' => [],
                    'setAppRoutes' => ['#middleware'],
                    'setRouter' => ['#config.router', '#config.template', '#app.response'],
                    'setTemplateCallback' => ['#config.template']
                ],
                'instanceOf' => 'Jet\Services\AppProvider\AppRoutingProvider'
            ],
        ],
        'debug_toolbar' => [
            'use' => 'JetFire\Framework\Providers\DebugBarProvider',
            'rule' => [
                'shared' => true,
                'call' => [
                    'init' => [],
                ]
            ]
        ],
        'cache' => [
            'use' => 'JetFire\Framework\Providers\CacheProvider',
            'rule' => [
                'shared' => true,
                'call' => [
                    'init' => ['#config.cache', '#setting.environment'],
                ]
            ]
        ],
        'database' => [
            'use' => 'JetFire\Framework\Providers\DbProvider',
            'rule' => [
                'shared' => true,
                'call' => [
                    'appInit' => ['#config.orm', '#setting.db', '#setting.environment'],
                    'provide' => ['#config.system.default'],
                    'setDebugBar' => ['#config.orm.debug_toolbar']
                ],
                'instanceOf' => 'Jet\Services\AppProvider\AppDbProvider'
            ],
            'boot' => true,
        ],
        'template' => [
            'use' => 'JetFire\Framework\Providers\TemplateProvider',
            'rule' => [
                'shared' => true,
                'call' => [
                    'init' => ['#config.template', '#setting.environment'],
                    'setExtensions' => [],
                    'setDebugBar' => ['#config.template.debug_toolbar']
                ]
            ]
        ],
        'request' => [
            'use' => 'JetFire\Framework\System\Request',
            'rule' => [
                'shared' => true,
            ],
        ],
        'mail' => [
            'use' => 'JetFire\Framework\Providers\MailProvider',
            'rule' => [
                'shared' => true,
                'call' => [
                    'initMailer' => ['#config.mail', '#setting.mail.params'],
                    'initMail' => ['#config.mail.class']
                ]
            ],
            'boot' => true,
        ],
        'logger' => [
            'use' => 'JetFire\Framework\Providers\LogProvider',
            'rule' => [
                'shared' => true,
                'call' => [
                    'init' => ['#config.log', '#setting.environment', '#config.system.default'],
                    'setup' => [],
                ]
            ]
        ],
        'cron' => [
            'use' => 'JetFire\Framework\Providers\CronProvider',
            'rule' => [
                'shared' => true,
                'call' => [
                    'init' => []
                ]
            ]
        ],
        'console' => [
            'use' => 'JetFire\Framework\Providers\ConsoleProvider',
            'rule' => [
                'shared' => true,
                'call' => [
                    'init' => ['#config.commands'],
                    'ormCommands' => ['#config.orm.use']
                ]
            ]
        ],
        'event' => [
            'use' => 'JetFire\Framework\Providers\EventProvider',
            'rule' => [
                'shared' => true,
                'call' => [
                    'appInit' => ['#app.settings.event_options']
                ],
                'instanceOf' => 'Jet\Services\AppProvider\AppEventProvider'
            ],
        ],

        //|-----------------------------------|
        //| Your custom providers             |
        //|-----------------------------------|
        'auth' => [
            'use' => 'Jet\AdminBlock\Services\Auth',
            'rule' => [
                'shared' => true,
            ],
        ],
        'translator' => [
            'use' => 'Jet\Services\AppProvider\AppTranslationProvider',
            'rule' => [
                'shared' => true,
                'construct' => ['#app.locales','#config.system.default.locale']
            ],
            'boot' => true,
        ],
        'slugify' => [
            'use' => 'Cocur\Slugify\Slugify',
            'rule' => [
                'shared' => true,
                'substitutions' => ['Cocur\Slugify\RuleProvider\RuleProviderInterface' => null]
            ],
        ],
        'redis' => [
            'use' => 'Jet\Services\CacheProvider\RedisProvider',
            'rule' => [
                'shared' => true,
                'call' => [
                    'init' => ['localhost', 6379]
                ]
            ],
        ],
        'recaptcha' => [
            'use' => 'Jet\Services\Recaptcha',
            'rule' => [
                'shared' => true,
                'construct' => ['#app.settings.recaptcha.public_key', '#app.settings.recaptcha.secret_key']
            ],
        ]
    ],

];