<?php

require_once __DIR__ . '\..\..\utils/utils.php';

$tags = obtenerTags();

$htmlTags = "";
foreach ($tags as $tag => $i) {
    $htmlTags .= "<li>";
    $htmlTags .= "<div class='flex items-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600'>";
    $htmlTags .= "<input id='checkbox-item-4' name='tags[]' type='checkbox' value='" . $i['tagId'] . "' class='w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500'>";
    $htmlTags .= "<label for='checkbox-item-4' class='w-full ms-2 text-sm font-medium text-gray-900 rounded dark:text-gray-300'>" . ucfirst($i['tag']) . "</label>";
    $htmlTags .= "</div>";
    $htmlTags .= "</li>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tags'])) {
        $selectedTags = $_POST['tags']; // Esto será un arreglo con los valores de los tags seleccionados
    } else {
        echo "No se seleccionaron tags.";
    }

}

if (isset($_FILES['image'])) {
    $original_name = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];

    $uploads_dir = $_SERVER['DOCUMENT_ROOT'] . '/isitec/public/cursos/uploads';

    if (!file_exists($uploads_dir)) {
        mkdir($uploads_dir, 0777, true);
    }

    $random_value = bin2hex(random_bytes(8));
    $hashed_name = hash('sha256', $original_name . $random_value);
    $extension = pathinfo($original_name, PATHINFO_EXTENSION);
    $destination = $uploads_dir . '/' . $hashed_name . '.' . $extension;
    $tokenValue = $_COOKIE['token'] ?? '';
    $userId = getUserIdByUsernameOrEmail($tokenValue);

    if ($userId !== null) {
        echo "El ID del usuario es: " . $userId;
    } else {
        echo "Usuario no encontrado.";
    }

    if (move_uploaded_file($tmp_name, $destination)) {
        $courseId = guardarCurso($userId, $_POST['title'], $_POST['description'], "cursos/uploads/" . $hashed_name . "." . $extension);
        if ($courseId !== null && guardarTagsDelCurso($courseId, $selectedTags) && insertVideoLink($courseId, $_POST['videoURL'])) {
            header("Location: /isitec/public/home.php");
        }
    } else {
        echo "Hubo un error al subir el archivo.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Añadir curso</title>
    <link rel="stylesheet" href="../../assets/css/addCourse.css" />
    <link rel="icon" href="../../assets/img/addCourse.webp" type="image/webp" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</head>

<body class="bg-gray-900 text-white">

    <?php require_once __DIR__ . '\..\..\includes\navBar.php';?>

    <div class="container mx-auto p-4 flex flex-col items-center justify-center min-h-content">
        <!-- Título de la página -->
        <h2 class="text-2xl font-bold mb-8">Añade tu curso</h2>

        <div class="w-full max-w-4xl flex flex-col md:flex-row gap-8">
            <!-- Sección de Drag and Drop para la imagen del curso -->
            <div class="flex-1">
                <label for="dropzone-file"
                    class="flex flex-col items-center justify-center w-full h-64 border-2 border-dashed rounded-lg bg-gray-800 text-gray-500 cursor-pointer mb-4 my-6">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                        </svg>
                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">Click to upload </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)
                        </p>
                    </div>
                    <input id="dropzone-file" type="file" name="image" class="hidden" accept="image/*" />
                </label>

                <!-- Dropdown para seleccionar tags -->
                <div class="mb-8 relative">
                    <label class="font-bold mb-2" for="tags">Tags</label>
                    <button id="dropdownButton" data-dropdown-toggle="dropdown"
                        class="my-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 w-full text-left"
                        type="button">
                        Selecciona tags
                        <!-- Icono del dropdown -->
                        <svg class="ml-auto -mr-1 w-4 h-4" aria-hidden="true" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <!-- Menú del dropdown -->
                    <div id="dropdown"
                        class="hidden z-10 w-full bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700">
                        <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownButton">
                            <?php echo $htmlTags; ?>
                        </ul>
                    </div>
                </div>

            </div>



            <!-- Formulario para el resto de datos -->
            <div class="flex-1">
                <form action="addCourse.php" method="post" enctype="multipart/form-data">
                    <!-- Título del curso -->
                    <div class="mb-4">
                        <label for="title" class="font-bold mb-2">Título del Curso</label>
                        <input name="title" type="text" placeholder="Enter a title for your dream"
                            class="w-full bg-gray-800 rounded-md border border-gray-700 px-4 py-2" />
                    </div>

                    <!-- Descripción del curso -->
                    <div class="mb-4">
                        <label for="description" class="font-bold mb-2">Descripción</label>
                        <textarea name="description" placeholder="Describe your dream in detail"
                            class="textarea-custom-scrollbar w-full bg-gray-800 rounded-md border border-gray-700 px-4 py-2 h-44"
                            rows="7"></textarea>
                    </div>

                    <!-- Enlace de video -->
                    <div class="mb-4">
                        <label for="videoURL" class="font-bold mb-2">Enlace de video</label>
                        <input name="videoURL" type="text" placeholder="Enter a video link for your dream"
                            class="w-full bg-gray-800 rounded-md border border-gray-700 px-4 py-2" />
                    </div>


                    <!-- Botón para subir curso -->
                    <div class="text-center">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-500 rounded-lg px-4 py-2 font-bold">
                            Subir Curso
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <script src="../../assets/js/home.js"></script>
</body>

</html>