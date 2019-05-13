<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require 'Data.php';

http_response_code(200);

switch ($_GET["method"]){
    case 'destinations':
        echo json_decode($destinations);
        break;

    case 'hotels':
        echo json_decode($hotels[$_GET["destination"]]);
        break;

    case 'hotel':
        echo json_decode($hotels[$_GET["destination"]][$_GET["id"]]);
        break;
}