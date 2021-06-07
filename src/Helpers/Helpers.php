<?php

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

/**
 * ########################
 * ###     APP CONF     ###
 * ########################
 */
define('CONF_DATE_FIN', strtotime("2021-01-31"));



/**
 * ########################
 * ###   STATEMENTS CM  ###
 * ########################
 */

 /**
  * Reads queries located in the default 'resources' folder
  *
  * @param string $queryName
  * @return string|null
  */
 function file_get_string_sql(string $queryName): ? string
 {    
    $path = __DIR__ . "/../Resources/{$queryName}.sql";
    if(file_exists($path)){
        return file_get_contents($path);
    } else return null;
 }

/**
 * ###################################
 * ###   Authenticate Config JWT   ###
 * ###################################
 */

function getConfigJWT(): Configuration
{
    return Configuration::forSymmetricSigner(
        new Sha256(),
        InMemory::plainText('71dc93fd767339cceb74fcfb2c4c62b7')
    );
}

/**
 * ##################
 * ###   STRING   ###
 * ##################
 */

function str_format_currency(string $value): string
{
    $source = array('.', ',');
    $replace = array('', '.');
    return str_replace($source, $replace, $value);
}
