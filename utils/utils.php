<?php
require_once __DIR__ . '\..\lib\bbdd.php';
require_once __DIR__ . '\..\config\configMail.php';

function isValidEmail($email)
{
    $regex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    return preg_match($regex, $email);
}

// Iniciar session
function compruebaUsuario($user, $pass, $opcion)
{
    $db = conexion();

    // Si es 1 es email, si es 2 es username
    if (compruebaActive($user)) {

        if ($opcion == 1) {
            $sql = "SELECT * FROM users WHERE mail = :user";
        } else {
            $sql = "SELECT * FROM users WHERE username = :user";
        }
        $logged = false;

        try {
            $resultat = $db->prepare($sql);
            $resultat->execute([":user" => $user]);
            $row = $resultat->fetch(PDO::FETCH_ASSOC);

            if ($row && password_verify($pass, $row['passHash'])) {
                $logged = true;
                actualizaLasSignIn($user);
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } finally {
            return $logged;
        }
    } else {
        return false;
    }

}

function actualizaLasSignIn($user)
{
    $db = conexion();
    $sql = "UPDATE users SET lastSignIn = NOW() WHERE username = :user";
    try {
        $resultat = $db->prepare($sql);
        $resultat->execute([":user" => $user]);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        return true;
    }
}

function compruebaActive($user)
{
    $db = conexion();
    $sql = "SELECT * FROM users WHERE (username = :user OR mail = :user) AND active = 1";
    $active = false;

    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':user', $user, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $active = true;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        return $active;
    }
}

