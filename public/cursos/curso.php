<?php
require_once __DIR__ . '\..\..\utils/utils.php';

$courseId = isset($_GET['id']) ? $_GET['id'] : null;
//conseguir variable de la cooke
$user = $_COOKIE['token'];
$userId = getUserIdByUsernameOrEmail($user);
$soyElAutor = esElAutor($userId, $courseId);
$avgCourse = round(averageCourseRating($courseId), 2);

$totalRatings = getTotalRatings($courseId);
$totalStudents = getTotalStudents($courseId);
$fullStars = floor($avgCourse);
$halfStar = ($avgCourse - $fullStars) >= 0.5 ? 1 : 0;
$emptyStars = 5 - $fullStars - $halfStar;
$tieneZIP = false;

if (isset($_GET['titleLesson']) && isset($_GET['descriptionLesson']) && isset($_GET['videoLesson'])) {
    $titleLesson = $_GET['titleLesson'];
    $descriptionLesson = $_GET['descriptionLesson'];
    $videoLesson = convertirURLparaIFrame($_GET['videoLesson']);
    $tieneZIP = tieneZIP($_GET['idLesson'], $courseId);
    
    if($tieneZIP)
    {
        $zip = getZIP($_GET['idLesson'], $courseId);
    }

} else {
    if ($courseId == null) {
        header("Location: ../home.php");
    } else {
        $course = obtenerCurso($courseId);
    }
}

$lecciones = obtenerLecciones($courseId);
$comentarios = obtenerComentarios($courseId);
$estoySubscrito = hasSubscription($userId, $courseId);

if (isset($_POST['subscribe'])) {
    subscribirme($userId, $courseId); // puede ser tanto el nombre de usuario como el mail
    header("Refresh: 0.5; url=../cursos/curso.php?id=$courseId");
}

