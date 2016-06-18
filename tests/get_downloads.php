<?php

require __DIR__ . '/../vendor/autoload.php';

$client = new Rtorrent\Client("https://user:passwd@mydomain.com/RPC");
var_dump($client->getAll());
var_dump($client->exists('709C76AD72CD0DDD13A781E0FD27CDAE53668511'));
var_dump($client->get('709C76AD72CD0DDD13A781E0FD27CDAE53668511'));
