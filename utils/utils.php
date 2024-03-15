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
    //$verificationUrl = "https://isitec.cat/public/authentication/mailCheckAccount.php?code=" . urlencode($randomValue) . "&mail=" . urlencode($email);
    //$verificationUrl = "https://www.isitec.cat/public/authentication/mailCheckAccount.php?code=" . urlencode($randomValue) . "&mail=" . urlencode($email);
    $verificationUrl = "localhost/isitec/public/authentication/mailCheckAccount.php?code=" . urlencode($randomValue) . "&mail=" . urlencode($email);

    // Inicia el contenido HTML con un diseño mejorado
    $htmlContent = "<html><body style='font-family: JetBrains Mono, monospace; background-color: #e9ecef; padding: 40px; text-align: center;'>";

    //$htmlContent = "<html><body style='font-family: JetBrains Mono, monospace; background-image: url(https://i.postimg.cc/X70q4WXW/fondo-register.png); text-align: center;'>";

    $htmlContent .= "<div style='max-width: 600px; margin: auto; background-color: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);'>";

    // Área para el logo de la empresa
    $htmlContent .= "<div style='margin-bottom: 20px;'>";
    //$htmlContent .= "<img src='https://isitec.com/path/to/your/logo.png' alt='ISITEC Logo' style='max-width: 100px;'>";
    //$htmlContent .= "<img src='https://i.postimg.cc/qRZ7GGqk/logo.png' alt='ISITEC Logo' style='max-width: 100px;'>";
    $htmlContent .= "<img src='https://i.postimg.cc/fRQzzkDZ/logo.png' alt='ISITEC Logo' style='width: 400px; height: auto;'>";

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
    $htmlContent .= "<a href='" . $verificationUrl . "' style='background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Activar!</a>";
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

function sendResetPasswordMail($email, $code)
{
    $mail = configMail();
    $verificationUrl = "localhost/isitec/public/authentication/mailCheckAccount.php?code=" . urlencode($code) . "&mail=" . urlencode($email);
    //$verificationUrl = "https://www.isitec.cat/public/authentication/resetPasswordSend.php?code=" . urlencode($code) . "&mail=" . urlencode($email);

// Inicia el contenido HTML con un diseño mejorado
    $htmlContent = "<html><body style='font-family: JetBrains Mono, monospace; background-color: #e9ecef; padding: 40px; text-align: center;'>";

//$htmlContent = "<html><body style='font-family: JetBrains Mono, monospace; background-image: url(https://i.postimg.cc/X70q4WXW/fondo-register.png); text-align: center;'>";

    $htmlContent .= "<div style='max-width: 600px; margin: auto; background-color: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);'>";

// Área para el logo de la empresa
    $htmlContent .= "<div style='margin-bottom: 20px;'>";
//$htmlContent .= "<img src='https://isitec.com/path/to/your/logo.png' alt='ISITEC Logo' style='max-width: 100px;'>";
//$htmlContent .= "<img src='https://i.postimg.cc/qRZ7GGqk/logo.png' alt='ISITEC Logo' style='max-width: 100px;'>";
    $htmlContent .= "<img src='https://i.postimg.cc/fRQzzkDZ/logo.png' alt='ISITEC Logo' style='width: 400px; height: auto;'>";

    $htmlContent .= "</div>";

// Mensaje de bienvenida y código de verificación
    $htmlContent .= "<h1 style='text-align: center; color: #333;'>Hola " . htmlspecialchars($email) . ",</h1>";
    $htmlContent .= "<p style='color: #555;'>Recibimos tu solicitud, por favor da clic en el siguiente enlace para restablecer tu contraseña, tiene 30 minutos desde su solicitud.</p>";
    $htmlContent .= "<div style='background-color: #f8f9fa; padding: 20px; margin: 20px auto; text-align: center; border-radius: 5px;'>";
    $htmlContent .= "<p style='font-size: 24px; font-weight: bold; color: #4A90E2; letter-spacing: 3px;'>" . htmlspecialchars($code) . "</p>";
    $htmlContent .= "</div>";
    $htmlContent .= "<p style='color: #555; text-align: center;'>Si no has sido tú, por favor ignora este mensaje.</p>";

// Enlace para activar la cuenta
    $htmlContent .= "<div style='text-align: center; margin: 20px;'>";
    $htmlContent .= "<a href='" . $verificationUrl . "' style='background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Recuperar!</a>";
    $htmlContent .= "</div>";

    $htmlContent .= "</div>";
    $htmlContent .= "</body></html>";

// Define el cuerpo del mensaje (versión HTML)
    $mail->Body = $htmlContent;

// Define el cuerpo alternativo del mensaje (versión de texto sin formato)
    $altBody = "Hola " . $email . ",\n\n";
    $altBody .= "Recibimos tu solicitud, por favor da clic en el siguiente enlace para restablecer tu contraseña, tiene 30 minutos desde su solicitud.\n";
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
        $successEmailResetPass = "Email enviado, revisa tu correo";
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
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        echo "Error de la base de datos: " . $e->getMessage();
    }
}

