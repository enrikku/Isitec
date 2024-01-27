<?php
require __DIR__ . '\..\..\lib\bbdd.php';
require_once __DIR__ . '\..\..\utils\utils.php';

if (isset($_GET['code']) && isset($_GET['mail'])) {
    $code = $_GET['code'];
    $mail = $_GET['mail'];

    if (verifyCode($code, $mail)) {
        activateUser($mail);
    }
} else {
    echo "Código o email no proporcionado.";
}
