<?php

$whereIsCommand = (PHP_OS == 'WINNT') ? 'where' : 'which';

if (!is_file(ROOT . '/vendor/autoload.php')) {
    if ($whereIsCommand . ` composer`) {
        shell_exec('composer install');
    } else {
        echo '"composer" command not found !<br/> Please install composer and run the following command in your application root folder: "composer install"';
    }
}

if (!is_dir (ROOT . '/node_modules')) {
    if ($whereIsCommand . ` npm`) {
        shell_exec('npm install');
    } else {
        echo '"npm" command not found !<br/> Please install npm and run the following command in your application root folder: "npm install"';
    }
}