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
        
        $connection->write("Welcome on the chat!\n");
        $connection->write("Your username: ");

        $connection->on('data', function ($data) use ($connection) {
        	$username = $this->getConnectionName($connection);
        	if(empty($username)){
        		$name = str_replace(["\n", "\r"], '', $data);
        		$this->setConnectionName($connection, $name);
				$this->sendMessegeToOthers($connection, "New user $name joined the chat!");
        		return;
        	}
        
            $this->sendMessegeToOthers($connection, $username . ': ' . $data);
        });
        
        $connection->on('close', function() use($connection) {
        	$username = $this->getConnectionName($connection);
			$this->connections->offsetUnset($connection);
            $this->sendMessegeToOthers($connection, "User $username has left the chat \n");
        });
    }

    protected function sendMessegeToOthers(ConnectionInterface $connection, $data){
        foreach($this->connections as $_connection){
            if($_connection !== $connection){
                $_connection->write($data);
            }
        }
    }
    
    function setConnectionName(ConnectionInterface $connection, $name)
    {
    	$this->connections->offsetSet($connection, $name);
    }
	
	function getConnectionName(ConnectionInterface $connection)
	{
		return $this->connections->offsetGet($connection);
	}

}