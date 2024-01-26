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
    $sql = "SELECT * FROM users WHERE username = :user AND active = 1";
    $active = false;

    try {
        $resultat = $db->prepare($sql);
        $resultat->execute([":user" => $user]);
        $row = $resultat->fetch(PDO::FETCH_ASSOC);

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

function sendVerificationMail($email, $user, $randomValue)
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
}
