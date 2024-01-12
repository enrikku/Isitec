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
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>


    <!-- <form action="sigin.php" method="post">
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
    </form> -->
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
  <div class="sm:mx-auto sm:w-full sm:max-w-sm">
    <img class="mx-auto h-10 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company">
    <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Registra tu cuenta</h2>
  </div>

  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
    <form class="space-y-6" action="login.php" method="POST">
      <div>
        <label for="user" class="block text-sm font-medium leading-6 text-gray-900">User/email address</label>
        <div class="mt-2">
          <input id="user" name="user" type="user" autocomplete="user" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
        </div>
      </div>

      <div>
        <label for="nombre" class="block text-sm font-medium leading-6 text-gray-900">Nombre</label>
        <div class="mt-2">
          <input id="nombre" name="nombre" type="nombre" autocomplete="nombre" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
        </div>
      </div>

      <div>
        <div class="flex items-center justify-between">
          <label for="pass" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
          <div class="text-sm">
            <a href="#" class="font-semibold text-indigo-600 hover:text-indigo-500">Forgot password?</a>
          </div>
        </div>
        <div class="mt-2">
          <input id="pass" name="pass" type="password" autocomplete="current-password" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
        </div>
      </div>

      <div>
        <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Sign in</button>
      </div>
    </form>

    <p class="mt-10 text-center text-sm text-gray-500">
      Not a member?
      <a href="#" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">Start a 14 day free trial</a>
    </p>
  </div>
</div>
</body>
</html>