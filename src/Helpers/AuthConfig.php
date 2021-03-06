<?php

use DateTimeImmutable;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

$now = new DateTimeImmutable();
$config = Configuration::forSymmetricSigner(
    new Sha256(),
    InMemory::plainText($_ENV['JWT_SECRET'])
);

return $config;