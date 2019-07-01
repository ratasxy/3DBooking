<?php
if (!isset($_COOKIE["user"]))
    header('Location: ' . '/menu.php');
?>

<head>
    <link rel="stylesheet" href="css/materialize.min.css">
    <style>
        .principal {
            height: 600px;
            overflow-y: scroll;
        }
    </style>
    <script src="js/materialize.min.js"></script>
    <script src="js/jquery.js"></script>
</head>

<body>
<div class="container">
    <?php
    require 'network/src/rb-sqlite.php';
    R::setup( 'sqlite:/tmp/dbfile.db' );

    $email = base64_decode($_COOKIE["user"]);

    $rooms = R::find('userroom', 'email = ?', [$email]);
    ?>
    <div class="row">
        <div class="col s5 blue darken-4 white-text center-align principal">
            <h4 class="center blue-text">Tus salas</h4>

            <ul>
                <?php foreach($rooms as $room): ?>
                    <li><a href="Api.php?method=goroom&room=<?php echo $room->room; ?>"><?php echo $room->room; ?></a></li>
                <?php endforeach; ?>
            </ul>

        </div>
        <div class="col s7 blue lighten-5 principal">
            <h4 class="center blue-text">Crear sala</h4>

            <a href="Api.php?method=create">Crear</a>

            <h4 class="center blue-text">Ir a sala</h4>

            <form method="get" action="Api.php">
                <input name="room" type="text" placeholder="2323423">
                <input name="method" type="hidden" value="goroom">

                <input type="submit" value="Entrar">
            </form>
        </div>
    </div>
</div>
</body>

<script>

</script>