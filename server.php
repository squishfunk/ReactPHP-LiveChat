<?php

use React\Socket\ConnectionInterface;
use Squishfunk\LiveChat\ConnectionsHandler;

require 'vendor/autoload.php';

$connectionHandler = new ConnectionsHandler();

$server = new React\Socket\SocketServer('127.0.0.1:8000');
$server->on('connection', function (ConnectionInterface $connection) use ($connectionHandler) {
    echo 'New connection: '. $connection->getRemoteAddress();
    $connectionHandler->handle($connection);
});

