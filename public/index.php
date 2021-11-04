<?php

use DI\Container;
use Slim\Factory\AppFactory;
use Monolog\Logger;
use Monolog\Handler\DBLogger;

require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/initialize/startup.php';

$container = new Container();
AppFactory::setContainer($container);
$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$log = new Logger('system');
$log->pushHandler(new DBLogger("app_logs",Logger::WARNING));

$errorMiddleware = $app->addErrorMiddleware(SHOW_ERRORS, true, true, $log);
require_once __DIR__ . '/../app/initialize/routes.php';
require_once __DIR__ . '/../app/initialize/containers.php';
$app->run();
