<?php

namespace App\Shared\Infra\Database;

use App\Shared\Errors\ApiException;
use PDO;
use Psr\Log\LoggerInterface;

/**
 * Class Transaction
 * @package App\Shared\Infra\Database
 */
final class Transaction
{
    /**
     * @var PDO|null
     */
    private static ?PDO $conn;
    /**
     * @var LoggerInterface|null
     */
    private static ?LoggerInterface $logger;

    /**
     * Transaction constructor.
     */
    private function __construct(){}

    /**
     * @throws ApiException
     */
    public static function open($database)
    {
        if(empty(self::$conn))
        {
            self::$conn = Connection::open($database);
            self::$conn->beginTransaction();
            self::$logger = NULL;
        }
    }

    /**
     * @return PDO
     */
    public static function get(): ?PDO
    {
        return self::$conn;
    }

    /**
     *
     */
    public static function rollback(): void
    {
        if (self::$conn){
            self::$conn->rollback();
            self::$conn = NULL;
        }
    }

    /**
     *
     */
    public static function close(): void
    {
        if (self::$conn){
            self::$conn->commit();
            self::$conn = NULL;
        }
    }

    /**
     * @param LoggerInterface $logger
     */
    public static function setLogger(LoggerInterface $logger): void
    {
        self::$logger = $logger;
    }

    /**
     * @param $message
     */
    public static function log($message)
    {
        if (self::$logger) {
            self::$logger->info($message);
        }
    }
}