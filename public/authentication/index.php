<?php

require_once __DIR__ . '\..\..\lib\bbdd.php';
require_once '..\..\utils\utils.php';


$db = conexion();
$logged = false;
$errMsg = "";
$succesRegister = "";

conexion();

session_start();
$registro = isset($_SESSION["registro"]) && $_SESSION["registro"];
$token = isset($_COOKIE["token"]) ? $_COOKIE["token"] : null;

if ($token != null) {
    header("Location: ../home.php");
}

if ($registro) {
    $succesRegister = "Se ha registrado correctamente";
    unset($_SESSION["registro"]);
}

if (count($_POST) == 2) {
    $user = isset($_POST["user"]) ? $_POST["user"] : "";
    $pass = isset($_POST["pass"]) ? $_POST["pass"] : "";

    if (str_contains($user, "@")) {
        $logged = compruebaUsuario($user, $pass, 1);
    } else {
        $logged = compruebaUsuario($user, $pass, 2);
    }

    if ($logged) {
        session_start();
        $_SESSION['token'] = $user;
        setcookie("token", $user, time() + 3600, "/");

        header("Location: ../home.php");
    } else {
        $errMsg = "No es posible iniciar sesión con los datos ingresados"; 
    }
}
//TODO:Una vegada completat amb èxit el registre, caldrà informar de l’èxit de l’operació a la web principal(index.php).

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../assets/css/common.css">
    <link rel="stylesheet" href="../../assets/css/register.css">
</head>

<body class="bg-gray-900 flex flex-col items-center justify-center min-h-screen p-4">

    <section class="my-8 flex justify-center items-center md:flex">
        <span class="text-lg sm:text-xl md:text-2xl lg:text-3xl text-red-500">developer</span>
        <span class="text-lg sm:text-xl md:text-2xl lg:text-3xl text-green-500">@</span>
        <span class="text-lg sm:text-xl md:text-2xl lg:text-3xl text-blue-500">php:</span>
        <span class="text-lg sm:text-xl md:text-2xl lg:text-3xl text-yellow-500">~</span>
        <span class="text-lg sm:text-xl md:text-2xl lg:text-3xl text-purple-500">$</span>
        <span class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-bold mx-2 text-gray-200">ISITEC</span>
        <span class="text-lg sm:text-xl md:text-2xl lg:text-3xl animate-blink text-green-500">|</span>
    </section>


    <div class="form-container p-6 rounded-lg max-w-md mx-auto w-full">
        <h2 class="text-gray-200 text-center text-3xl font-bold mb-6">Iniciar Sesión</h2>
        <form class="space-y-6" action="index.php" method="POST">
            <div class="form-field">
                <input id="user" name="user" type="user" autocomplete="user" required
                    class="input-style focus:outline-none focus:border-gray-500 autocomplete:bg-transparent">
                <label for="user" class="label-style text-sm md:text-base">Usuario o dirección de email</label>
            </div>

            <div class="form-field">
                <input id="pass" name="pass" type="password" autocomplete="current-password" required
                    class="input-style focus:outline-none focus:border-gray-500 autocomplete:bg-transparent">
                <label for="pass" class="label-style">Contraseña</label>
            </div>

            <div class="flex items-center justify-between">
                <a href="#" class="text-sm text-indigo-600 hover:text-indigo-500">¿Olvidaste tu contraseña?</a>
            </div>

            
            <span class="mt-2 text-sm text-green-500" id="succes-register">
                <?= $succesRegister ?>
            </span>

            <span class="mt-2 text-sm text-red-500" id="error-login">
                <?= $errMsg ?>
            </span>

            <div class="flex justify-center">
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md
                    shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none
                    focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Ingresar
                </button>
            </div>
        </form>

        <p class="mt-6 text-center text-sm text-gray-500">
            ¿No tienes cuenta?
            <a href="register.php" class="text-indigo-600 hover:text-indigo-500"> Regístrate</a>
        </p>
    </div>

</body>

</html>
