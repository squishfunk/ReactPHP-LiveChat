<?php

namespace Squishfunk\LiveChat;

use React\Socket\ConnectionInterface;
use SplObjectStorage;

class ConnectionsHandler
{

    protected $connections;

    /**
     * ConnectionCollection constructor.
     * @param $connections
     */
    public function __construct()
    {
        $this->connections = new SplObjectStorage();
    }

    public function handle(ConnectionInterface $connection){
        $this->connections->attach($connection);

        $connection->on('data', function ($data) use ($connection) {
            $this->sendMessegeToOthers($connection, $data);
        });
        $connection->on('close', function() use($connection) {
            $this->sendMessegeToOthers($connection, sprintf('User %s has left the chat', $connection->getRemoteAddress()));
        });
    }

    protected function sendMessegeToOthers(ConnectionInterface $connection, $data){
        foreach($this->connections as $_connection){
            if($_connection !== $connection){
                $_connection->write($data);
            }
        }
    }

}