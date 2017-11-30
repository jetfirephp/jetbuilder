<?php

if(!is_file(__DIR__ . '/vendor/autoload.php')){
    echo '<h1 style="text-align: center">Packages installation in progress ...</h1>';
    $whereIsCommand = (PHP_OS == 'WINNT') ? 'where' : 'which';

    if (!is_file(__DIR__ . '/vendor/autoload.php')) {
        if ($whereIsCommand . ` php`) {
            shell_exec('php composer.phar install');
        } else {
            echo '"php" command not found !<br/> Please install php and run the following command in your application root folder: "php composer.phar install"';
            exit;
        }
    }
}

if(!is_file(__DIR__  . '/config/setting.inc.json') && is_file(__DIR__ . '/config/setting.inc.json.sample')){
    echo '<h1 style="text-align: center">Generating configuration file ...</h1>';
    $setting = json_decode(file_get_contents(__DIR__ . '/config/setting.inc.json.sample'), true);
    $setting['environment'] = 'dev';
    $setting['minify'] = false;
    $setting['db']['default']['driver'] = 'mysql';
    file_put_contents(__DIR__ . '/config/setting.inc.json', json_encode($setting));
}

echo '<h1 style="text-align: center">Installation done !</h1>';