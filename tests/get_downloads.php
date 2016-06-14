<?php

require __DIR__ . '/../vendor/autoload.php';

$client = new Rtorrent\Client("https://user:passwd@mydomain.com/RPC");
var_dump($client->getDownloads());