function activateUser($mail)
{
    $db = conexion();

    $sql = "UPDATE users
            SET active = 1, activationCode = NULL, activationDate = NOW()
            WHERE mail = :mail AND active = 0";

    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':mail', $mail);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        echo "Error de la base de datos: " . $e->getMessage();
    }
}

function resetPassCode($mail, $code)
{
    $db = conexion();

    $sql = "UPDATE users
            SET resetPassExpiry = NOW() + INTERVAL 30 MINUTE, resetPassCode = :code
            WHERE mail = :mail AND active = 1";

    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':mail', $mail);
        $stmt->bindParam(':code', $code);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        echo "Error de la base de datos: " . $e->getMessage();
    }

}

function updatePassUser($mailURL, $password)
{
    $db = conexion();

    $passHash = password_hash($password, PASSWORD_DEFAULT);

    // $sql = "UPDATE users
    //     SET passHash = :passHash, resetPassExpiry = NULL, resetPassCode = NULL
    //     WHERE mail = :mailURL AND (resetPassExpiry > (NOW() - INTERVAL 30 MINUTE)) AND active = 1";

    $sql = "UPDATE users
        SET passHash = :passHash, resetPassExpiry = NULL, resetPassCode = NULL
        WHERE mail = :mailURL AND (minute(resetPassExpiry)-minute(NOW()) < 30) AND active = 1";

    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':passHash', $passHash);
        $stmt->bindParam(':mailURL', $mailURL);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        echo "Error de la base de datos: " . $e->getMessage();
    }

}

function obtenerTags()
{

    $db = conexion();
    $tags = [];

    $sql = "SELECT * FROM tags";

    try {
        $stmt = $db->prepare($sql);
        $stmt->execute();

        $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "Error de la base de datos: " . $e->getMessage();
    }

    return $tags;
}

function guardarPath($path)
{
    $db = conexion();

    $sql = "UPDATE courses
        SET passHash = :passHash, resetPassExpiry = NULL, resetPassCode = NULL
        WHERE mail = :mailURL AND (minute(resetPassExpiry)-minute(NOW()) < 30) AND active = 1";

    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':passHash', $passHash);
        $stmt->bindParam(':mailURL', $mailURL);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        echo "Error de la base de datos: " . $e->getMessage();
    }
}

function guardarCurso($userId, $title, $description, $coverURL)
{
    $db = conexion();

    try {
        $stmt = $db->prepare("INSERT INTO courses (userId, title, description, publishDate, coverURL) VALUES (:userId, :title, :description, now(), :coverURL)");

        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':coverURL', $coverURL);
        $stmt->execute();

        return $db->lastInsertId();
    } catch (PDOException $e) {
        die("Error al guardar el curso: " . $e->getMessage());
    }
}

