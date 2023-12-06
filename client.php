<?php

use React\EventLoop\Loop;
use React\Socket\ConnectionInterface;
use React\Socket\Connector;
use React\Stream\ReadableResourceStream;
use React\Stream\WritableResourceStream;

require 'vendor/autoload.php';

$loop = Loop::get();

$inputStream = new ReadableResourceStream(STDIN, $loop);
$outputStream = new WritableResourceStream(STDOUT, $loop);

$connector = new Connector($loop);

$connector->connect('127.0.0.1:8000')->then(
    function (ConnectionInterface $connection) use ($inputStream, $outputStream) {
    	// Wysyłka lokalnego streamu do serwera. I z serwera do lokala
    	$inputStream->pipe($connection)->pipe($outputStream);
    },
    function (Exception $error) {
        echo 'Błąd połaczenia';
    }
);


$loop->run();