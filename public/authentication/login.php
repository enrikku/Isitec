<?php

require __DIR__ . '\..\..\lib\bbdd.php';

$db = conexion();
$logged = false;


if(count($_POST) == 2){
    $user   = isset($_POST["user"]) ? $_POST["user"] : "";
    $pass   = isset($_POST["pass"]) ? $_POST["pass"] : "";
    
    if(str_contains($user, "@")){
        $logged = compruebaUsuario($user, $pass, 1);
    }
    else{
        $logged = compruebaUsuario($user, $pass, 2);
    }
    
    if($logged){
        echo "Login echo";
    }
    else{
        echo "Login no hecho";
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="login.php" method="post">
        <input type="text" name="user">
        <input type="password" name="pass">
        <input type="submit" value="login">
    </form>
</body>
</html>