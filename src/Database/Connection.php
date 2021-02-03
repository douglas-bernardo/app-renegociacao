<?php

namespace App\Database;

use PDO;
use Exception;

final class Connection
{    
    private function __construc(){}

    public static function open($name){
        
        $fileConfig = __DIR__ . "/../Config/{$name}.ini";
                
        if(file_exists($fileConfig)){
            $db = parse_ini_file($fileConfig);
        }
        else {
            throw new Exception("Arquivo '$name' não encontrado!");
        }

        $user = isset($db['user'])? $db['user'] : NULL;
        $pass = isset($db['pass'])? $db['pass'] : NULL;
        $name = isset($db['name'])? $db['name'] : NULL;
        $host = isset($db['host'])? $db['host'] : NULL;
        $type = isset($db['type'])? $db['type'] : NULL;
        $port = isset($db['port'])? $db['port'] : NULL;

        switch ($type) {
            case 'pgsql':
                $port = $port ? $port : '5432';
                $conn = new PDO("pgsql:dbname={$name};user={$user};password={$pass};
                                 host=$host;port={$port}");
                break;
  
            case 'mysql':
                $port = $port ? $port : '3306';
                $conn = new PDO("mysql:host={$host};port={$port};dbname={$name}", $user, $pass);
                break;
                
            case 'oracle':
                $port = $port ? $port : '1521';
                $conn = new PDO("oci:dbname={$host}:{$port}/{$name};charset=AL32UTF8", $user, $pass);
                $conn->query("ALTER SESSION SET NLS_DATE_FORMAT = 'YYYY-MM-DD'");
                break;
        }
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }    
}