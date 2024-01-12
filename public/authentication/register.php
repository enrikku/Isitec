<?php
require __DIR__ . '\..\..\lib\bbdd.php';

$db = conexion();
$singed = false;
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

    $mail = isset($_POST["mail"]) ? $_POST["mail"] : '';

    $regexMail = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

    if (preg_match($regexMail, $mail)) {
        $validEmail = true;
    } else {
        $errMsg = "El correo electrónico no es válido";
        $validEmail = false;
    }

    if ($validEmail == true && $equalPass == true) {
        if (existeMail($mail)) {
            $errMsg = "El correo electrónico ya existe";
        }

        $signed = sigin($user, $pass, $mail, $nombre, $apellidos);
    } else {
        $signed = false;
    }

    if ($singed == true) {
        header("Location: ./login.php");
    } else {

    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Register</title>
</head>

<body class="bg-gray-900 flex items-center justify-center h-screen">

    <!-- Card Container -->
    <div class="bg-gray-800 p-6 rounded-lg max-w-sm mx-auto">
        <h2 class="text-green-400 text-center text-2xl font-bold mb-4">Register</h2>

        <!-- Error Message -->
        <?php if ($errMsg != ""): ?>
        <p class="bg-red-500 text-white p-2 rounded"><?php echo $errMsg; ?></p>
        <?php endif;?>

        <!-- Form Start -->
        <form action="sigin.php" method="post" class="space-y-4">
            <div>
                <input type="email" name="mail" id="mail" required placeholder="Email"
                    class="w-full px-4 py-2 rounded-md bg-gray-700 text-white placeholder-gray-300">
            </div>
            <div>
                <input type="text" name="user" id="user" required placeholder="Username"
                    class="w-full px-4 py-2 rounded-md bg-gray-700 text-white placeholder-gray-300">
            </div>
            <div>
                <input type="text" name="nombre" id="nombre" placeholder="First Name"
                    class="w-full px-4 py-2 rounded-md bg-gray-700 text-white placeholder-gray-300">
            </div>
            <div>
                <input type="text" name="apellidos" id="apellidos" placeholder="Last Name"
                    class="w-full px-4 py-2 rounded-md bg-gray-700 text-white placeholder-gray-300">
            </div>
            <div>
                <input type="password" name="pass" id="pass" required placeholder="Password"
                    class="w-full px-4 py-2 rounded-md bg-gray-700 text-white placeholder-gray-300">
            </div>
            <div>
                <input type="password" name="pass2" id="pass2" required placeholder="Repeat Password"
                    class="w-full px-4 py-2 rounded-md bg-gray-700 text-white placeholder-gray-300">
            </div>
            <div>
                <input type="submit" value="Register"
                    class="w-full py-2 rounded-md bg-green-500 hover:bg-green-600 text-white cursor-pointer">
            </div>
        </form>
        <!-- Form End -->
    </div>
    <!-- Card Container End -->

</body>

</html>