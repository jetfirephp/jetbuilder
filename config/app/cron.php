<?php
return [
    
    'ModuleUpdateChecker' => [
        'route' => [
            'name' => 'api.module.check.update',
            'subdomain' => 'api'
        ],
        'schedule' => '0 0 * * *',
        'enabled'  => false,
    ],

    'WebsiteExpirationReminder' => [
        'route' => [
            'name' => 'api.website.reminder',
            'subdomain' => 'api'
        ],
        'schedule' => '0 0 * * *',
        'enabled'  => true,
    ],

    'WebsiteDisable' => [
        'route' => [
            'name' => 'api.website.disable',
            'subdomain' => 'api'
        ],
        'schedule' => '0 0 * * *',
        'enabled'  => true,
    ],

    'WebsiteRemove' => [
        'route' => [
            'name' => 'api.website.delete',
            'subdomain' => 'api'
        ],
        'schedule' => '0 0 * * *',
        'enabled'  => false,
    ],

    'IkosoftImporter' => [
        'route' => [
            'name' => 'api.ikosoft.update',
        ],
        'schedule' => '0 0 * * *',
        'enabled'  => false,
    ],

    'CleanLog' => [
        'route' => [
            'name' => 'api.log.clean',
            'subdomain' => 'api'
        ],
        'schedule' => '0 0 01 */3 *',
        'enabled'  => true,
    ],
];