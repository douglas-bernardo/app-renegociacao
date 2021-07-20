<?php

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

/**
 * ###################################
 * ###   Authenticate Config JWT   ###
 * ###################################
 */

function getConfigJWT(): Configuration
{
    return Configuration::forSymmetricSigner(
        new Sha256(),
        InMemory::plainText($_ENV['JWT_SECRET'])
    );
}