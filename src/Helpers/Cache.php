<?php

// source => https://www.digitalocean.com/community/tutorials/how-to-set-up-redis-as-a-cache-for-mysql-with-php-on-ubuntu-20-04

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->auth('REDIS_PASSWORD');

$key = 'PRODUCTS';

if (!$redis->get($key)) {
    $source = 'MySQL Server';
    $database_name     = 'test_store';
    $database_user     = 'test_user';
    $database_password = 'PASSWORD';
    $mysql_host        = 'localhost';

    $pdo = new PDO('mysql:host=' . $mysql_host . '; dbname=' . $database_name, $database_user, $database_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql  = "SELECT * FROM products";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
       $products[] = $row;
    }

    $redis->set($key, serialize($products));
    $redis->expire($key, 10);

} else {
     $source = 'Redis Server';
     $products = unserialize($redis->get($key));

}

echo $source . ': <br>';
print_r($products);