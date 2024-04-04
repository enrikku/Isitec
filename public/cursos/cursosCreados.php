<?php
require_once __DIR__ . '\..\..\utils\utils.php';
/* $user = $_COOKIE['token'];
$userId = getUserIdByUsernameOrEmail($user); */
/* require_once __DIR__ . '\..\..\includes\navBar.php'; */

$user = $_COOKIE['token'];

$id = getUserIdByUsernameOrEmail($user);

$cursos = cursosCreados($id);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Cursos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
    <body class="bg-gray-900 text-white">
        <?php require_once __DIR__ . '/../../includes/navBar.php';?>


        <main class="py-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10">
            
            <?php foreach ($cursos as $curso): ?>

            <!-- CARD 1 -->
            <div class="rounded overflow-hidden shadow-lg flex flex-col bg-card transition-transform hover:scale-105">
                <a href="./curso.php?id=<?php echo htmlspecialchars($curso['courseId']); ?>"></a>
                <div class="relative cursor-pointer">
                    <a href="./curso.php?id=<?php echo htmlspecialchars($curso['courseId']); ?>">
                        <img class="w-full" src="<?php echo htmlspecialchars($curso['coverURL']); ?>"
                            alt="Imagen del curso <?php echo htmlspecialchars($curso['title']); ?>"
                            style="max-height: 80%;">
                        <div
                            class="hover:bg-transparent transition duration-300 absolute bottom-0 top-0 right-0 left-0 bg-gray-900 opacity-25">
                        </div>
                    </a>
                    <a href="./curso.php?id=<?php echo htmlspecialchars($curso['courseId']); ?>">
                        <div
                            class="text-xs absolute top-0 right-0 bg-indigo-600 px-4 py-2 text-white mt-3 mr-3 hover:bg-white hover:text-indigo-600 transition duration-500 ease-in-out">
                            Ver curso
                        </div>
                    </a>
                </div>
                <div class="px-6 py-4 mb-auto">
                    <a href="./curso.php?id=<?php echo htmlspecialchars($curso['courseId']); ?>"
                        class="font-medium text-lg inline-block hover:text-indigo-600 transition duration-500 ease-in-out inline-block mb-2">
                        <?php echo htmlspecialchars($curso['title']); ?> </a>
                    <!-- Tags del curso -->
                    <div class="px-6 pt-4 pb-2">
                        <?php if (!empty($curso['tags'])): ?>
                        <?php foreach ($curso['tags'] as $tag): ?>
                        <span
                            class="bg-gray-100 text-gray-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
                            <?php echo htmlspecialchars($tag['tag']); ?>
                        </span>
                        <?php endforeach;?>
                        <?php endif;?>
                    </div>
                    <!-- Like button and dislike -->
                    <div class="flex gap-2">
                        <button data-id=<?php echo htmlspecialchars($curso['courseId']); ?>
                            class="py-1.5 px-3 hover:text-green-600 hover:scale-105 hover:shadow text-center border border-gray-300 rounded-md border-gray-400 h-8 text-sm flex items-center gap-1 lg:gap-2 like-button">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z">
                                </path>
                            </svg>
                            <span><?php echo $curso['votos']['likes'] ?? 0; ?></span>
                        </button>

                        <button data-id=<?php echo htmlspecialchars($curso['courseId']); ?>
                            class="py-1.5 px-3 hover:text-red-600 hover:scale-105 hover:shadow text-center border border-gray-300 rounded-md border-gray-400 h-8 text-sm flex items-center gap-1 lg:gap-2 like-button">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7.5 15h2.25m8.024-9.75c.011.05.028.1.052.148.591 1.2.924 2.55.924 3.977a8.96 8.96 0 01-.999 4.125m.023-8.25c-.076-.365.183-.75.575-.75h.908c.889 0 1.713.518 1.972 1.368.339 1.11.521 2.287.521 3.507 0 1.553-.295 3.036-.831 4.398C20.613 14.547 19.833 15 19 15h-1.053c-.472 0-.745-.556-.5-.96a8.95 8.95 0 00.303-.54m.023-8.25H16.48a4.5 4.5 0 01-1.423-.23l-3.114-1.04a4.5 4.5 0 00-1.423-.23H6.504c-.618 0-1.217.247-1.605.729A11.95 11.95 0 002.25 12c0 .434.023.863.068 1.285C2.427 14.306 3.346 15 4.372 15h3.126c.618 0 .991.724.725 1.282A7.471 7.471 0 007.5 19.5a2.25 2.25 0 002.25 2.25.75.75 0 00.75-.75v-.633c0-.573.11-1.14.322-1.672.304-.76.93-1.33 1.653-1.715a9.04 9.04 0 002.86-2.4c.498-.634 1.226-1.08 2.032-1.08h.384">
                                </path>
                            </svg>
                            <span><?php echo $curso['votos']['dislikes'] ?? 0; ?></span>
                        </button>

                    </div>
                    <p class="text-gray-500 text-sm">
                        <?php echo $curso['description']; ?>
                    </p>
                </div>
                <div class="px-6 py-3 flex flex-row items-center justify-between bg-gray-100">
                    <span href="#" class="py-1 text-xs font-regular text-gray-900 mr-1 flex flex-row items-center">
                        <?php if ($curso['tiempoUltimoComentario'] != '0 comentarios'): ?>
                        <svg height="13px" width="13px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512"
                            style="enable-background:new 0 0 512 512;" xml:space="preserve">
                            <g>
                                <g>
                                    <path
                                        d="M256,0C114.837,0,0,114.837,0,256s114.837,256,256,256s256-114.837,256-256S397.163,0,256,0z M277.333,256 c0,11.797-9.536,21.333-21.333,21.333h-85.333c-11.797,0-21.333-9.536-21.333-21.333s9.536-21.333,21.333-21.333h64v-128 c0-11.797,9.536-21.333,21.333-21.333s21.333,9.536,21.333,21.333V256z">
                                    </path>
                                </g>
                            </g>
                        </svg>

                        <span class="ml-1"><?php echo $curso['tiempoUltimoComentario'] ?></span>
                        <?php endif?>
                    </span>

                    <span href="#" class="py-1 text-xs font-regular text-gray-900 mr-1 flex flex-row items-center">
                        <svg class="h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                            </path>
                        </svg>
                        <span class="ml-1"><?php echo $curso['testimonios']; ?> comentarios</span>
                    </span>
                </div>
            </div>
            <?php endforeach;?>
        </div>
    </main>

    <?php require_once __DIR__ . '\..\..\includes\footer.php';?>


    <script src="/isitec/assets/js/home.js"></script>
</body>
</html>