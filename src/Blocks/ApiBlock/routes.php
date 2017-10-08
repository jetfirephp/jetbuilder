<?php

return [

    '/check-update'	=> [
        'use' => 'ModuleController@checkUpdate',
        'name' => 'api.module.check.update'
    ],

    '/website-reminder'	=> [
        'use' => 'WebsiteController@reminder',
        'name' => 'api.website.reminder'
    ],

    '/website-disable'	=> [
        'use' => 'WebsiteController@disable',
        'name' => 'api.website.disable'
    ],

    '/website-delete'	=> [
        'use' => 'WebsiteController@delete',
        'name' => 'api.website.delete'
    ],

    '/log/create' => [
        'use' => 'LogController@create',
        'name' => 'api.log.create',
        'method' => 'POST'
    ],

    '/log/clean' => [
        'use' => 'LogController@clean',
        'name' => 'api.log.clean'
    ],

    '/media/update' => [
        'use' => 'MediaController@update',
        'name' => 'api.media.update',
        'method' => 'POST'
    ],
];