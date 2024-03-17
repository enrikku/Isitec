<?php

require_once __DIR__ . '\..\..\utils/utils.php';

$cookie = isset($_COOKIE['token']) ? $_COOKIE['token'] : null;

if ($cookie == null) {
    header("Location: ../index.php");
}

$userId = getUserIdByUsernameOrEmail($cookie);

$userCourses = obtenerCursosUsuario($userId);
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

    <div class="container mx-auto px-4 py-8 flex flex-col items-center justify-center min-h-content">
        <div class="w-full max-w-4xl mx-auto my-8 md:my-16">
            <h1 class="text-3xl font-bold mb-6 text-center">Añadir Lecciones a tu Curso</h1>

            <div class="flex flex-col md:flex-row md:items-start gap-8">
                <!-- Columna izquierda para Dropdown y ZIP -->
                <div class="w-full md:w-1/2">
                    <div class="mb-4 relative">
                        <label class="font-bold mb-2" for="tags">Cursos</label>
                        <div class="relative">
                            <button id="dropdownButton" data-dropdown-toggle="dropdown" class="my-2 text-white bg-card hover:bg-blue-800 focus:ring-4 focus:outline-none
                                            focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 text-center inline-flex
                                            items-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800
                                            w-full text-left border-2 border-gray-600" type="button">
                                Selecciona Curso
                                <!-- Icono del dropdown -->
                                <svg class="ml-auto -mr-1 w-4 h-4" aria-hidden="true" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7">
                                    </path>
                                </svg>
                            </button>
                            <!-- Menú del dropdown -->
                            <div id="dropdown"
                                class="hidden absolute left-0 mt-1 w-full bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700">
                                <ul class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                    aria-labelledby="dropdownButton">
                                    <select name="cursoId">
                                        <?php foreach ($userCourses as $curso): ?>
                                        <option value="<?php echo htmlspecialchars($curso['courseId']); ?>">
                                            <?php echo htmlspecialchars($curso['title']); ?>
                                        </option>
                                        <?php endforeach;?>
                                    </select>
                                </ul>
                            </div>
                        </div>
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