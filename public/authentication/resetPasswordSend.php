<?php
require_once __DIR__ . '\..\..\utils\utils.php';
session_start();

if (count($_POST) == 1) {
    $mail = isset($_POST["mail"]) ? filter_input(INPUT_POST, 'mail', FILTER_SANITIZE_EMAIL) : '';

    if (existeMail($mail)) {

        $randomValue = mt_rand(100000, 999999);
        $sha256Value = hash('sha256', $randomValue);

        resetPassCode($mail, $sha256Value);

        sendResetPasswordMail($mail, $randomValue);

        $_SESSION['code'] = $code;
        $_SESSION['mail'] = $mail;
        $_SESSION['reset_pass_status_email'] = "success";
        $_SESSION['reset_pass_message_email'] = "Se ha enviado un correo para restablecer tu contraseña.";

    }
    header("Location: ../../index.php");
    exit();

} else if (count($_POST) == 3) {
    $codeURL = isset($_GET["code"]) ? $_GET["code"] : '';
    $mailURL = isset($_GET["mail"]) ? $_GET["mail"] : '';

    $code = isset($_POST["code"]) ? $_POST["code"] : '';
    $password = isset($_POST["password"]) ? $_POST["password"] : '';

    if ($codeURL == $code) {
        if (updatePassUser($mailURL, $password)) {
            $_SESSION['reset_pass_status_email'] = "success";
            $_SESSION['reset_pass_message_email'] = "Contraseña actualitzada";
            header("Location: ../../index.php");
            exit();
        } elseif (!updatePassUser($mailURL, $password)) {
            $_SESSION['reset_pass_status_email'] = "error";
            $_SESSION['reset_pass_message_email'] = "No se ha podido actualizar la contraseña";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperacion de contraseña</title>
    <link rel="icon" href="../../assets/img/ResetPassword.ico" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../assets/css/common.css">
    <link rel="stylesheet" href="../../assets/css/register.css">
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
        <h2 class="text-gray-200 text-center text-2xl font-bold mb-4">Recuperacion de contraseña</h2>
        <div> <span id="error-container"></span></div>
        <form action="" method="post" class="space-y-6" id="form-reset-password" data-te-validation-init>
            <!-- Email -->
            <div class="form-field">
                <input type="text" name="code" id="code" placeholder=" " required
                    class="input-style focus:outline-none focus:border-gray-500">
                <label for="code" class="label-style" id="label-code">Code</label>
                <span class="mt-2 text-sm text-red-500 hidden" id="error-code"></span>
            </div>

            <!-- Password -->
            <div class="form-field">
                <input type="password" name="password" id="pass" placeholder=" " required
                    class="input-style focus:outline-none focus:border-gray-500">
                <label for="pass" class="label-style" id="label-pass">Contraseña</label>
                <span class="mt-2 text-sm text-red-500 hidden" id="error-pass"></span>
            </div>

            <!-- Repeat Password -->
            <div class="form-field">
                <input type="password" name="password2" id="pass2" placeholder=" " required
                    class="input-style focus:outline-none focus:border-gray-500">
                <label for="pass2" class="label-style" id="label-pass2">Confirmar Contraseña</label>
                <span class="mt-2 text-sm text-red-500 hidden" id="error-pass2"></span>
            </div>

            <div class="flex justify-center">
                <input type="submit" value="Enviar" id="resetBtn"
                    class="relative overflow-hidden block w-full py-2 mt-3 text-2xl border-2 border-gray-200 text-gray-200 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent hover:text-green-600 hover:opacity-60">
            </div>
        </form>
    </div>
    <script src="../../assets/js/resetPassword.js"></script>
</body>

</html>
