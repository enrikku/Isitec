<?php

require_once __DIR__ . '\lib\bbdd.php';
require_once __DIR__ . '\utils\utils.php';
require_once __DIR__ . '\config\configMail.php';
require_once __DIR__ . '\public\authentication\mailCheckAccount.php';

$logged = false;
$errMsg = "";
$successRegister = "";

$registro = isset($_SESSION["registro"]) && $_SESSION["registro"];
$token = isset($_COOKIE["token"]) ? $_COOKIE["token"] : null;

if ($token != null) {
    header("Location: public/home.php");
}

if ($registro) {
    $successRegister = "Registro exitoso. Revisa tu correo electrónico para activar tu cuenta.";
    unset($_SESSION["registro"]);
}

if (isset($_SESSION['activation_status'])) {
    $ActivationStatus = $_SESSION['activation_status'];
    $ActivationMessage = $_SESSION['activation_message'];
    if ($ActivationStatus == "success") {
        $successRegister = $ActivationMessage;
    } else {
        $errMsg = $ActivationMessage;
    }
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
        $_SESSION['token'] = $user; //TODO:Se deberia buscar el nombre del usuario para que no salga el email al iniciar sesion
        setcookie("token", $user, time() + 3600, "/");
        unset($_SESSION['activation_status'], $_SESSION['activation_message']);

        header("Location: public/home.php");
    } else {
        $errMsg = "No es posible iniciar sesión con los datos ingresados";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/common.css">
    <link rel="stylesheet" href="assets/css/index.css">
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
                <a href="#" class="text-sm text-gradient hover:opacity-75">¿Olvidaste tu contraseña?</a>
            </div>

            <span class="mt-2 text-sm text-green-500" id="succes-register">
                <?=$successRegister?>
            </span>

            <span class="mt-2 text-sm text-red-500" id="error-login">
                <?=$errMsg?>
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
            <a href="public/authentication/register.php" class="text-gradient hover:opacity-75">
                Regístrate
            </a>
        </p>

    </div>

</body>

</html>