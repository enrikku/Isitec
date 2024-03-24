<?php

require_once __DIR__ . '\lib\bbdd.php';
require_once __DIR__ . '\utils\utils.php';
require_once __DIR__ . '\config\configMail.php';
require_once __DIR__ . '\public\authentication\mailCheckAccount.php';

$logged = false;
$errMsg = "";
$successRegister = "";
$ResetPassEmail = "";

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

if (isset($_SESSION['reset_pass_status_email'])) {
    $ResetPassStatus = $_SESSION['reset_pass_status_email'];

    if ($ResetPassStatus == "success") {
        $ResetPassEmail = $_SESSION['reset_pass_message_email'];
        unset($_SESSION['reset_pass_status_email']);
        unset($_SESSION['reset_pass_message_email']);

    } else {
        $errMsg = "No se ha podido enviar el correo para restablecer tu contraseña.";
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
        $_SESSION['token'] = $user;
        setcookie("token", $user, time() + 3600, "/");
        unset($_SESSION['activation_status'], $_SESSION['activation_message']);

        header("Location: public/home.php");
    } else {
        $errMsg = "No es posible iniciar sesión con los datos ingresados";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="icon" href="assets/img/LogIn.ico" type="image/x-icon">
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
        <span class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-bold mx-2 text-orange-500">ISITEC</span>
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
                <a href="#" data-modal-target="authentication-modal" id="open-modal"
                    class="text-sm text-gradient hover:opacity-75">¿Olvidaste tu
                    contraseña?</a>
            </div>

            <span class="mt-2 text-sm text-green-500" id="succes-reset-pass">
                <?=$ResetPassEmail?>
            </span>

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

    <!-- Modal -->
    <div id="authentication-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 flex justify-center items-center">
        <div class="relative p-4 w-full max-w-md">
            <!-- Fondo oscuro semi-transparente -->


            <!-- Contenido del modal -->
            <div class=" relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div
                    class=" flex justify-between items-center p-5 rounded-t border-b dark:border-gray-600 bg-black-200 opacity-75">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        ¿Olvidaste tu contraseña?
                    </h3>
                    <button type="button" id="close-modal" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900
                        rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                        dark:hover:bg-gray-600 dark:hover:text-white close-button animate-close"
                        data-modal-target="authentication-modal">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM7.707 7.293a1 1 0 011.414 0L10 8.586l.879-.879a1 1 0 111.414 1.414L11.414 10l.879.879a1 1 0 01-1.414 1.414L10 11.414l-.879.879a1 1 0 11-1.414-1.414L8.586 10 7.707 9.121a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6">
                    <!-- Formulario del modal -->
                    <form class="space-y-4" method="post" action="public/authentication/resetPasswordSend.php"
                        id="forgot-password-form">
                        <!-- Input de email -->
                        <div class="form-field">
                            <input type="email" name="mail" id="mail" placeholder=" " required
                                class="input-style focus:outline-none focus:border-gray-500">
                            <label for="mail" class="label-style" id="label-mail">Email</label>
                            <span class="mt-2 text-sm text-red-500 hidden" id="error-mail"></span>
                        </div>

                        <button type="submit"
                            class="bg-indigo-600 w-full flex justify-center py-2 px-4 border border-transparent rounded-md
                        shadow-sm text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Enviar
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="assets/js/index.js"></script>
</body>

</html>