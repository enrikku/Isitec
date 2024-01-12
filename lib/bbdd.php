<?php
function conexion()
{
    $connString = 'mysql:host=localhost;port=3106;dbname=isitec';
    $user = 'root';
    $pass = '';
    $db = null;

    try {
        $db = new PDO($connString, $user, $pass);
        // echo '<p> Connectats! </p>';
    } catch (PDOException $e) {
        echo '<p style="color:red"> Error' . $e->getMessage() . '</p>';
    } finally {
        return $db;
    }
}

function compruebaUsuario($user, $pass, $opcion)
{
    $db = conexion();

    // Si es 1 es email, si es 2 es username
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
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        return $logged;
    }
}


function sigin($user, $pass, $mail, $nombre, $apellidos){
    $dbo = conexion();
    $signed = false;

    $sql = "INSERT INTO users (username, passHash, mail, userFirstName, userLastName, creationDate) 
                        VALUES (:user, :pass, :mail, :nombre, :apellidos, NOW())";

    if(!existeMail($mail))
    {
        try{

            $pass = password_hash($pass, PASSWORD_DEFAULT);

            $resultat = $dbo->prepare($sql);
            $resultat->execute([":user" => $user, 
                                ":pass" => $pass, 
                                ":mail" => $mail, 
                                ":nombre" => $nombre, 
                                ":apellidos" => $apellidos
                            ]);
            $row = $resultat->fetch(PDO::FETCH_ASSOC);
    
            if ($row) {
                $signed = true;
            }
        }
        catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } finally {
            return $signed;
        }
    }
    else{
        $signed = false;
        return $signed;
    }

    
}

function existeMail($mail){
    $db = conexion();
    $existe = false;

    $sql = "SELECT * FROM users WHERE mail = :mail";

    try{
        $resultat = $db->prepare($sql);
        $resultat->execute([":mail" => $mail]);
        $row = $resultat->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $existe = true;
        }
    }catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }finally {
        return $existe;
    }
}