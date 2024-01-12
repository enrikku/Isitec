<?php
    require __DIR__ . '\..\..\lib\bbdd.php';

    $db = conexion();
    $singed = false;
    $validEmail = false;
    $equalPass = false;
    $errMsg = "";

    if(count($_POST) == 6){
        $user   = isset($_POST["user"]) ? filter_input(INPUT_POST,'user',FILTER_SANITIZE_STRING) : '';
        $nombre = isset($_POST["nombre"]) ? filter_input(INPUT_POST,'nombre',FILTER_SANITIZE_STRING) : '';
        $apellidos = isset($_POST["apellidos"]) ? filter_input(INPUT_POST,'apellidos',FILTER_SANITIZE_STRING) : '';

        $pass   = isset($_POST["pass"]) ? filter_input(INPUT_POST,'pass',FILTER_SANITIZE_STRING) : '';
        $pass2   = isset($_POST["pass2"]) ? filter_input(INPUT_POST,'pass2',FILTER_SANITIZE_STRING) : '';
        
        if($pass == $pass2){
            $equalPass = true;
        }else{
            $errMsg = "Las contraseñas no coinciden";
            $equalPass = false;
        }
     
        $mail = isset($_POST["mail"]) ? $_POST["mail"] : '';
        
        $regexMail = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

        if (preg_match($regexMail, $mail)) {
            $validEmail = true;
        } else {
            $errMsg = "El correo electrónico no es válido";
            $validEmail = false;
        }   
        
        if($validEmail == true && $equalPass == true){
            if(existeMail($mail)){
                $errMsg = "El correo electrónico ya existe";
            }
            
            $signed = sigin($user, $pass, $mail, $nombre, $apellidos);
        }else{
            $signed = false;
        }


        if($singed == true){
           header("Location: ./login.php");
        }
        else{
            
        }
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


    <form action="sigin.php" method="post">
        <label for="user">Username
            <input type="text" name="user" id="user" required placeholder="username">
        </label>
        
        <label for="mail">Email: </label>
            <input type="email" name="mail" id="mail" required placeholder="email">
        

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

        <p><?php echo $errMsg ?></p>
        <input type="submit" value="sigin">
    </form>
</body>
</html>