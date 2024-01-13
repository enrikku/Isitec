<?php
require __DIR__ . '\..\..\lib\bbdd.php';

$db = conexion();
$signed = false;
$validEmail = false;
$equalPass = false;
$errMsg = "";

if (count($_POST) == 6) {
    $user = isset($_POST["user"]) ? filter_input(INPUT_POST, 'user', FILTER_SANITIZE_STRING) : '';
    $nombre = isset($_POST["nombre"]) ? filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING) : '';
    $apellidos = isset($_POST["apellidos"]) ? filter_input(INPUT_POST, 'apellidos', FILTER_SANITIZE_STRING) : '';

    $pass = isset($_POST["pass"]) ? filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING) : '';
    $pass2 = isset($_POST["pass2"]) ? filter_input(INPUT_POST, 'pass2', FILTER_SANITIZE_STRING) : '';

    if ($pass == $pass2) {
        $equalPass = true;
    } else {
        $errMsg = "Las contraseñas no coinciden";
        $equalPass = false;
    }

    $mail = isset($_POST["mail"]) ? filter_input(INPUT_POST, 'mail', FILTER_SANITIZE_EMAIL) : '';

    $regexMail = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

    if (preg_match($regexMail, $mail)) {
        $validEmail = true;
    } else {
        $errMsg .= " El correo electrónico no es válido";
        $validEmail = false;
    }

    if ($validEmail && $equalPass) {
        if (existeMail($mail)) {
            $errMsg = "El correo electrónico ya existe";
            $signed = false;

        } else if (existeUsername($user)) {
            $errMsg = "El usuario ya existe";
            $signed = false;
        } else {
            $signed = signUp($user, $pass, $mail, $nombre, $apellidos);
        }
    } else {
        $signed = false;
    }

    if ($signed) {
        header("Location: ./index.php");
        exit();
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../assets/css/common.css">
    <link rel="stylesheet" href="../../assets/css/register.css">
    <title>Register</title>
</head>

<body class="bg-gray-900 flex items-center justify-center min-h-screen p-4">

    <div class="form-container p-6 rounded-lg max-w-2xl mx-auto w-full">
        <h2 class="text-green-400 text-center text-2xl font-bold mb-4">Registro</h2>

        <!-- Error Message Intentare poner en cada input la validacion y que cambie el borde a rojo etc -->

        <form action="register.php" method="post" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Email -->
                <div class="form-field">
                    <input type="email" name="mail" id="mail" placeholder=" " required class="input-style">
                    <label for="mail" class="label-style">Email</label>
                </div>
                <!-- Username -->
                <div class="form-field">
                    <input type="text" name="user" id="user" placeholder=" " required class="input-style">
                    <label for="user" class="label-style">Usuario</label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombre -->
                <div class="form-field">
                    <input type="text" name="nombre" id="nombre" placeholder=" " class="input-style">
                    <label for="nombre" class="label-style">Nombre</label>
                </div>
                <!-- Apellidos -->
                <div class="form-field">
                    <input type="text" name="apellidos" id="apellidos" placeholder=" " class="input-style">
                    <label for="apellidos" class="label-style">Apellidos</label>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <!-- Password -->
                <div class="form-field">
                    <input type="password" name="pass" id="pass" placeholder=" " required class="input-style">
                    <label for="pass" class="label-style">Contraseña</label>
                </div>

                <!-- Repeat Password -->
                <div class="form-field">
                    <input type="password" name="pass2" id="pass2" placeholder=" " required class="input-style">
                    <label for="pass2" class="label-style">Confirmar Contraseña</label>
                </div>
            </div>

            <!-- Register Button -->
            <!--             <div class="flex justify-center">
                <input type="submit" value="Enviar" id="registerBtn"
                    class="block w-full py-2 mt-3 text-2xl border-2 border-green-700 text-green-700 hover:bg-green-600 hover:text-white
                    rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div> -->
            <!-- Botón de registro con animación -->
            <div class="flex justify-center">
                <input type="submit" value="Enviar" id="registerBtn" class="relative overflow-hidden block w-full py-2 mt-3 text-2xl border-2 border-green-700 text-green-700
                        rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
                        hover:bg-green-600 hover:text-white hover:opacity-60">
            </div>

        </form>
    </div>
</body>

</html>

<div
    style="background-image: url('../../assets/img/fondo_register.webp'); height: 100vh; background-size: cover; background-position: center;">
    <!-- Contenido sobre el fondo con degradado -->
</div>
