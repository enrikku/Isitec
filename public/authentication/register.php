<?php
require __DIR__ . '\..\..\lib\bbdd.php';
require_once __DIR__ . '\..\..\utils\utils.php';

$db = conexion();
$signed = false;
$validEmail = false;
$equalPass = false;
$errMsgMail = '';
$errMsgUser = '';

if (count($_POST) == 6) {
    $user = isset($_POST["user"]) ? filter_input(INPUT_POST, 'user', FILTER_SANITIZE_STRING) : '';
    $nombre = isset($_POST["nombre"]) ? filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING) : '';
    $apellidos = isset($_POST["apellidos"]) ? filter_input(INPUT_POST, 'apellidos', FILTER_SANITIZE_STRING) : '';

    $pass = isset($_POST["pass"]) ? filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING) : '';
    $pass2 = isset($_POST["pass2"]) ? filter_input(INPUT_POST, 'pass2', FILTER_SANITIZE_STRING) : '';

    $equalPass = ($pass == $pass2);

    $mail = isset($_POST["mail"]) ? filter_input(INPUT_POST, 'mail', FILTER_SANITIZE_EMAIL) : '';

    $validEmail = isValidEmail($mail);

    if ($validEmail && $equalPass) {
        if (existeMail($mail)) {
            $errMsgMail = "El correo electrónico ya existe";
            $signed = false;

        }
        if (existeUsername($user)) {
            $errMsgUser = "El usuario ya existe";
            $signed = false;
        }

        if (!existeMail($mail) && !existeUsername($user)) {
            $signed = signUp($user, $pass, $mail, $nombre, $apellidos);
        }
    } else {
        $signed = false;
    }

    if ($signed) {
        session_start();
        $_SESSION['registro'] = true;
        header("Location: ../../index.php");
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

<body class="bg-gray-900 flex flex-col items-center justify-center min-h-screen p-4">

    <section class="my-8 hidden sm:flex justify-center items-center">
        <span class="text-4xl text-red-500">developer</span>
        <span class="text-4xl text-green-500">@</span>
        <span class="text-4xl text-blue-500">php:</span>
        <span class="text-4xl text-yellow-500">~</span>
        <span class="text-4xl text-purple-500">$</span>
        <span class="text-4xl font-bold mx-2 text-gray-200">ISITEC</span>
        <span class="text-4xl animate-blink text-green-500">|</span>
    </section>

    <div class="form-container p-6 rounded-lg max-w-2xl mx-auto w-full">

        <h2 class="text-gray-200 text-center text-2xl font-bold mb-4">Registro</h2>
        <div> <span id="error-container"></span></div>
        <form action="register.php" method="post" class="... group space-y-6 " id="form-register"
            data-te-validation-init>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Email -->
                <div class="form-field">
                    <input type="email" name="mail" id="mail" placeholder=" " required
                        value="<?php echo isset($_POST['mail']) ? htmlspecialchars($_POST['mail']) : ''; ?>"
                        class="input-style focus:outline-none focus:border-gray-500">
                    <label for="mail" class="label-style" id="label-mail">Email</label>
                    <?php if ($errMsgMail != ""): ?>
                    <span class="mt-2 text-sm text-red-500" id="error-mail"><?=$errMsgMail?></span>
                    <?php endif;?>
                    <span class="mt-2 text-sm text-red-500 hidden" id="error-mail"></span>
                </div>
                <!-- Username -->
                <div class="form-field">
                    <input type="text" name="user" id="user" placeholder=" " required
                        value="<?php echo isset($_POST['user']) ? htmlspecialchars($_POST['user']) : ''; ?>"
                        class="input-style focus:outline-none focus:border-gray-500">
                    <label for="user" class="label-style" id="label-user">Usuario</label>
                    <?php if ($errMsgUser != ""): ?>
                    <span class="mt-2 text-sm text-red-500" id="error-user">
                        <?=$errMsgUser?>
                    </span>
                    <?php endif;?>
                    <span class="mt-2 text-sm text-red-500 hidden" id="error-user"></span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombre -->
                <div class="form-field">
                    <input type="text" name="nombre" id="nombre" placeholder=" "
                        value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>"
                        class="input-style focus:outline-none focus:border-gray-500">
                    <label for="nombre" class="label-style">Nombre</label>
                </div>
                <!-- Apellidos -->
                <div class="form-field">
                    <input type="text" name="apellidos" id="apellidos" placeholder=" "
                        value="<?php echo isset($_POST['apellidos']) ? htmlspecialchars($_POST['apellidos']) : ''; ?>"
                        class="input-style focus:outline-none focus:border-gray-500">
                    <label for="apellidos" class="label-style">Apellidos</label>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <!-- Password -->
                <div class="form-field">
                    <input type="password" name="pass" id="pass" placeholder=" " required
                        class="input-style focus:outline-none focus:border-gray-500">
                    <label for="pass" class="label-style" id="label-pass">
                        Contraseña</label>
                    <span class="mt-2 text-sm text-red-500 hidden" id="error-pass"></span>
                </div>

                <!-- Repeat Password -->
                <div class="form-field">
                    <input type="password" name="pass2" id="pass2" placeholder=" " required
                        class="input-style focus:outline-none focus:border-gray-500">
                    <label for="pass2" class="label-style" id="label-pass2">Confirmar
                        Contraseña</label>
                    <span class="mt-2 text-sm text-red-500 hidden" id="error-pass2"></span>
                </div>

            </div>
            <div class="flex justify-center">
                <input type="submit" value="Enviar" id="registerBtn" class="... group-invalid:pointer-events-none group-invalid:opacity-30 relative overflow-hidden block
                w-full py-2 mt-3 text-2xl border-2 border-gray-200 text-gray-200
                        rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
                        hover:text-green-600 hover:opacity-60">
            </div>

        </form>

    </div>
    <script src="../../assets/js/register.js"></script>
</body>

</html>