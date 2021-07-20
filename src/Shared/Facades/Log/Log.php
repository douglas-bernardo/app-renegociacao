<?php


namespace App\Shared\Facades\Log;

use Exception;
use Psr\Log\LoggerInterface;

/**
 * Class Log
 * @package App\Facades\Log
 */
final class Log
{

    /**
     * @var LoggerInterface|null
     */
    private static ?LoggerInterface $logger;

    /**
     * Log constructor.
     */
    private function __construct(){}

    /**
     * @throws Exception
     */
    public static function getInstance(): LoggerInterface
    {
        if (empty(self::$logger)) {
            self::$logger = MonologFacade::init();
        }
        return self::$logger;
    }

    /**
     * @param string $message
     * @param array $context
     */
    public static function debug(string $message, array $context = array()): void
    {
        self::$logger->debug($message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public static function info(string $message, array $context = array()): void
    {
        self::$logger->info($message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public static function notice(string $message, array $context = array()): void
    {
        self::$logger->notice($message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public static function warning(string $message, array $context = array()): void
    {
        self::$logger->warning($message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public static function error(string $message, array $context = array()): void
    {
        self::$logger->error($message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public static function critical(string $message, array $context = array()): void
    {
        self::$logger->critical($message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public static function alert(string $message, array $context = array()): void
    {
        self::$logger->alert($message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public static function emergency(string $message, array $context = array()): void
    {
        self::$logger->emergency($message, $context);
    }
}