<?php

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use System\Migrate;
use Monolog\Logger;
use Monolog\Handler\DBLogger;

$app->get('/migrate/run', function (Request $request, Response $response, $args) {
        try {
            $migrate = new Migrate();
            $migrate->create_table();
            $migrate->run();
            return $response->withStatus(200);
        } catch (Exception $e) {
            $logger = new Logger('migrate');
            $logger->pushHandler(new DBLogger("app_logs", Logger::ERROR));
            $logger->error($e->getMessage());
            return $response->withStatus(500);
        }
});
