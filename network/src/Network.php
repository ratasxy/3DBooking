<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Network implements MessageComponentInterface {
    protected $clients;
    protected $rooms;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->rooms = array();
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;

        $message = json_decode(base64_decode($msg));

        print_r($message);

        if($message->type == 'login'){
            $room = $message->room;
            $alias = $message->alias;
            if(!isset($this->rooms[$room])){
                $this->rooms[$room] = new \SplObjectStorage;
                echo "Creando sala\n";
            }

            $this->rooms[$room]->attach($from);
            echo "Agregado $alias a la sala $room! ({$from->resourceId})\n";


            return;
        }

        if($message->type == 'move'){
            $room = $message->room;
            $alias = $message->alias;
            if(!isset($this->rooms[$room])){
                $this->rooms[$room] = new \SplObjectStorage;
                echo "Creando sala\n";
            }

            foreach ($this->rooms[$room] as $client) {
                if ($from !== $client) {
                    // The sender is not the receiver, send to each client connected
                    $client->send($msg);
                }

                echo "Enviando movimiento de $alias a la sala\n";
            }

            return;
        }

        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}