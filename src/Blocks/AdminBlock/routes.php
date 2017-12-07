<?php

return [

    '/'	=> [
        'use' => 'AdminController@index',
        'name' => 'admin',
        'permission' => false
    ],
    
    '/auth/*' => [
        'use' => 'AuthController@{method}',
        'template' => 'Auth/{template}',
        'method' => ['GET','POST'],
        'ajax' => true,
        'permission' => false
    ],

    '/dashboard/*' => [
        'use' => 'AdminController@{method}',
        'template' => 'Dashboard/{template}',
        'ajax' => true,
    ],

    '/permissions/*' => [
        'use' => 'PermissionController@{method}',
        'template' => 'Permission/{template}',
        'ajax' => true,
    ],

    '/log/*' => [
        'use' => 'LogController@{method}',
        'ajax' => true,
        'permission' => ['read' => 'Read', 'delete' => 'Delete']
    ],

    '/status/*' => [
        'use' => 'StatusController@{method}',
        'ajax' => true,
        'permission' => ['create', 'read', 'update', 'delete']
    ],

    '/media/*' => [
        'use' => 'MediaController@{method}',
        'ajax' => true
    ],

    '/theme/*' => [
        'use' => 'ThemeController@{method}',
        'ajax' => true
    ],
    
    '/module/*' => [
        'use' => 'ModuleController@{method}',
        'ajax' => true
    ],

    '/module-category/*' => [
        'use' => 'ModuleCategoryController@{method}',
        'ajax' => true
    ],

    '/library/*' => [
        'use' => 'LibraryController@{method}',
        'ajax' => true
    ],

    '/template/*' => [
        'use' => 'TemplateController@{method}',
        'ajax' => true
    ],

    '/account/*' => [
        'use' => 'AccountController@{method}',
        'ajax' => true
    ],

    '/address/*' => [
        'use' => 'AddressController@{method}',
        'ajax' => true
    ],
    
    '/website/*' => [
        'use' => 'WebsiteController@{method}',
        'ajax' => true
    ],

    '/route/*' => [
        'use' => 'RouteController@{method}',
        'ajax' => true
    ],

    '/content/*' => [
        'use' => 'ContentController@{method}',
        'ajax' => true
    ],

    '/page/*' => [
        'use' => 'PageController@{method}',
        'ajax' => true
    ],

    '/custom-field/*' => [
        'use' => 'CustomFieldController@{method}',
        'ajax' => true
    ],


];