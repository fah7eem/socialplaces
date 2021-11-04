<?php
namespace System;
use Monolog\Logger;
use Monolog\Handler\DBLogger;

class Utils
{

    protected $logger;
    protected $errorLogger;
    function __construct() {
        $this->logger = new Logger('events');
        $this->logger->pushHandler(new DBLogger("app_logs",Logger::INFO));
        $this->errorLogger = new Logger('errors');
        $this->errorLogger->pushHandler(new DBLogger("app_logs",Logger::ERROR));
    }


    public function clean_inputs($input){
        if($input !== null){
            $input = strip_tags($input);;
            $input = str_replace('\'', '′' ,$input);
            $input = str_replace('"', '″', $input);
        }
        return $input;
    }
}