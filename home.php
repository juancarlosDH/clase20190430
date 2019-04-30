<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title></title>
    </head>
    <body>
        <a href="cerrarSession.php">Salir</a>
        <h1>Bienvenido <?php echo $_SESSION['email']; ?></h1>
        <img src="<?php echo $_SESSION['avatar']; ?>" alt="">
    </body>
</html>
