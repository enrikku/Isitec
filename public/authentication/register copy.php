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
    <link rel="stylesheet" href="../../assets/css/register.css">

    <title>Register</title>
</head>

<body class="bg-gray-900 flex items-center justify-center h-screen">

    <!-- Card Container -->
    <div class="bg-gray-800 p-6 rounded-lg max-w-sm mx-auto">
        <h2 class="text-green-400 text-center text-2xl font-bold mb-4">Register</h2>
        <!-- Error Message -->
        <!-- Assumed PHP error message here -->

        <!-- Form Start -->
        <form action="register.php" method="post" class="space-y-4">
            <!-- Each form field -->
            <div class="form-field">
                <input type="email" name="mail" id="mail" placeholder=" " required
                    class="block w-full px-4 Py-2 mt-2 text-white bg-transparent rounded-md focus:outline-none">
                <label for="mail" class="pointer-events-none">Email</label>
            </div>
            <div class="form-field">
                <input type="text" name="user" id="user" placeholder=" " required
                    class="block w-full px-4 py-2 mt-2 text-white bg-transparent rounded-md focus:outline-none">
                <label for="user" class="pointer-events-none">Username</label>
            </div>
            <div class="form-field">
                <input type="text" name="nombre" id="nombre" placeholder=" "
                    class="block w-full px-4 py-2 mt-2 text-white bg-transparent rounded-md focus:outline-none">
                <label for="nombre" class="pointer-events-none">Nombre</label>
            </div>
            <div class="form-field">
                <input type="text" name="apellidos" id="apellidos" placeholder=" "
                    class="block w-full px-4 py-2 mt-2 text-white bg-transparent rounded-md focus:outline-none">
                <label for="apellidos" class="pointer-events-none">Apellidos</label>
            </div>
            <div class="form-field">
                <input type="password" name="pass" id="pass" placeholder=" " required
                    class="block w-full px-4 py-2 mt-2 text-white bg-transparent rounded-md focus:outline-none">
                <label for="pass" class="pointer-events-none">Password</label>
            </div>
            <div class="form-field">
                <input type="password" name="pass2" id="pass2" placeholder=" " required
                    class="block w-full px-4 py-2 mt-2 text-white bg-transparent rounded-md focus:outline-none">
                <label for="pass2" class="pointer-events-none">Repeat Password</label>
            </div>
            <input type="submit" value="Register" class="block w-full px-4 py-2 mt-4 text-green-700 hover:text-white border border-green-700
                hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5
                text-center me-2 mb-2 dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600
                dark:focus:ring-green-800 dark:hover:border-green-600 dark:focus:ring-green-800 cursor-pointer">
    </div>

    </form>
    <!-- Form End -->
    </div>
    <!-- Card Container End -->

</body>

</html>
