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
