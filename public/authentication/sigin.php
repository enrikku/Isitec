<?php
    require __DIR__ . '\..\..\lib\bbdd.php';

    $db = conexion();

    if(count($_POST) == 2){
        
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sigin</title>
</head>

<body>


    <form action="login.php" method="post">

        <label for="user">Username
            <input type="text" name="user" id="user" placeholder="username">
        </label>

        <label for="mail">Email
            <input type="text" name="mail" id="mail" placeholder="email">
        </label>


        <label for="nombre">Primer nombre
            <input type="text" name="nombre" id="nombre" placeholder="Prmer nombre">
        </label>

        <label for="apellidos">Apellidos</label>
            <input type="text" name="apellidos" id="apellidos" placeholder="Apellidos">
        </label>

        <label for="pass">Contraseña:
            <input type="password" name="pass" id="pass" required placeholder="Ej: 9807@abstract">
        </label>

        <label for="pass2">Repite la contraseña:
            <input type="text" name="pass2" id="pass2" required placeholder="Repite la contraseña">
        </label>

        <input type="submit" value="login">
    </form>
</body>
</html>