function guardarTagsDelCurso($courseId, $selectedTags)
{
    $db = conexion();

    try {
        $stmt = $db->prepare("INSERT INTO CourseTags (courseId, tagId) VALUES (:courseId, :tagId)");

        foreach ($selectedTags as $tagId) {
            $stmt->bindParam(':courseId', $courseId);
            $stmt->bindParam(':tagId', $tagId);
            $stmt->execute();
        }
        return true;
    } catch (PDOException $e) {
        die("Error al guardar los tags del curso: " . $e->getMessage());
    }
}

function insertVideoLink($courseId, $videoURL)
{
    $db = conexion();

    try {
        $stmt = $db->prepare("INSERT INTO videos (courseId, videoURL) VALUES (:courseId, :videoURL)");
        $stmt->bindParam(':courseId', $courseId);
        $stmt->bindParam(':videoURL', $videoURL);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        die("Error al insertar el vídeo: " . $e->getMessage());
    }
}

function getUserIdByUsernameOrEmail($value)
{
    $db = conexion();
    $isEmail = strpos($value, '@') !== false;
    $column = $isEmail ? 'mail' : 'username';

    try {
        $stmt = $db->prepare("SELECT iduser FROM users WHERE {$column} = :value");
        $stmt->bindParam(':value', $value);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? $user['iduser'] : null;
    } catch (PDOException $e) {
        die("Error al obtener el userID: " . $e->getMessage());
    }
}

function obtenerCursos()
{
    $db = conexion();

    // Obtener los cursos
    $stmtCursos = $db->query("SELECT * FROM courses");
    $cursos = $stmtCursos->fetchAll(PDO::FETCH_ASSOC);

    // Para cada curso, obtener los vídeos, tags y votos
    foreach ($cursos as $index => $curso) {
        // Obtener los vídeos
        $stmtVideos = $db->prepare("SELECT * FROM videos WHERE courseId = ?");
        $stmtVideos->execute([$curso['courseId']]);
        $cursos[$index]['videos'] = $stmtVideos->fetchAll(PDO::FETCH_ASSOC);

        // Obtener los tags
        $stmtTags = $db->prepare("SELECT t.* FROM tags t JOIN coursetags ct ON t.tagId = ct.tagId WHERE ct.courseId = ?");
        $stmtTags->execute([$curso['courseId']]);
        $cursos[$index]['tags'] = $stmtTags->fetchAll(PDO::FETCH_ASSOC);

        // Obtener los votos
        $stmtVotos = $db->prepare("SELECT likes, dislikes FROM votes WHERE courseId = ?");
        $stmtVotos->execute([$curso['courseId']]);
        $votos = $stmtVotos->fetch(PDO::FETCH_ASSOC);
        $cursos[$index]['votos'] = $votos ? $votos : ['likes' => 0, 'dislikes' => 0];
    }

    return $cursos;
}

