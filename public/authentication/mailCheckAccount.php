<?php
require_once __DIR__ . '\..\..\utils\utils.php';
session_start();

if (isset($_GET['code']) && isset($_GET['mail'])) {
    $code = $_GET['code'];
    $mail = $_GET['mail'];

    if (verifyCode($code, $mail) && activateUser($mail)) {
        $_SESSION['activation_status'] = "success";
        $_SESSION['activation_message'] = "Cuenta activada exitosamente.";
        setcookie("token", $mail, time() + 3600, "/");
        header("Location: ../../index.php");
        exit();
    } elseif (!verifyCode($code, $mail) && !activateUser($mail)) {
        $_SESSION['activation_status'] = "error";
        $_SESSION['activation_message'] = "Código inválido o la cuenta ya está activa.";
    }
}