if (isset($_POST['comentario']) && !empty($_POST['rating'])) {
    updateRating($courseId, $userId);
    enviarComentario($courseId, $userId, $_POST['comentario'], $_POST['rating'] != 0 ? $_POST['rating'] : null);
    header("Refresh: 0.5; url=../cursos/curso.php?id=$courseId");
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
    <link rel="icon" href="/isitec/assets/img/CourseDetail.ico" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <link rel="stylesheet" href="/isitec/assets/css/curso.css">
    <link rel="stylesheet" href="/isitec/assets/css/common.css">
    <link rel="stylesheet" href="/isitec/assets/css/estrella.css">
    <link rel="stylesheet" href="https://unpkg.com/emoji-mart/css/emoji-mart.css" />
</head>

<body class="bg-gray-900 text-white">

    <?php require_once __DIR__ . '/../../includes/navBar.php';?>

    <?php if($tieneZIP): ?>

        <button><a href= "<?= $zip; ?>" download>Descargar ZIP</a></button>

    <?php endif; ?>

    <div class="container mx-auto p-4">
        <div class="flex flex-wrap">
            <!-- Sidebar para lista de lecciones -->
            <div class="w-full lg:w-1/4 px-2 mb-4">
                <div class="mb-4">
                    <h3 class="text-xl font-bold mb-2">Lecciones del Curso</h3>
                    <ul class="bg-gray-800 p-4 rounded-lg">
                        <!-- Suponiendo que tienes un array de lecciones -->
                        <?php if (count($lecciones) == 0): ?>
                        <li class="py-2 border-b border-gray-700">
                            <p href="#" class="text-white hover:text-blue-400">No hay lecciones disponibles</p>
                        </li>
                        <?php endif;?>

                        <?php foreach ($lecciones as $leccion): ?>
                        <li class="py-2 border-b border-gray-700">
                            <a href="curso.php?id=<?php echo $leccion['courseId']; ?>&titleLesson=<?php echo $leccion['title']; ?>&descriptionLesson=<?php echo $leccion['description']; ?>&videoLesson=<?php echo $leccion['videoURL']; ?> &idLesson=<?php echo $leccion['lessonId']; ?>"
                                class="text-white hover:text-blue-400"><?php echo $leccion['title']; ?></a>
                        </li>
                        <?php endforeach;?>
                    </ul>
                </div>
                <div class="rating">
                    <!-- Estrellas completas -->
                    <?php for ($i = 0; $i < $fullStars; $i++): ?>
                    <span class="star full">★</span>
                    <?php endfor;?>

                    <!-- Media estrella -->
                    <?php if ($halfStar): ?>
                    <span class="star half">★</span>
                    <?php endif;?>

                    <!-- Estrellas vacías -->
                    <?php for ($i = 0; $i < $emptyStars; $i++): ?>
                    <span class="star empty">★</span>
                    <?php endfor;?>

                    <!-- Calificación y número de calificaciones -->
                    <span class="rating-number"><?php echo number_format($avgCourse, 1); ?></span><br>
                    <span class=" total-ratings"><?php echo number_format($totalRatings); ?> calificaciones</span><br>
                    <span class="total-students"><?php echo number_format($totalStudents); ?> estudiantes</span>
                </div>
            </div>




            <!-- Contenido Principal -->
            <div class="w-full lg:w-3/4 px-2 mb-4">
                <!-- Detalles del Curso -->
                <div class="mb-4">
                    <h1 class="text-3xl font-bold mb-2">
                        <?php
                            $titleToShow = empty($_GET['titleLesson']) ? htmlspecialchars($course['title']) : htmlspecialchars($_GET['titleLesson']);
                            echo $titleToShow;
                        ?>

                    </h1>
                    <p class="text-gray-500 dark:text-gray-400">
                        <!-- <?php echo htmlspecialchars($course['description']); ?> -->
                        <?php
                            $descToShow = empty($_GET['descriptionLesson']) ? htmlspecialchars($course['description']) : htmlspecialchars($_GET['descriptionLesson']);
                            echo $descToShow;
                        ?>
                    </p>
                </div>

                <!-- Contenedor responsivo para el iframe del video -->
                <div class="video-container relative overflow-hidden pb-56.25% rounded-3xl shadow-3xl"
                    style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                    <iframe src="<?php $videoToShow = empty($videoLesson) ? htmlspecialchars(convertirURLparaIFrame($course['videos'][0]['videoURL'])) : htmlspecialchars($videoLesson);
                        echo $videoToShow;?>" class="absolute top-0 left-0 w-full h-full" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
                    </iframe>
                </div>

                <!-- Formulario de Subscripción -->
                <?php if (!$estoySubscrito && !$soyElAutor): ?>
                <!--@enrikku: si no estoy suscrito y no soy el autor -->
                <div class=" mb-4 text-center py-5">

                    <form method="post">
                        <button type="submit" name="subscribe"
                            class="bg-transparent bg-gray-600 hover:bg-gray-500 rounded-lg px-4 py-2 font-bold border-2 border-gray-600">Subscribete</button>
                    </form>
                </div>
                <?php endif;?>

                <?php if ($estoySubscrito): ?>
                <form method="post" id="chat-form" class="rating">
                    <div class="rating__stars py-5">
                        <input id="numEstrella" class="hidden" type="text" name="rating" value=0 name="rating">
                        <input id="rating-1" class="rating__input rating__input-1" type="radio" name="rating" value="1">
                        <input id="rating-2" class="rating__input rating__input-2" type="radio" name="rating" value="2">
                        <input id="rating-3" class="rating__input rating__input-3" type="radio" name="rating" value="3">
                        <input id="rating-4" class="rating__input rating__input-4" type="radio" name="rating" value="4">
                        <input id="rating-5" class="rating__input rating__input-5" type="radio" name="rating" value="5">
                        <label class="rating__label" for="rating-1">
                            <svg class="rating__star" width="32" height="32" viewBox="0 0 32 32" aria-hidden="true">
                                <g transform="translate(16,16)">
                                    <circle class="rating__star-ring" fill="none" stroke="#000" stroke-width="16" r="8"
                                        transform="scale(0)" />
                                </g>
                                <g stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <g transform="translate(16,16) rotate(180)">
                                        <polygon class="rating__star-stroke"
                                            points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07"
                                            fill="none" />
                                        <polygon class="rating__star-fill"
                                            points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07"
                                            fill="#000" />
                                    </g>
                                    <g transform="translate(16,16)" stroke-dasharray="12 12" stroke-dashoffset="12">
                                        <polyline class="rating__star-line" transform="rotate(0)" points="0 4,0 16" />
                                        <polyline class="rating__star-line" transform="rotate(72)" points="0 4,0 16" />
                                        <polyline class="rating__star-line" transform="rotate(144)" points="0 4,0 16" />
                                        <polyline class="rating__star-line" transform="rotate(216)" points="0 4,0 16" />
                                        <polyline class="rating__star-line" transform="rotate(288)" points="0 4,0 16" />
                                    </g>
                                </g>
                            </svg>
                            <span class="rating__sr">1 star—Terrible</span>
                        </label>
                        <label class="rating__label" for="rating-2">
                            <svg class="rating__star" width="32" height="32" viewBox="0 0 32 32" aria-hidden="true">
                                <g transform="translate(16,16)">
                                    <circle class="rating__star-ring" fill="none" stroke="#000" stroke-width="16" r="8"
                                        transform="scale(0)" />
                                </g>
                                <g stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <g transform="translate(16,16) rotate(180)">
                                        <polygon class="rating__star-stroke"
                                            points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07"
                                            fill="none" />
                                        <polygon class="rating__star-fill"
                                            points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07"
                                            fill="#000" />
                                    </g>
                                    <g transform="translate(16,16)" stroke-dasharray="12 12" stroke-dashoffset="12">
                                        <polyline class="rating__star-line" transform="rotate(0)" points="0 4,0 16" />
                                        <polyline class="rating__star-line" transform="rotate(72)" points="0 4,0 16" />
                                        <polyline class="rating__star-line" transform="rotate(144)" points="0 4,0 16" />
                                        <polyline class="rating__star-line" transform="rotate(216)" points="0 4,0 16" />
                                        <polyline class="rating__star-line" transform="rotate(288)" points="0 4,0 16" />
                                    </g>
                                </g>
                            </svg>
                            <span class="rating__sr">2 stars—Bad</span>
                        </label>
                        <label class="rating__label" for="rating-3">
                            <svg class="rating__star" width="32" height="32" viewBox="0 0 32 32" aria-hidden="true">
                                <g transform="translate(16,16)">
                                    <circle class="rating__star-ring" fill="none" stroke="#000" stroke-width="16" r="8"
                                        transform="scale(0)" />
                                </g>
                                <g stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <g transform="translate(16,16) rotate(180)">
                                        <polygon class="rating__star-stroke"
                                            points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07"
                                            fill="none" />
                                        <polygon class="rating__star-fill"
                                            points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07"
                                            fill="#000" />
                                    </g>
                                    <g transform="translate(16,16)" stroke-dasharray="12 12" stroke-dashoffset="12">
                                        <polyline class="rating__star-line" transform="rotate(0)" points="0 4,0 16" />
                                        <polyline class="rating__star-line" transform="rotate(72)" points="0 4,0 16" />
                                        <polyline class="rating__star-line" transform="rotate(144)" points="0 4,0 16" />
                                        <polyline class="rating__star-line" transform="rotate(216)" points="0 4,0 16" />
                                        <polyline class="rating__star-line" transform="rotate(288)" points="0 4,0 16" />
                                    </g>
                                </g>
                            </svg>
                            <span class="rating__sr">3 stars—OK</span>
                        </label>
                        <label class="rating__label" for="rating-4">
                            <svg class="rating__star" width="32" height="32" viewBox="0 0 32 32" aria-hidden="true">
                                <g transform="translate(16,16)">
                                    <circle class="rating__star-ring" fill="none" stroke="#000" stroke-width="16" r="8"
                                        transform="scale(0)" />
                                </g>
                                <g stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <g transform="translate(16,16) rotate(180)">
                                        <polygon class="rating__star-stroke"
                                            points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07"
                                            fill="none" />
                                        <polygon class="rating__star-fill"
                                            points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07"
                                            fill="#000" />
                                    </g>
                                    <g transform="translate(16,16)" stroke-dasharray="12 12" stroke-dashoffset="12">
                                        <polyline class="rating__star-line" transform="rotate(0)" points="0 4,0 16" />
                                        <polyline class="rating__star-line" transform="rotate(72)" points="0 4,0 16" />
                                        <polyline class="rating__star-line" transform="rotate(144)" points="0 4,0 16" />
                                        <polyline class="rating__star-line" transform="rotate(216)" points="0 4,0 16" />
                                        <polyline class="rating__star-line" transform="rotate(288)" points="0 4,0 16" />
                                    </g>
                                </g>
                            </svg>
                            <span class="rating__sr">4 stars—Good</span>
                        </label>
                        <label class="rating__label" for="rating-5">
                            <svg class="rating__star" width="32" height="32" viewBox="0 0 32 32" aria-hidden="true">
                                <g transform="translate(16,16)">
                                    <circle class="rating__star-ring" fill="none" stroke="#000" stroke-width="16" r="8"
                                        transform="scale(0)" />
                                </g>
                                <g stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <g transform="translate(16,16) rotate(180)">
                                        <polygon class="rating__star-stroke"
                                            points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07"
                                            fill="none" />
                                        <polygon class="rating__star-fill"
                                            points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07"
                                            fill="#000" />
                                    </g>
                                    <g transform="translate(16,16)" stroke-dasharray="12 12" stroke-dashoffset="12">
                                        <polyline class="rating__star-line" transform="rotate(0)" points="0 4,0 16" />
                                        <polyline class="rating__star-line" transform="rotate(72)" points="0 4,0 16" />
                                        <polyline class="rating__star-line" transform="rotate(144)" points="0 4,0 16" />
                                        <polyline class="rating__star-line" transform="rotate(216)" points="0 4,0 16" />
                                        <polyline class="rating__star-line" transform="rotate(288)" points="0 4,0 16" />
                                    </g>
                                </g>
                            </svg>
                            <span class="rating__sr">5 stars—Excellent</span>
                        </label>
                    </div>

                    <label for="chat" class="sr-only">Este curso me cambio la vida</label>
                    <div class="flex items-center px-3 py-2 rounded-lg bg-gray-50 dark:bg-gray-700 relative">
                        <!-- Añade relative para que z-index funcione -->
                        <button id="emojiButton" type="button"
                            class="p-2 text-gray-500 rounded-lg cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13.408 7.5h.01m-6.876 0h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM4.6 11a5.5 5.5 0 0 0 10.81 0H4.6Z" />
                            </svg>
                            <span class="sr-only">Add emoji</span>
                        </button>

                        <div id="emojiPicker"
                            style="display: none; position: absolute; top: calc(100% + 10px); left: 0; z-index: 999;">
                        </div> <!-- Ajusta el estilo para que se vea correctamente -->

                        <textarea id="chat" name="comentario" rows="1"
                            class="block mx-4 p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Este curso me cambio la vida..."></textarea>
                        <button type="submit"
                            class="inline-flex justify-center p-2 text-blue-600 rounded-full cursor-pointer hover:bg-blue-100 dark:text-blue-500 dark:hover:bg-gray-600">
                            <svg class="w-5 h-5 rotate-90 rtl:-rotate-90" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                                <path
                                    d="m17.914 18.594-8-18a1 1 0 0 0-1.828 0l-8 18a1 1 0 0 0 1.157 1.376L8 18.281V9a1 1 0 0 1 2 0v9.281l6.758 1.689a1 1 0 0 0 1.156-1.376Z" />
                            </svg>
                            <span class="sr-only">Este curso me cambio la vida</span>
                        </button>
                    </div>
                </form>
                <?php endif;?>


                <!-- Testimonios -->
                <div class="mb-4 py-5" style="max-height: 300px; overflow-y: auto;">
                    <h3 class="text-xl font-bold mb-2">Testimonios</h3>
                    <!-- Suponiendo que tienes un array de testimonios -->
                    <?php foreach ($comentarios as $testimonial): ?>
                    <div class="bg-gray-800 p-4 rounded-lg mb-4">
                        <p class="text-gray-500 dark:text-gray-400"><?php echo $testimonial['username']; ?></p>
                        <span class="block text-sm text-gray-400 mt-2">—
                            <?php echo $testimonial['testimonial']; ?></span>
                    </div>
                    <?php endforeach;?>
                </div>

            </div>
        </div>
    </div>


    <script src="/isitec/assets/js/home.js"></script>
    <script src="/isitec/assets/js/estrella.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/emoji-mart@latest/dist/browser.js"></script>
    <!-- Ya se mirara -->
    <script>
    const emojiButton = document.getElementById('emojiButton');
    const emojiPicker = document.getElementById('emojiPicker');
    const chatTextarea = document.getElementById('chat');

    // Agregar un evento de clic al botón para mostrar u ocultar el selector de emoji
    emojiButton.addEventListener('click', function() {
        if (emojiPicker.style.display === 'none') {
            // Mostrar el selector de emoji si está oculto
            emojiPicker.style.display = 'block';
        } else {
            // Ocultar el selector de emoji si está visible
            emojiPicker.style.display = 'none';
        }
    });

    // Configurar el selector de emoji
    const pickerOptions = {
        onSelect: function(emoji) {
            // Obtener el emoji seleccionado y agregarlo al contenido del textarea
            chatTextarea.value += emoji.native; // Agrega el emoji al final del contenido del textarea
            alert("a")
        }
    };
    const picker = new EmojiMart.Picker(pickerOptions);

    // Agregar el selector de emoji al div emojiPicker
    emojiPicker.appendChild(picker);
    </script>
    <?php require_once __DIR__ . '\..\..\includes\footer.php';

?>
</body>

</html>