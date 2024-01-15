<?php
require __DIR__ . '\..\..\lib\bbdd.php';
//require __DIR__ . '\..\..\utils\utils.php';

$db = conexion();
$signed = false;
$validEmail = false;
$equalPass = false;
$errMsg = "";

$isUsernameValid = true;
$errMsgMail = '';
$errMsgUser = '';
$errMsgPass = '';

if (count($_POST) == 6) {
    $user = isset($_POST["user"]) ? filter_input(INPUT_POST, 'user', FILTER_SANITIZE_STRING) : '';
    $nombre = isset($_POST["nombre"]) ? filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING) : '';
    $apellidos = isset($_POST["apellidos"]) ? filter_input(INPUT_POST, 'apellidos', FILTER_SANITIZE_STRING) : '';

    $pass = isset($_POST["pass"]) ? filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING) : '';
    $pass2 = isset($_POST["pass2"]) ? filter_input(INPUT_POST, 'pass2', FILTER_SANITIZE_STRING) : '';

    //TODO: Creo que se deberia mirar si ha puesto bien la primera con el regex y si la segunda no
    //TODO:coincide, pero si se mira asi sale el error de que las contraseñas no coinciden
    //TODO: RELLENAR LOS CAMPOS DEL FORMULARIO CUANDO SE ENVIA EL FORMULARIO Y TE DEMUESTRA EL ERROR
    //TODO: PONER LOS INPUTS DE CONTRASEÑA A MITAD DE TAMAÑO Y PONER AL LADO UNA LISTA DE CHECKBOX
    //TODO: CON LOS REQUIRIMIENTOS DE LA CONTRASEÑA CON JAVASCRIPT VALIDARLOS Y PASAR DE FALSE A TRUE MIRANDO EL INPUT DE LA CONTRASEÑA
    if ($pass == $pass2) {
        $equalPass = true;
    } else {
        $errMsgPass = "Las contraseñas no coinciden";
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

/*     if (isValidEmail($mail)) {
$validEmail = true;
} else {
$errMsg .= " El correo electrónico no es válido";
$validEmail = false;
} */
// ... Lógica de validación existente ...

    if ($validEmail && $equalPass) {
        if (existeMail($mail)) {
            $errMsgMail = "El correo electrónico ya existe";
            $signed = false;

        } else if (existeUsername($user)) {
            $errMsgUser = "El usuario ya existe";
            $signed = false;
            $isUsernameValid = false;
        } else {
            $signed = signUp($user, $pass, $mail, $nombre, $apellidos);
        }
    } else {
        $signed = false;
    }

    if (!$validEmail) {
        $errMsgMail = "El correo electrónico no es válido";
    }

    if (!$isUsernameValid) {
        $errMsgUser = "El usuario ya existe";
    }

    if (!$equalPass) {
        $errMsgPass = "Las contraseñas no coinciden";
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
        <h2 class="text-gray-200 text-center text-2xl font-bold mb-4">Registro</h2>

        <form action="register.php" method="post" class="... group space-y-6 " id="form-1" data-te-validation-init>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Email -->
                <div class="form-field">
                    <input type="email" name="mail" id="mail" placeholder=" " required class="input-style">
                    <label for="mail" class="label-style">Email</label>
                    <?php if ($errMsgMail): ?>
                    <span class="mt-2 text-sm text-red-500" id="error-mail"><?=$errMsgMail?></span>
                    <?php endif;?>
                </div>
                <!-- Username -->
                <div class="form-field">
                    <input type="text" name="user" id="user" placeholder=" " required
                        class="input-style <?php echo !$isUsernameValid ? 'border-red-500' : ''; ?>">
                    <label for="user" class="label-style">Usuario</label>
                    <?php if ($errMsgUser != ""): ?>
                    <span class="mt-2 text-sm text-red-500" id="error-user">
                        <?=$errMsgUser?>
                    </span>
                    <?php endif;?>
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
                    <input type="password" name="pass" id="pass" placeholder=" " required
                        class="bg-gray-100 border-2 border-gray-300 rounded p-2 text-gray-700">
                    <!-- <?php echo !$equalPass ? 'border-red-500' : ''; ?> -->
                    <label for="pass" class="label-style <?php echo !$equalPass ? 'text-red-500' : ''; ?>">
                        Contraseña</label>
                    <?php if ($errMsgPass != ""): ?>
                    <span class="mt-2 text-sm text-red-500" id="error-pass">
                        <?=$errMsgPass?>
                    </span>
                    <?php endif;?>
                    <span class="mt-2 text-sm text-red-500 hidden" id="error-pass"></span>
                </div>

                <!-- Repeat Password -->
                <div class="form-field">
                    <input type="password" name="pass2" id="pass2" placeholder=" " required
                        class="input-style <?php echo !$signed ? 'border-red-500' : ''; ?>">
                    <label for="pass2" class="label-style">Confirmar Contraseña</label>
                    <?php if ($errMsgPass != ""): ?>
                    <span class="mt-2 text-sm text-red-500" id="error-pass2">
                        <?=$errMsgPass?>
                    </span>
                    <?php endif;?>
                </div>

            </div>
            <div class="flex justify-center">
                <input type="submit" value="Enviar" id="registerBtn" class="... group-invalid:pointer-events-none group-invalid:opacity-30 relative overflow-hidden block w-full py-2 mt-3 text-2xl border-2 border-gray-200 text-gray-200
                        rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
                        hover:text-green-600 hover:opacity-60">
            </div>

        </form>

    </div>
    <script src="../../assets/js/register.js"></script>
</body>

</html>