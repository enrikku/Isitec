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
          
        } 
        else if (existeUsername($user)) {
            $errMsg = "El usuario ya existe";
            $signed = false;
        }
        else {
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
    <title>Register</title>
</head>
<body>
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
  <div class="sm:mx-auto sm:w-full sm:max-w-sm">
    <img class="mx-auto h-10 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company">
    <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Registra tu cuenta</h2>
  </div>

  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
  <form class="space-y-6" action="register.php" method="POST">
      <!-- Username  -->
    <div>
        <label for="user" class="block text-sm font-medium leading-6 text-gray-900">Username</label>
        <div class="mt-2">
          <input id="user" name="user" type="text" autocomplete="user" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
        </div>
      </div>
      <!-- Correo -->
      <div>
        <label for="mail" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
        <div class="mt-2">
          <input id="mail" name="mail" type="mail" autocomplete="mail" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
        </div>
      </div>
      <!-- Nombre -->
      <div>
        <label for="nombre" class="block text-sm font-medium leading-6 text-gray-900">Nombre</label>
        <div class="mt-2">
          <input id="nombre" name="nombre" type="text" autocomplete="nombre" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
        </div>
      </div>
      <!-- Apellidos -->
      <div>
        <label for="apellidos" class="block text-sm font-medium leading-6 text-gray-900">Apellidos</label>
        <div class="mt-2">
          <input id="apellidos" name="apellidos" type="text" autocomplete="apellidos" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
        </div>
      </div>
        <!-- Contraseña -->
        <div>
        <label for="pass" class="block text-sm font-medium leading-6 text-gray-900">Contraseña</label>
        <div class="mt-2">
          <input id="pass" name="pass" type="password" autocomplete="pass" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
        </div>
      </div>
      <!-- Contraseña2 -->
      <div>
        <label for="pass2" class="block text-sm font-medium leading-6 text-gray-900">Repetir contraseña</label>
        <div class="mt-2">
          <input id="pass2" name="pass2" type="password" autocomplete="pass2" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
        </div>
      </div>
      <div>
      <?php if (!empty($errMsg)): ?>
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
            <?php echo $errMsg; ?>
        </div>
    <?php endif;?>


      <input type="submit" value="Crear cuenta" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">

      </div>
    </form>
  </div>
</div>
</body>
</html>