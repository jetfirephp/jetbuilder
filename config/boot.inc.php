<?php
return [

    'required_files' => [
        ROOT . '/vendor/jetfirephp/framework/src/Helpers/system.php',
        ROOT . '/src/Helpers/app.php',
    ],

    'include_files' => [
        'setting' => ROOT . '/config/setting.inc.json',
        'config' => ROOT . '/config/config.inc.php',
        'app' => ROOT . '/config/app.inc.php',
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
                    'init' => ['#config.session', '#setting.session'],
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
                    'setAppRoutes' => [],
                    'setRouter' => ['#config.router', '#config.template'],
                    'setMiddleware' => ['#config.router.middleware', '#app.middleware'],
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
                    'init' => ['#config.cache', '#setting.cache'],
                ]
            ]
        ],
        'database' => [
            'use' => 'JetFire\Framework\Providers\DbProvider',
            'rule' => [
                'shared' => true,
                'call' => [
                    'init' => ['#config.orm', '#config.system.default.db', '#setting.db', '#app.blocks', '#setting.environment'],
                    'provide' => ['#config.system.default'],
                    'setDebugBar' => ['#config.orm.debug_toolbar']
                ],
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
                    'init' => ['#config.log', '#setting.log', '#config.system.default'],
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
                    'ormCommands' => ['#config.orm.use', '#config.system.default.db']
                ]
            ]
        ],
        'event' => [
            'use' => 'JetFire\Framework\Providers\EventProvider',
            'rule' => [
                'shared' => true,
                'call' => [
                    'init' => ['#app.events', '#app.settings.event_options']
                ]
            ],
        ],
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
        'asset' => [
            'use' => 'Jet\Services\AssetProvider',
            'rule' => [
                'shared' => true,
                'call' => [
                    'init' => ['#config.asset', '#app.assets']
                ]
            ],
        ],
        'minify' => [
            'use' => 'Jet\Services\Minify',
            'rule' => [
                'shared' => true,
                'construct' => ['#config.minify.script_path', '#config.minify.cache_path']
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