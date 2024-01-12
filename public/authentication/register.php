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

        $signed = signUp($user, $pass, $mail, $nombre, $apellidos);
    } else {
        $signed = false;
    }

    if ($singed == true) {
        header("Location: ./index.php");
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
        <form action="register.php" method="post" class="space-y-4 bg-gray-800 p-6 rounded-lg">
            <div>
                <input type="email" name="mail" id="mail" required placeholder="Email"
                    class="block w-full px-4 py-2 mt-2 text-white bg-gray-700 rounded-md focus:border-green-500 hover:border-green-500">
            </div>
            <div>
                <input type="text" name="user" id="user" required placeholder="Username"
                    class="block w-full px-4 py-2 mt-2 text-white bg-gray-700 rounded-md focus:border-green-500 hover:border-green-500">
            </div>
            <div>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre"
                    class="block w-full px-4 py-2 mt-2 text-white bg-gray-700 rounded-md focus:border-green-500 hover:border-green-500">
            </div>
            <div>
                <input type="text" name="apellidos" id="apellidos" placeholder="Apellidos"
                    class="block w-full px-4 py-2 mt-2 text-white bg-gray-700 rounded-md focus:border-green-500 hover:border-green-500">
            </div>
            <div>
                <input type="password" name="pass" id="pass" required placeholder="Password"
                    class="block w-full px-4 py-2 mt-2 text-white bg-gray-700 rounded-md focus:border-green-500 hover:border-green-500">
            </div>
            <div>
                <input type="password" name="pass2" id="pass2" required placeholder="Repeat Password"
                    class="block w-full px-4 py-2 mt-2 text-white bg-gray-700 rounded-md focus:border-green-500 hover:border-green-500">
            </div>
            <div>
                <input type="submit" value="Register"
                    class="block w-full px-4 py-2 mt-4 text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-800 cursor-pointer">
            </div>
        </form>
        <!-- Form End -->
    </div>
    <!-- Card Container End -->

</body>

</html>