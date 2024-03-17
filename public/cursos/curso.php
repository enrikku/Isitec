<?php
require_once __DIR__ . '\..\..\utils/utils.php';

$courseId = isset($_GET['id']) ? $_GET['id'] : null;

if ($courseId == null) {
    header("Location: ../home.php");
} else {
    $course = obtenerCurso($courseId);
}

function convertirURLparaIFrame($url)
{
    // Expresión regular para extraer el ID del video de la URL de YouTube
    $pattern = '/(?:youtube(?:-nocookie)?\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';

    // Intenta encontrar el ID del video en la URL
    if (preg_match($pattern, $url, $matches)) {
        $video_id = $matches[1]; // ID del video
        // Construye y devuelve la URL para el iframe
        return "https://www.youtube.com/embed/$video_id";
    } else {
        // Si no se puede extraer el ID del video, devuelve la URL original
        return $url;
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Curso</title>
    <link rel="icon" href="../../assets/img/CourseDetail.ico" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <link rel="stylesheet" href="../../assets/css/common.css">
</head>

<body class="bg-gray-900 text-white">

    <?php require_once __DIR__ . '/../../includes/navBar.php'; ?>

    <div class="container mx-auto p-4">
        <div class="flex flex-wrap">
            <!-- Sidebar para lista de lecciones -->
            <div class="w-full lg:w-1/4 px-2 mb-4">
                <div class="mb-4">
                    <h3 class="text-xl font-bold mb-2">Lecciones del Curso</h3>
                    <ul class="bg-gray-800 p-4 rounded-lg">
                        <!-- Suponiendo que tienes un array de lecciones -->
                        <?php foreach ($course['lessons'] as $lesson): ?>
                        <li class="py-2 border-b border-gray-700">
                            <a href="#" class="text-white hover:text-blue-400"><?php echo $lesson['title']; ?></a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Contenido Principal -->
            <div class="w-full lg:w-3/4 px-2 mb-4">
                <!-- Detalles del Curso -->
                <div class="mb-4">
                    <h1 class="text-3xl font-bold mb-2">
                        <?php echo htmlspecialchars($course['title']); ?>
                    </h1>
                    <p class="text-gray-500 dark:text-gray-400">
                        <?php echo htmlspecialchars($course['description']); ?>
                    </p>
                </div>

                <!-- Player de Video -->
                <div class="mb-4">
                    <iframe width="560" height="315"
                        src="<?php echo convertirURLparaIFrame($course['videos'][0]['videoURL']); ?>" frameborder="0"
                        allowfullscreen></iframe>
                </div>

                <!-- Formulario de Inscripción -->
                <div class="mb-4">
                    <h3 class="text-xl font-bold mb-2">Inscribirse en el Curso</h3>
                    <form action="post" class="bg-gray-800 p-4 rounded-lg">
                        <div class="mb-4">
                            <label for="name" class="font-bold mb-1 text-sm block">Nombre Completo</label>
                            <input type="text" id="name"
                                class="w-full bg-gray-700 text-white p-2 rounded-lg focus:outline-none focus:shadow-outline"
                                placeholder="John Doe">
                        </div>
                        <div class="mb-4">
                            <label for="email" class="font-bold mb-1 text-sm block">Correo Electrónico</label>
                            <input type="email" id="email"
                                class="w-full bg-gray-700 text-white p-2 rounded-lg focus:outline-none focus:shadow-outline"
                                placeholder="john@example.com">
                        </div>
                        <div class="flex justify-center">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Inscribirse</button>
                        </div>
                    </form>
                </div>

                <!-- Testimonios -->
                <div class="mb-4">
                    <h3 class="text-xl font-bold mb-2">Testimonios</h3>
                    <!-- Suponiendo que tienes un array de testimonios -->
                    <?php foreach ($course['testimonials'] as $testimonial): ?>
                    <div class="bg-gray-800 p-4 rounded-lg mb-4">
                        <p class="text-gray-500 dark:text-gray-400"><?php echo $testimonial['content']; ?></p>
                        <span class="block text-sm text-gray-400 mt-2">— <?php echo $testimonial['author']; ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/home.js"></script>
</body>

</html>
>