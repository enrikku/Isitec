
<?php
function conexion()
{
    $connString = 'mysql:host=localhost;port=3306;dbname=isitec';
    $user = 'root';
    $pass = '0000';
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
        $sql = "SELECT * FROM users WHERE mail = :user AND passHash = :pass";
    } else {
        $sql = "SELECT * FROM users WHERE username = :user AND passHash = :pass";
    }

    $logged = false;

    try {
        $resultat = $db->prepare($sql);
        $resultat->execute([":user" => $user, ":pass" => $pass]);
        $row = $resultat->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $logged = true;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        return $logged;
    }
}