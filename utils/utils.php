<?php
require_once __DIR__ . '\..\lib\bbdd.php';

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
    }else{
        return false;
    }

}


function actualizaLasSignIn($user){
    $db = conexion();
    $sql = "UPDATE users SET lastSignIn = NOW() WHERE username = :user";
    try {
        $resultat = $db->prepare($sql);
        $resultat->execute([":user" => $user]);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } finally{
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

    $sql = "INSERT INTO users (username, passHash, mail, userFirstName, userLastName, creationDate, active)
            VALUES (:user, :pass, :mail, :nombre, :apellidos, NOW(), 1)";

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
            ]);

            $rowCount = $resultat->rowCount();
            if ($rowCount > 0) {
                $signed = true;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } finally {
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
