<?php

define('DS', DIRECTORY_SEPARATOR);
require __DIR__ . DS . 'vendor' .DS . 'autoload.php';

use App\Core\App\AppHttpRequest;
use App\Core\App\AppInitializer;
use App\Core\Routing\Dispatcher;

$app = AppInitializer::getApp();
$request = new AppHttpRequest($app->getServer()->get('REQUEST_URI'));
$dispatcher = new Dispatcher($request, $app);
$dispatcher->runRequest();
