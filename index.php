<?php
if (isset($_COOKIE["user"]))
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
    if(isset($_GET['error']))
        echo $_GET['error'];
    ?>
    <div class="row">
        <div class="col s5 blue darken-4 white-text center-align principal">
            <h4 class="center blue-text">Login</h4>

            <form method="get" action="Api.php">
                <input name="email" type="text" placeholder="email@dominio.com">
                <input name="password" type="password" placeholder="password">
                <input name="method" type="hidden" value="login">

                <input type="submit" value="Ingresar">
            </form>

        </div>
        <div class="col s7 blue lighten-5 principal">
            <h4 class="center blue-text">Register</h4>

            <form method="get" action="Api.php">
                <input name="email" type="text" placeholder="email@dominio.com">
                <input name="password" type="password" placeholder="password">
                <input name="method" type="hidden" value="register">

                <input type="submit" value="Registrarse">
            </form>
        </div>
    </div>
</div>
</body>

<script>

</script>