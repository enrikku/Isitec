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

// echo $htmlTags;

// echo strip_tags($htmlTags);

// for($i = 0; $i < count($tags); i ++)
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir curso</title>
    <link rel="stylesheet" href="../../assets/css/addCourse.css">
    <link rel="icon" href="../../assets/img/addCourse.webp" type="image/webp">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</head>

<body>

    <form action="addCourse.php" method="post" enctype="multipart/form-data">
        <div class="mt-4 flex flex-col bg-gray-900 rounded-lg p-4">
            <h2 class="text-white font-bold text-2xl">AI Story Maker Dream Form</h2>

            <div class="mt-4">
                <label class="text-white" for="title">Titulo</label>
                <input placeholder="Enter a title for your dream" name="title"
                    class="w-full bg-gray-800 rounded-md border-gray-700 text-white px-2 py-1" type="text">
            </div>

            <div class="mt-4">
                <label class="text-white" for="description">Descripcion</label>
                <textarea placeholder="Describe your dream in detail" name="description"
                    class="w-full bg-gray-800 rounded-md border-gray-700 text-white px-2 py-1"
                    id="description"></textarea>
            </div>
            <div class="mt-4">
                <label class="text-white" for="videoURL">Enlace de video</label>
                <input placeholder="Enter a video link for your dream" name="videoURL"
                    class="w-full bg-gray-800 rounded-md border-gray-700 text-white px-2 py-1" type="text">
            </div>
            <div class="mt-4">
                <!-- <div class="flex-1">
                <label class="text-white" for="emotions">Tags</label>
                    <input placeholder="What emotions did you feel during your dream?" class="w-full bg-gray-800 rounded-md border-gray-700 text-white px-2 py-1" id="emotions" type="text">
            </div> -->

                <button id="dropdownBgHoverButton" data-dropdown-toggle="dropdownBgHover"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    type="button">Dropdown checkbox <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>
                <!-- Dropdown menu -->
                <div id="dropdownBgHover" class="z-10 hidden w-48 bg-white rounded-lg shadow dark:bg-gray-700">
                    <ul class="p-3 space-y-1 text-sm text-gray-700 dark:text-gray-200"
                        aria-labelledby="dropdownBgHoverButton">
                        <?php echo $htmlTags; ?>
                    </ul>
                </div>

                <!-- <li>
            <div class="flex items-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">
            <input id="checkbox-item-4" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
            <label for="checkbox-item-4" class="w-full ms-2 text-sm font-medium text-gray-900 rounded dark:text-gray-300">Default checkbox</label>
            </div>
        </li> -->

                <div class="flex items-center justify-center w-full">
                    <label for="dropzone-file"
                        class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                            </svg>
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">Click to upload </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)
                            </p>
                        </div>
                        <input id="dropzone-file" type="file" name="image" class="hidden" accept="image/*" />
                    </label>
                </div>
            </div>
            <button type="submit"
                class="max-w-[140px] py-2 px-4 flex justify-center items-center  bg-green-600 hover:bg-green-500 focus:ring-green-500 focus:ring-offset-red-200 text-white w-full transition ease-in duration-200 text-center text-base font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2  rounded-lg ">
                <svg width="20" height="20" fill="currentColor" class="mr-2" viewBox="0 0 1792 1792"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M1344 1472q0-26-19-45t-45-19-45 19-19 45 19 45 45 19 45-19 19-45zm256 0q0-26-19-45t-45-19-45 19-19 45 19 45 45 19 45-19 19-45zm128-224v320q0 40-28 68t-68 28h-1472q-40 0-68-28t-28-68v-320q0-40 28-68t68-28h427q21 56 70.5 92t110.5 36h256q61 0 110.5-36t70.5-92h427q40 0 68 28t28 68zm-325-648q-17 40-59 40h-256v448q0 26-19 45t-45 19h-256q-26 0-45-19t-19-45v-448h-256q-42 0-59-40-17-39 14-69l448-448q18-19 45-19t45 19l448 448q31 30 14 69z">
                    </path>
                </svg>
                Subir
            </button>
        </div>
    </form>
</body>

</html>