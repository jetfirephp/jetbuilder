<?php

date_default_timezone_set('Europe/Paris');
setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
define('ROOT', __DIR__);
require ROOT . '/vendor/autoload.php';

$jet = new \JetFire\Framework\App();
$jet->load(include ROOT . '/config/boot.inc.php');
$jet->boot();

/** @var \JetFire\Routing\RouteCollection $collection */
$collection = $jet->get('routing')->getCollection();
$domain = $jet->data['setting']['domain'] . (isset($jet->data['setting']['public_path']) ? '/' . trim($jet->data['setting']['public_path'], '/') : '');
$collection->generateRoutesPath($domain, 'cron.php');

$cron = $jet->get('cron');
$cron->setStream([
    'http' => [
        'header' =>  "AUTH-KEY: " . $jet->data['app']['settings']['event_options']['AUTH-KEY'] . "\r\n",
    ]
]);
$cron->setCron(include ROOT . '/config/cron.inc.php');
$cron->run();