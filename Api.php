<?php

//header("Access-Control-Allow-Origin: *");
//header("Content-Type: application/json; charset=UTF-8");

require 'network/src/rb-sqlite.php';
R::setup( 'sqlite:/tmp/dbfile.db' );

http_response_code(200);

switch ($_GET["method"]){
    case 'login':
        $email = $_GET["email"];
        $password = $_GET["password"];



        $user = R::findOne('user', 'email = ?', [$email]);
        if($user->password == $password){

            setcookie("user", base64_encode($email), time() + (86400 * 30), "/");
            header('Location: ' . '/menu.php');
        }else{
            header('Location: ' . '/index.php?error=Usuario o clave incorrecta');
        }
        break;

    case 'register':
        $email = $_GET["email"];
        $password = $_GET["password"];

        $user = R::findOne('user', 'email = ?', [$email]);
        if($user->password == $password){
            header('Location: ' . '/index.php?error=Usuario ya existe');
        }else{
            $user = R::dispense( 'user' );
            $user->email = $email;
            $user->password = $password;
            $id = R::store( $user );
            setcookie("user", base64_encode($email), time() + (86400 * 30), "/");
            header('Location: ' . '/menu.php');
        }
        break;

    case 'all':
        $a = R::getAll( 'select * from user' );
        print_r($a);
        break;

    case 'all2':
        $a = R::getAll( 'select * from userroom' );
        print_r($a);
        break;


    case 'goroom':
        $room = $_GET['room'];
        $user = R::dispense( 'userroom' );
        $email = base64_decode($_COOKIE["user"]);
        $user->email = $email;
        $user->room = $room;
        $id = R::store( $user );
        setcookie("room", $room, time() + (86400 * 30), "/");
        header('Location: ' . '/world.php');
        break;

    case 'create':
        $r = rand('10000', '99999');
        header('Location: ' . '/Api.php?method=goroom&room=' . $r);
        break;
}