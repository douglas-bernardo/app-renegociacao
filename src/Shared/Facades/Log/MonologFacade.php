<?php


namespace App\Shared\Facades\Log;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

final class MonologFacade
{
    /**
     * @throws \Exception
     */
    public static function init(): ? LoggerInterface
    {
        $logger = new Logger(
            $_ENV['APPLICATION']);
        $logger->pushHandler(
            new StreamHandler( CONF_LOG_FILE, Logger::DEBUG)
        );
        return $logger ?? null;
    }
}