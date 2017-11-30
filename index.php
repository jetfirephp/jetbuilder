<?php

define('ROOT', __DIR__);
define('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));

require ROOT . '/vendor/autoload.php';

$jet = new \Jet\App();
$jet->run();
