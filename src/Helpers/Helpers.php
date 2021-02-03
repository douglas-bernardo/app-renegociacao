<?php

use DateTimeImmutable;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

/**
 * ########################
 * ###   STATEMENTS CM  ###
 * ########################
 */

 /**
  * Lê as consultas CM localizadas na pasta padrão 'resources'
  *
  * @param string $queryName
  * @return string|null
  */
 function file_get_string_sql(string $queryName): ? string
 {    
    $path = __DIR__ . "/../Resources/{$queryName}.sql";
    if(file_exists($path)){
        $sql = file_get_contents($path);
        return $sql;
    } else {
        return null;
    }    
 }

 /**
  * Authenticate Config JWT
  */
function getConfigJWT(): Configuration
{
    $config = Configuration::forSymmetricSigner(
        new Sha256(),
        InMemory::plainText('71dc93fd767339cceb74fcfb2c4c62b7')
    );
    
    return $config;
}
