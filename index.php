<?php

define('ROOT', __DIR__);
define('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));

if(is_file($install = ROOT . '/install.php') && !is_file(ROOT . '/vendor/autoload.php')){
    echo '<h1 id="title">Packages installation in progress ...</h1>';
    require $install;
    echo '<script type="text/javascript">document.getElementById("title").outerHTML="";</script>';
}

require ROOT . '/vendor/autoload.php';

$jet = new \Jet\App();
$jet->run();
