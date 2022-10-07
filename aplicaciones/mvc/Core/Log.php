<?php

/*
 * https://docs.zendframework.com/zend-log/
 *
 */
namespace Agrodb\Core;

class Log
{

    function __construct($mensaje)
    {
        $this->bd($mensaje);
        // var_dump($mensaje);
    }

    public function bd($mensaje)
    {
        
        $dbconfig = [
            // Sqlite Configuration
            'driver' => 'Pdo',
     'dsn' => 'sqlite:' . MVC . '/Log/log_agrocalidad.db'
        ];
        $db = new \Zend\Db\Adapter\Adapter($dbconfig);
        $mapping = [
            'timestamp' => 'fecha',
            'priority' => 'tipo',
            'message' => 'evento'
        ];
        $writer = new \Zend\Log\Writer\Db($db, 'log_sistema', $mapping);
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
       
        $logger->info($mensaje->getMessage());
        
        if (ENVIRONMENT == 'development' || ENVIRONMENT == 'dev') {
            \ChromePhp::log($mensaje->getMessage());
        }
    }
}
