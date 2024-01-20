<?php
require 'vendor/autoload.php';

use Ratchet\Http\HttpServer;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

class Chat implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "Nowe połączenie! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Otrzymałem wiadomość od %d: %s' . "\n", $from->resourceId, $msg);

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Połączenie {$conn->resourceId} zostało zamknięte\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Błąd: {$e->getMessage()}\n";

        $conn->close();
    }
}

$server = IoServer::factory(
    new HttpServer(
        new WsServer(new Chat())
    ),
    8000  // zmieniony port
);

echo "Serwer uruchomiony na porcie 8000...\n";

$server->run();
