<?php

return [

    // system configuration
    'system' => [
        'default' => [
            'orm' => 'doctrine',
            'db' => 'default',
            'locale' => 'en_GB'
        ],
    ],

    // orm for database
    'orm' => [
        'use' => ['pdo', 'doctrine'],
        'debug_toolbar' => true,
        'drivers' => [
            'pdo' => 'JetFire\Db\Pdo\PdoModel',
            // composer require gabordemooij/redbean
            'redbean' => 'JetFire\Db\RedBean\RedBeanModel',
            // composer require doctrine/orm
            'doctrine' => [
                'class' => 'JetFire\Db\Doctrine\DoctrineModel',
                'cache' => 'redis_cache',
                'functions' => [],
                'events' => [
                    'listeners' => [],
                    'subscribers' => [
                        '\Jet\Events\ActivityListener'
                    ]
                ]
            ],
        ],
    ],

    // router configuration
    'router' => [
        'use' => ['array'],
        'matcher' => [
            'array' => [
                'class' => 'JetFire\Routing\Matcher\ArrayMatcher',
                'resolver' => [
                    /*'isClosureAndTemplate',*/
                    'isControllerAndTemplate',
                    /*'isClosure',
                    'isController',*/
                    'isTemplate',
                ]
            ],
            'uri' => [
                'class' => 'JetFire\Routing\Matcher\UriMatcher',
                'resolver' => [
                    'isControllerAndTemplate',
                    /*'isController',
                    'isTemplate',*/
                ]
            ]
        ],
        'response' => 'JetFire\Framework\System\Response',
        'redirect' => 'JetFire\Framework\System\Redirect',
        'middleware' => 'JetFire\Routing\Middleware',
        'generateRoutePath' => true,
    ],

    // template engine configuration
    'template' => [
        'use' => 'twig',
        'view' => 'JetFire\Framework\System\View',
        'debug_toolbar' => true,
        'engines' => [
            'php' => [
                'class' => 'JetFire\Template\Php\PhpTemplate',
                'extension' => '.php',
                'functions' => []
            ],
            // composer require twig/twig
            'twig' => [
                'class' => 'JetFire\Template\Twig\TwigTemplate',
                'extension' => '.html.twig',
                'cache' => ROOT . '/var/cache/template/',
                'charset' => 'utf-8',
                'functions' => [
                    'Jet\Extensions\Twig\DefaultExtension',
                    'Jet\Extensions\Twig\FrontExtension',
                    'Jet\Extensions\Twig\TextExtension',
                    'Twig_Extension_StringLoader'
                ]
            ],
            // composer require plates/plates
            'plates' => [
                'class' => 'JetFire\Template\Plates\PlatesTemplate',
                'extension' => '.tpl',
                'functions' => [
                    function () {
                        return new League\Plates\Extension\Asset(ROOT . '/public/', true);
                    },
                    function () {
                        return new League\Plates\Extension\URI($_SERVER['PATH_INFO']);
                    }
                ]
            ],
        ]
    ],

    // mail configuration
    'mail' => [
        'use' => 'swiftmailer',
        'class' => 'JetFire\Framework\System\Mail',
        'mailers' => [
            // composer require phpmailer/phpmailer
            'phpmailer' => [
                'class' => 'JetFire\Mailer\PhpMailer\PhpMailer',
                'debug' => 0,
                'lang' => [
                    'local' => 'en',
                    'path' => ROOT . '/vendor/phpmailer/phpmailer/language/'
                ]
            ],
            // composer require swiftmailer/swiftmailer
            'swiftmailer' => [
                'class' => 'JetFire\Mailer\SwiftMailer\SwiftMailer'
            ],
        ]
    ],

    'commands' => [
        'di' => [
            'JetFire\Framework\Commands\Server',
            'JetFire\Framework\Commands\Route',
            'Jet\Commands\LoadFixtures',
            'Jet\Modules\Ikosoft\Commands\ImportCommand',
        ],
        'new' => [
            'Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand',
            'Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand',
            'Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand',
            'Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand',
            'Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand',
            'Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand'
        ]
    ],

    // cache configuration
    'cache' => [
        'dev' => 'array_cache',
        'prod' => 'redis_cache',
        'drivers' => [
            'array_cache' => [
                'class' => 'Doctrine\Common\Cache\ArrayCache'
            ],
            'file_cache' => [
                'class' => 'Doctrine\Common\Cache\FilesystemCache',
                'args' => [ROOT . '/var/cache/db', '.data']
            ],
            'apc_cache' => [
                'class' => 'Doctrine\Common\Cache\ApcCache'
            ],
            'xcache_cache' => [
                'class' => 'Doctrine\Common\Cache\XcacheCache',
            ],
            'memcache_cache' => [
                'class' => 'Doctrine\Common\Cache\MemcacheCache',
                'callback' => 'memcache'
            ],
            'memcached_cache' => [
                'class' => 'Doctrine\Common\Cache\MemcachedCache',
                'callback' => 'memcached'
            ],
            'redis_cache' => [
                'class' => 'Doctrine\Common\Cache\RedisCache',
                'callback' => 'redis'
            ]
        ],
    ],

    // session configuration
    'session' => [
        'name' => 'jetfire',
        'class' => 'JetFire\Http\Session',
        'dev' => [
            'storage' => 'native_storage',
            'handler' => 'native_handler'
        ],
        'prod' => [
            'storage' => 'native_storage',
            'handler' => 'file_handler'
        ],
        'storages' => [
            'native_storage' => [
                'class' => 'Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage',
                'args' => [
                    'cookie_lifetime' => '0',
                    'gc_divisor' => '100',
                    'gc_probability' => '1',
                    'gc_maxlifetime' => '1440'
                ],
            ],
            'mock_array_storage' => [
                'class' => 'Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage'
            ],
            'mock_file_storage' => [
                'class' => 'Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage'
            ],
        ],
        'handlers' => [
            'native_handler' => [
                'class' => 'Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeSessionHandler',
            ],
            'pdo_handler' => [
                'class' => 'Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler',
                'args' => ['pdo', ['db_table' => 'sessions']]
                /*'args' => ['mysql:host=localhost;dbname=db', ['db_table' => 'sessions','db_username' => 'root','db_password' => '']]*/
            ],
            'file_handler' => [
                'class' => 'Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler',
                'args' => [ROOT . '/var/session'],
            ],
            'memcache_handler' => [
                'class' => 'Symfony\Component\HttpFoundation\Session\Storage\Handler\MemcacheSessionHandler',
                'args' => ['prefix' => 'jet', 'expiretime' => 86400],
                'callback' => 'memcache'
            ],
            'memcached_handler' => [
                'class' => 'Symfony\Component\HttpFoundation\Session\Storage\Handler\MemcachedSessionHandler',
                'args' => ['prefix' => 'jet', 'expiretime' => 86400],
                'callback' => 'memcached',
            ],
            'redis_handler' => [
                'class' => 'Jet\Services\SessionHandler\RedisSessionHandler',
                'args' => [86400, []],
                'callback' => 'redis'
            ]
        ],
    ],

    'error' => [
        'displays' => E_ERROR | E_PARSE | E_WARNING | E_NOTICE
    ],

    'log' => [
        'dev' => [
            'main' => [
                'handlers' => ['console'],
                'processors' => ['introspection_processor']
            ],
            'import' => [
                'handlers' => ['import_handler'],
                'processors' => ['web_processor']
            ],
            'payment' => [
                'handlers' => ['console'],
                'processors' => ['web_processor']
            ]
        ],
        'prod' => [
            'main' => [
                'handlers' => ['file_handler'],
                'processors' => ['web_processor']
            ],
            'import' => [
                'handlers' => ['import_handler'],
                'processors' => ['web_processor']
            ],
            'payment' => [
                'handlers' => ['payment_handler'],
                'processors' => ['web_processor']
            ]
        ],

        'handlers' => [
            'console' => [
                'class' => 'Monolog\Handler\StreamHandler',
                'level' => 'WARNING',
                'stream' => 'php://output',
                'formatter' => 'html'
            ],

            'browser_console' => [
                'class' => 'Monolog\Handler\BrowserConsoleHandler',
                'level' => 'DEBUG'
            ],

            'file_handler' => [
                'class' => 'Monolog\Handler\RotatingFileHandler',
                'level' => 'INFO',
                'max_files' => 30,
                'stream' => ROOT . '/var/log/system.log'
            ],

            'payment_handler' => [
                'class' => 'Monolog\Handler\RotatingFileHandler',
                'level' => 'INFO',
                'max_files' => 30,
                'stream' => ROOT . '/var/log/payment.log'
            ],

            'import_handler' => [
                'class' => 'Monolog\Handler\RotatingFileHandler',
                'level' => 'INFO',
                'max_files' => 30,
                'stream' => ROOT . '/var/log/import.log'
            ],

            'pdo_handler' => [
                'class' => 'JetFire\Framework\Log\PDOHandler',
                'level' => 'INFO',
                'table' => 'logs',
                'fields' => ['user', 'user_id', 'user_level'],
            ],

            'swiftmailer_handler' => [
                'class' => 'Monolog\Handler\SwiftMailerHandler',
                'level' => 'WARNING',
                'to' => 'contact@jetfire.fr',
                'subject' => 'Erreur',
                'from' => 'jetfire@log.com',
                'formatter' => 'html'
            ],

            'native_mail_handler' => [
                'class' => 'Monolog\Handler\NativeMailHandler',
                'level' => 'WARNING',
                'to' => 'contact@jetfire.fr',
                'subject' => 'Erreur',
                'from' => 'jetfire@log.com',
            ]
        ],
        'processors' => [
            'web_processor' => [
                'class' => 'Monolog\Processor\WebProcessor',
            ],
            'introspection_processor' => [
                'class' => 'Monolog\Processor\IntrospectionProcessor',
            ]
        ],
        'formatters' => [
            'html' => [
                'class' => 'Monolog\Formatter\HtmlFormatter',
            ]
        ]
    ],

    'debugger' => [
        'mode' => 'r', // r => rich, p => plain, w => whitespace, c => cli
        'theme' => 'original', // original, solarized, solarized-dark, aante-light
    ],

    'minify' => [
        'script_path' => '/min/static/',
        'cache_path' => '/var/cache/asset'
    ]

];