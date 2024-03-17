<?php

require_once __DIR__ . '\..\..\utils/utils.php';

$cookie = isset($_COOKIE['token']) ? $_COOKIE['token'] : null;

if ($cookie == null) {
    header("Location: ../index.php");
    exit;
}

$userId = getUserIdByUsernameOrEmail($cookie);

$userCourses = obtenerCursosUsuario($userId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cursoId = $_POST['cursoId'] ?? null;
    $titulo = $_POST['tituloLeccion'] ?? null;
    $descripcion = $_POST['descripcionLeccion'] ?? null;
    $videoURL = $_POST['videoURL'] ?? null;
    $resourceZip = null;

    if ($cursoId && $titulo) {
        if (isset($_FILES['resourceZip']) && $_FILES['resourceZip']['error'] === UPLOAD_ERR_OK) {
            $filename = basename($_FILES['resourceZip']['name']);
            $resources_dir = $_SERVER['DOCUMENT_ROOT'] . '/isitec/public/cursos/resources/' . $filename;
            if (move_uploaded_file($_FILES['resourceZip']['tmp_name'], $resources_dir)) {
                $resourceZip = $resources_dir;
            } else {
                // Error al mover el archivo
            }
        }

        $resultado = agregarLeccion($cursoId, $titulo, $descripcion, $videoURL, $resourceZip);

        if ($resultado !== false) {
            // Si el resultado es exitoso, $resultado contiene el ID de la lección insertada
            header("Location: ruta_a_la_pagina_de_confirmacion.php?leccionId=" . $resultado, true, 303);
            exit;
        } else {
            // Manejar el caso de error
        }

    } else {
        // Manejar el error, falta información
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Lecciones a Curso</title>
    <link rel="icon" href="../../assets/img/addLesson.ico" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <link rel="stylesheet" href="../../assets/css/addLesson.css">
    <link rel="stylesheet" href="assets/css/common.css">
</head>

<body class="bg-gray-900 text-white">

    <?php require_once __DIR__ . '\..\..\includes\navBar.php';?>

    <div class="container mx-auto px-4 py-8 flex flex-col items-center justify-center min-h-screen">
        <div class="w-full max-w-4xl mx-auto my-8 md:my-16">
            <h1 class="text-3xl font-bold mb-6 text-center">Añadir Lecciones a tu Curso</h1>

            <div class="flex flex-wrap md:flex-nowrap md:items-start gap-8">
                <!-- Columna izquierda para Dropdown y ZIP -->
                <div class="w-full md:w-1/2">
                    <div class="mb-4">
                        <label for="cursoId" class="font-bold mb-2 block">Cursos</label>
                        <select id="cursoId" name="cursoId"
                            class="bg-gray-800 text-white rounded border border-gray-700 p-2 w-full">
                            <option disabled selected>Selecciona un curso</option>
                            <!-- Aquí se iterará sobre cada curso del usuario -->
                            <?php foreach ($userCourses as $curso): ?>
                            <option value="<?php echo htmlspecialchars($curso['courseId']); ?>">
                                <?php echo htmlspecialchars($curso['title']); ?>
                            </option>
                            <?php endforeach;?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="resourceZip" class="block mb-2 font-bold">Recursos (ZIP)</label>
                        <input type="file" id="resourceZip" name="resourceZip"
                            class="bg-gray-800 text-white rounded border border-gray-700 p-2 w-full">
                    </div>
                </div>
                <!-- Columna derecha para el resto del formulario -->
                <div class="w-full md:w-1/2">
                    <form action="ruta_al_script_que_procesa_el_formulario.php" method="post"
                        enctype="multipart/form-data" class="space-y-4">
                        <div class="mb-4">
                            <label for="tituloLeccion" class="block mb-2 font-bold">Título de la Lección</label>
                            <input type="text" id="tituloLeccion" name="tituloLeccion" required
                                class="bg-gray-800 text-white rounded border border-gray-700 p-2 w-full">
                        </div>

                        <div class="mb-4">
                            <label for="videoURL" class="block mb-2 font-bold">URL del Video</label>
                            <input type="url" id="videoURL" name="videoURL" required
                                class="bg-gray-800 text-white rounded border border-gray-700 p-2 w-full">
                        </div>

                        <div class="mb-4">
                            <label for="descripcionLeccion" class="block mb-2 font-bold">Descripción</label>
                            <textarea id="descripcionLeccion" name="descripcionLeccion"
                                class="textarea-custom-scrollbar bg-gray-800 text-white rounded border border-gray-700 p-2 w-full"></textarea>
                        </div>

                        <button type="submit"
                            class="bg-transparent bg-gray-600 hover:bg-gray-500 rounded-lg px-4 py-2 font-bold border-2 border-gray-600">
                            Añadir Lección
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="../../assets/js/home.js"></script>
</body>

</html>