function obtenerCursosByTag($search_query)
{
    $db = conexion();

    // Obtener los cursos
    $stmtCursos = $db->query("SELECT c.* , t.tag FROM isitec.courses c
	inner join isitec.coursetags ct on c.courseId = ct.courseId
    inner join isitec.tags t on ct.tagId = t.tagId
    where t.tag LIKE '%{$search_query}%'");
    $cursos = $stmtCursos->fetchAll(PDO::FETCH_ASSOC);

    // Para cada curso, obtener los vídeos, tags y votos
    foreach ($cursos as $index => $curso) {
        // Obtener los vídeos
        $stmtVideos = $db->prepare("SELECT * FROM videos WHERE courseId = ?");
        $stmtVideos->execute([$curso['courseId']]);
        $cursos[$index]['videos'] = $stmtVideos->fetchAll(PDO::FETCH_ASSOC);

        // Obtener los tags
        $stmtTags = $db->prepare("SELECT t.* FROM tags t JOIN coursetags ct ON t.tagId = ct.tagId WHERE ct.courseId = ?");
        $stmtTags->execute([$curso['courseId']]);
        $cursos[$index]['tags'] = $stmtTags->fetchAll(PDO::FETCH_ASSOC);

        // Obtener los votos
        $stmtVotos = $db->prepare("SELECT likes, dislikes FROM votes WHERE courseId = ?");
        $stmtVotos->execute([$curso['courseId']]);
        $votos = $stmtVotos->fetch(PDO::FETCH_ASSOC);
        $cursos[$index]['votos'] = $votos ? $votos : ['likes' => 0, 'dislikes' => 0];
    }

    return $cursos;
}

function obtenerCurso($courseid)
{
    $db = conexion();

    // Obtener el curso específico
    $stmtCurso = $db->prepare("SELECT * FROM courses WHERE courseId = ?");
    $stmtCurso->execute([$courseid]);
    $curso = $stmtCurso->fetch(PDO::FETCH_ASSOC);

    if (!$curso) {
        return null; // Retorna null si el curso no se encuentra
    }

    // Obtener los vídeos del curso
    $stmtVideos = $db->prepare("SELECT * FROM videos WHERE courseId = ?");
    $stmtVideos->execute([$courseid]);
    $curso['videos'] = $stmtVideos->fetchAll(PDO::FETCH_ASSOC);

    // Obtener los tags del curso
    $stmtTags = $db->prepare("SELECT t.* FROM tags t JOIN coursetags ct ON t.tagId = ct.tagId WHERE ct.courseId = ?");
    $stmtTags->execute([$courseid]);
    $curso['tags'] = $stmtTags->fetchAll(PDO::FETCH_ASSOC);

    // Obtener los votos del curso
    $stmtVotos = $db->prepare("SELECT likes, dislikes FROM votes WHERE courseId = ?");
    $stmtVotos->execute([$courseid]);
    $votos = $stmtVotos->fetch(PDO::FETCH_ASSOC);
    $curso['votos'] = $votos ? $votos : ['likes' => 0, 'dislikes' => 0];

    return $curso;
}



function getLikesCourse($courseId)
{
    $db = conexion();
    $likes = 0;
    $sql = "SELECT likes FROM votes WHERE courseId = :courseId";

    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT); // Vinculamos el parámetro courseId
        $stmt->execute();

        $likes = $stmt->fetchColumn(0);

    } catch (PDOException $e) {
        echo "Error de la base de datos: " . $e->getMessage();
    }

    return $likes;
}

function guardarLike($courseId)
{
    $db = conexion();

    $likes = getLikesCourse($courseId);

    $likes ++;

    try {
        $stmt = $db->prepare("UPDATE votes SET likes = :likes WHERE courseId = :courseId");
        $stmt->bindParam(':courseId', $courseId);
        $stmt->bindParam(':likes', $likes);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        die("Error al dar like al video: " . $e->getMessage());
    }
}


function getDislikesCourse($courseId)
{
    $db = conexion();
    $likes = 0;
    $sql = "SELECT dislikes FROM votes WHERE courseId = :courseId";

    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT); // Vinculamos el parámetro courseId
        $stmt->execute();

        $likes = $stmt->fetchColumn(0);

    } catch (PDOException $e) {
        echo "Error de la base de datos: " . $e->getMessage();
    }

    return $likes;
}

function guardarDislike($courseId)
{
    $db = conexion();

    $dislikes = getDislikesCourse($courseId);

    $dislikes ++;

    try {
        $stmt = $db->prepare("UPDATE votes SET dislikes = :dislikes WHERE courseId = :courseId");
        $stmt->bindParam(':courseId', $courseId);
        $stmt->bindParam(':dislikes', $dislikes);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        die("Error al dar like al video: " . $e->getMessage());
    }
}


function getCourseById()
{

}