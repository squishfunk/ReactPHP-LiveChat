<?php

use React\Socket\ConnectionInterface;
use Squishfunk\LiveChat\ConnectionsHandler;

require 'vendor/autoload.php';

$connectionHandler = new ConnectionsHandler();

$address = '127.0.0.1:8000';

$server = new React\Socket\SocketServer($address);

echo "Server started on $address\n";
$server->on('connection', function (ConnectionInterface $connection) use ($connectionHandler) {
    $connectionHandler->handle($connection);
});

