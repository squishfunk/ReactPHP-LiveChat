<?php

use React\Socket\ConnectionInterface;
use React\Socket\Connector;

require 'vendor/autoload.php';

$loop = React\EventLoop\Factory::create();

$connector = new Connector($loop);

$connector->connect('127.0.0.1:8000')->then(
    function (ConnectionInterface $connection) {
        echo 'Połączono';
    },
    function (Exception $error) {
        echo 'Błąd połaczenia';
    }
);

$loop->run();