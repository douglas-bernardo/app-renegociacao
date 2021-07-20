<?php

namespace App\Shared\Infra\Database;

use App\Shared\Errors\ApiException;
use PDO;
use PDOException;

/**
 * Class Connection
 * @package App\Shared\Infra\Database
 */
final class Connection
{
    /**
     * Connection constructor.
     */
    private function __construct(){}

    /**
     * @throws ApiException
     */
    public static function open($name): ?PDO
    {
        try {
            $fileConfig = CONF_PATH_APP_INI . "$name.ini";
            if(!file_exists($fileConfig)){
                throw new ApiException("File $name not found.");
            }

            $db = parse_ini_file($fileConfig);

            $user = $db['user'] ?? NULL;
            $pass = $db['pass'] ?? NULL;
            $name = $db['name'] ?? NULL;
            $host = $db['host'] ?? NULL;
            $type = $db['type'] ?? NULL;
            $port = $db['port'] ?? NULL;

            $conn = null;
            switch ($type) {
                case 'pgsql':
                    $port = $port ?: '5432';
                    $conn = new PDO("pgsql:dbname=$name;user=$user;password=$pass;
                                 host=$host;port=$port");
                    break;

                case 'mysql':
                    $port = $port ?: '3306';
                    $conn = new PDO("mysql:host=$host;port=$port;dbname=$name", $user, $pass);
                    break;

                case 'oracle':
                    $port = $port ?: '1521';
                    $conn = new PDO("oci:dbname=$host:$port/$name;charset=AL32UTF8", $user, $pass);
                    $conn->query("ALTER SESSION SET NLS_DATE_FORMAT = 'YYYY-MM-DD'");
                    break;
            }
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $exception) {
            throw new ApiException($exception->getMessage(), 500);
        }
    }    
}