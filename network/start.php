<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use MyApp\Network;

require dirname(__DIR__) . '/network/vendor/autoload.php';

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Network()
        )
    ),
    8080
);

$server->run();