// Registrar usuario
function signUp($user, $pass, $mail, $nombre, $apellidos) //?:Hay que poner el campo active en 1
{
    $dbo = conexion();
    $signed = false;
    $randomValue = mt_rand(100000, 999999);
    $sha256Value = hash('sha256', $randomValue);

/*     $sql = "INSERT INTO users (username, passHash, mail, userFirstName, userLastName, creationDate, active)
VALUES (:user, :pass, :mail, :nombre, :apellidos, NOW(), 0)"; */
    $sql = "INSERT INTO users (username, passHash, mail, userFirstName, userLastName, creationDate, removeDate, lastSignIn, active, activationDate, activationCode, resetPassExpiry, resetPassCode)
            VALUES (:user, :pass, :mail, :nombre, :apellidos, NOW(), NULL, NULL, 0, NULL, :activationCode, NULL, NULL)";

    if (!existeMail($mail) && !existeUsername($user)) {
        try {
            $pass = password_hash($pass, PASSWORD_DEFAULT);

            $resultat = $dbo->prepare($sql);

            $resultat->execute([
                ":user" => $user,
                ":pass" => $pass,
                ":mail" => $mail,
                ":nombre" => $nombre,
                ":apellidos" => $apellidos,
                ":activationCode" => $sha256Value,
            ]);

            $rowCount = $resultat->rowCount();
            if ($rowCount > 0) {
                $signed = true;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } finally {
            if ($signed) {
                sendVerificationMail($mail, $user, $randomValue);
            }
            return $signed;
        }
    } else {
        $signed = false;
        return $signed;
    }
}

function existeMail($mail)
{
    $db = conexion();
    $existe = false;

    $sql = "SELECT * FROM users WHERE mail = :mail";

    try {
        $resultat = $db->prepare($sql);
        $resultat->execute([":mail" => $mail]);
        $row = $resultat->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $existe = true;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        return $existe;
    }
}

function existeUsername($username)
{
    $db = conexion();
    $existe = false;

    $sql = "SELECT * FROM users WHERE username = :username";

    try {
        $resultat = $db->prepare($sql);
        $resultat->execute([":username" => $username]);
        $row = $resultat->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $existe = true;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        return $existe;
    }
}

/* function sendVerificationMail($email, $user, $randomValue)
{
$mail = configMail();
// Inicia el contenido HTML
$htmlContent = "<html><body>";
$htmlContent .= "<h1>Hola " . htmlspecialchars($user) . ",</h1>";
$htmlContent .= "<p>Gracias por registrarte. Aquí está tu código de verificación:</p>";
$htmlContent .= "<p style='font-weight: bold; font-size: 24px;'>" . htmlspecialchars($randomValue) . "</p>";
$htmlContent .= "<p>Utiliza este código para completar tu registro.</p>";
$htmlContent .= "</body></html>";
// Define el cuerpo del mensaje (versión HTML)
$mail->Body = html_entity_decode($htmlContent, ENT_HTML5, 'UTF-8');
//$mail->Body = $htmlContent;

// Define el cuerpo alternativo del mensaje (versión de texto sin formato)
$altBody = "Hola " . $user . ",\n\n";
$altBody .= "Gracias por registrarte. Aquí está tu código de verificación:\n";
$altBody .= $randomValue . "\n\n";
$altBody .= "Utiliza este código para completar tu registro.";
$mail->AltBody = $altBody;

$mail->IsHTML(true);
// Añade la dirección del destinatario
$mail->addAddress($email);

// Enviar el correo
if (!$mail->send()) {
echo 'El mensaje no se pudo enviar.';
echo 'Error del mailer: ' . $mail->ErrorInfo;
} else {
echo 'El mensaje ha sido enviado';
}
} */

/* function sendVerificationMail($email, $user, $randomValue)
{
$mail = configMail();

// Inicia el contenido HTML con un diseño simplificado para mejorar la compatibilidad con los clientes de correo electrónico
$htmlContent = "<html><body style='font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; text-align: center;'>";
$htmlContent .= "<div style='text-align: center; font-family: Arial, sans-serif; margin: 20px 0;'>";
$htmlContent .= "<span style='font-size: 24px; color: #ff0000;'>developer</span>";
$htmlContent .= "<span style='font-size: 24px; color: #008000;'>@</span>";
$htmlContent .= "<span style='font-size: 24px; color: #0000ff;'>php:</span>";
$htmlContent .= "<span style='font-size: 24px; color: #ffff00;'>~</span>";
$htmlContent .= "<span style='font-size: 24px; color: #800080;'>$</span>";
$htmlContent .= "<span style='font-size: 24px; font-weight: bold; color: #cccccc; margin: 0 8px;'>ISITEC</span>";
// La animación se omite ya que no es ampliamente soportada en clientes de correo electrónico
$htmlContent .= "</div>";
$htmlContent .= "<h1 style='text-align: center; color: #333;'>Hola " . htmlspecialchars($user) . ",</h1>";
$htmlContent .= "<p style='color: #555;'>Gracias por registrarte. Utiliza el siguiente código de verificación para activar tu cuenta:</p>";
$htmlContent .= "<div style='background-color: #f8f9fa; padding: 20px; margin: 20px auto; text-align: center; border-radius: 5px;'>";
$htmlContent .= "<p style='font-size: 24px; font-weight: bold; color: #4A90E2; letter-spacing: 3px;'>" . htmlspecialchars($randomValue) . "</p>";
$htmlContent .= "</div>";
$htmlContent .= "<p style='color: #555; text-align: center;'>Si no has sido tú, por favor ignora este mensaje.</p>";
$htmlContent .= "</div>";
$htmlContent .= "</body></html>";

// Define el cuerpo del mensaje (versión HTML)
$mail->Body = $htmlContent;

// Define el cuerpo alternativo del mensaje (versión de texto sin formato)
$altBody = "Hola " . $user . ",\n\n";
$altBody .= "Gracias por registrarte. Utiliza el siguiente código de verificación para activar tu cuenta:\n";
$altBody .= $randomValue . "\n\n";
$altBody .= "Si no has sido tú, por favor ignora este mensaje.";
$mail->AltBody = $altBody;

$mail->IsHTML(true);

// Añade la dirección del destinatario
$mail->addAddress($email);

// Enviar el correo
if (!$mail->send()) {
echo 'El mensaje no se pudo enviar.';
echo 'Error del mailer: ' . $mail->ErrorInfo;
} else {
echo 'El mensaje ha sido enviado';
}
} */

/* function sendVerificationMail($email, $user, $randomValue)
{
$mail = configMail();

// Inicia el contenido HTML con un diseño mejorado
$htmlContent = "<html><body style='font-family: Arial, sans-serif; background-color: #e9ecef; padding: 40px;'>";
$htmlContent .= "<div style='max-width: 600px; margin: auto; background-color: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);'>";
$htmlContent .= "<div style='text-align: center;'>";
$htmlContent .= "<h2 style='color: #333;'>ISITEC</h2>";
$htmlContent .= "</div>";
$htmlContent .= "<h1 style='text-align: center; color: #333;'>Hola " . htmlspecialchars($user) . ",</h1>";
$htmlContent .= "<p style='color: #555;'>Gracias por registrarte. Utiliza el siguiente código de verificación para activar tu cuenta:</p>";
$htmlContent .= "<div style='background-color: #f8f9fa; padding: 20px; margin: 20px auto; text-align: center; border-radius: 5px;'>";
$htmlContent .= "<p style='font-size: 24px; font-weight: bold; color: #4A90E2; letter-spacing: 3px;'>" . htmlspecialchars($randomValue) . "</p>";
$htmlContent .= "</div>";
$htmlContent .= "<p style='color: #555; text-align: center;'>Si no has sido tú, por favor ignora este mensaje.</p>";
$htmlContent .= "</div>";
$htmlContent .= "</body></html>";

// Define el cuerpo del mensaje (versión HTML)
$mail->Body = $htmlContent;

// Define el cuerpo alternativo del mensaje (versión de texto sin formato)
$altBody = "Hola " . $user . ",\n\n";
$altBody .= "Gracias por registrarte. Utiliza el siguiente código de verificación para activar tu cuenta:\n";
$altBody .= $randomValue . "\n\n";
$altBody .= "Si no has sido tú, por favor ignora este mensaje.";
$mail->AltBody = $altBody;

$mail->IsHTML(true);

// Añade la dirección del destinatario
$mail->addAddress($email);

// Enviar el correo
if (!$mail->send()) {
echo 'El mensaje no se pudo enviar.';
echo 'Error del mailer: ' . $mail->ErrorInfo;
} else {
echo 'El mensaje ha sido enviado';
}
} */

function sendVerificationMail($email, $user, $randomValue)
{
    $mail = configMail();
    //$verificationUrl = "https://isitec.cat/public/authentication/mailCheckAccount.php?code=" . urlencode($randomValue) . "&mail=" . urlencode($email);
    $verificationUrl = "http://localhost/Isitec/public/authentication/mailCheckAccount.php?code=" . urlencode($randomValue) . "&mail=" . urlencode($email);

    // Inicia el contenido HTML con un diseño mejorado
    $htmlContent = "<html><body style='font-family: Arial, sans-serif; background-color: #e9ecef; padding: 40px; text-align: center;'>";
    $htmlContent .= "<div style='max-width: 600px; margin: auto; background-color: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);'>";

    // Área para el logo de la empresa
    $htmlContent .= "<div style='margin-bottom: 20px;'>";
    $htmlContent .= "<img src='https://isitec.com/path/to/your/logo.png' alt='ISITEC Logo' style='max-width: 100px;'>";
    $htmlContent .= "</div>";

    // Mensaje de bienvenida y código de verificación
    $htmlContent .= "<h1 style='text-align: center; color: #333;'>Hola " . htmlspecialchars($user) . ",</h1>";
    $htmlContent .= "<p style='color: #555;'>Gracias por registrarte. Utiliza el siguiente código de verificación para activar tu cuenta:</p>";
    $htmlContent .= "<div style='background-color: #f8f9fa; padding: 20px; margin: 20px auto; text-align: center; border-radius: 5px;'>";
    $htmlContent .= "<p style='font-size: 24px; font-weight: bold; color: #4A90E2; letter-spacing: 3px;'>" . htmlspecialchars($randomValue) . "</p>";
    $htmlContent .= "</div>";
    $htmlContent .= "<p style='color: #555; text-align: center;'>Si no has sido tú, por favor ignora este mensaje.</p>";

    // Enlace para activar la cuenta
    $htmlContent .= "<div style='text-align: center; margin: 20px;'>";
    $htmlContent .= "<a href='" . $verificationUrl . "' style='background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Activa tu cuenta ahora!</a>";
    $htmlContent .= "</div>";

    $htmlContent .= "</div>";
    $htmlContent .= "</body></html>";

    // Define el cuerpo del mensaje (versión HTML)
    $mail->Body = $htmlContent;

    // Define el cuerpo alternativo del mensaje (versión de texto sin formato)
    $altBody = "Hola " . $user . ",\n\n";
    $altBody .= "Gracias por registrarte. Para activar tu cuenta, por favor visita el siguiente enlace:\n";
    $altBody .= $verificationUrl . "\n\n";
    $altBody .= "Si no has sido tú, por favor ignora este mensaje.";
    $mail->AltBody = $altBody;

    $mail->IsHTML(true);

    // Añade la dirección del destinatario
    $mail->addAddress($email);

    // Enviar el correo
    if (!$mail->send()) {
        echo 'El mensaje no se pudo enviar. Error del mailer: ' . $mail->ErrorInfo;
    } else {
        echo 'El mensaje ha sido enviado';
    }
}

function verifyCode($code, $mail)
{
    $db = conexion();
    $hashedCode = hash('sha256', $code);

    $sql = "SELECT * FROM users WHERE activationCode = :activationCode AND mail = :mail";

    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':activationCode', $hashedCode);
        $stmt->bindParam(':mail', $mail);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "Código verificado con éxito.";
            return true;
        } else {
            echo "Código o email no válido.";
            return false;
        }
    } catch (PDOException $e) {
        echo "Error de la base de datos: " . $e->getMessage();
    }
}

function activateUser($mail)
{
    $db = conexion();

    $sql = "UPDATE users SET active = 1 WHERE mail = :mail AND active = 0";

    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':mail', $mail);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "Usuario activado con éxito.";
        } else {
            echo "No se pudo activar el usuario. Es posible que ya esté activo o que el email no exista.";
        }
    } catch (PDOException $e) {
        echo "Error de la base de datos: " . $e->getMessage();
    }
}
