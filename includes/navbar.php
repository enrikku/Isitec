<?php
require_once __DIR__ . '\..\utils/utils.php';
require_once __DIR__ . '\redireccion.php';

$current_page = basename($_SERVER['PHP_SELF']);
$user = $_COOKIE['token'];
$userId = getUserIdByUsernameOrEmail($user);
$esAutor = esAutor($userId);
$esEstudiante = esEstudiante($userId);

// Función para imprimir la clase correcta
function print_link_class($page)
{
    global $current_page;
    $base_class = "text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium";
    if ($current_page == $page) {
        echo "bg-gray-900 text-white " . $base_class;
    } else {
        echo $base_class;
    }
}
?>

<nav class="bg-gray-800">
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
        <div class="relative flex items-center justify-between h-16">

            <!-- Contenedor del logotipo -->
            <div class="flex-1 flex items-center justify-start sm:items-stretch sm:justify-start">
                <a href="/isitec/public/home.php" class="flex-shrink-0 flex items-center">
                    <section class="hidden sm:block">
                        <span class="text-lg sm:text-xl md:text-1xl lg:text-1xl text-red-500">developer</span>
                        <span class="text-lg sm:text-xl md:text-1xl lg:text-1xl text-green-500">@</span>
                        <span class="text-lg sm:text-xl md:text-1xl lg:text-1xl text-blue-500">php:</span>
                        <span class="text-lg sm:text-xl md:text-1xl lg:text-1xl text-yellow-500">~</span>
                        <span class="text-lg sm:text-xl md:text-1xl lg:text-1xl text-purple-500">$</span>
                        <span
                            class="text-lg sm:text-xl md:text-1xl lg:text-1xl font-bold mx-2 text-orange-500">ISITEC</span>
                        <span class="text-lg sm:text-xl md:text-1xl lg:text-1xl animate-blink text-green-500">|</span>
                    </section>
                </a>

                <!-- Menú de navegación para escritorio -->
                <div class="hidden sm:block sm:ml-6">
                    <div class="flex space-x-4">
                        <!-- Los elementos de tu menú aquí -->

                        <a href="/isitec/public/cursos/addCourse.php" class="<?php print_link_class('addCourse.php');?>"
                            <?php echo ($current_page == 'addCourse.php' ? 'aria-current="page"' : ''); ?>>Añadir
                            curso</a>

                        <?php if ($esAutor): ?>
                        <a href="/isitec/public/cursos/addLesson.php" class="<?php print_link_class('addLesson.php');?>"
                            <?php echo ($current_page == 'addLesson.php' ? 'aria-current="page"' : ''); ?>>
                            Añadir Leccion</a>
                        <?php endif;?>

                        <?php if ($esEstudiante): ?>
                        <a href="/isitec/public/cursos/misCursos.php" class="<?php print_link_class('misCursos.php');?>"
                            <?php echo ($current_page == 'misCursos.php' ? 'aria-current="page"' : ''); ?>>
                            Mis Cursos</a>

                        <?php endif;?>
                    </div>
                </div>
            </div>

            <!-- Barra de búsqueda para escritorio -->
            <?php

$current_page = basename($_SERVER['PHP_SELF']);

if ($current_page != 'addCourse.php' && $current_page != 'addLesson.php') {
    ?>
            <div class="hidden md:block w-full max-w-xs">
                <form class="max-w-md mx-auto" method="get">
                    <label for="default-search"
                        class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Buscar</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <!-- Icono de lupa -->
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <input type="search" id="default-search" name="search"
                                class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-600 rounded-lg bg-gray-700 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="React, php, c# ..." required />
                            <button type="submit" class="absolute right-2.5 bottom-2.5 bg-gray-600 hover:bg-gray-500 text-white border border-gray-600 focus:ring-4 focus:outline-none focus:ring-gray-300
        font-medium rounded-lg text-sm px-4 py-2 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                                Buscar
                            </button>
                        </div>

                    </div>
                </form>

            </div>
            <?php

}
?>
            <div class="hidden md:block">
                <div class="ml-4 flex items-center md:ml-6">
                    <button type="button" stroke="currentColor" aria-hidden="true">

                    </button>

                    <!-- Profile dropdown -->
                    <div class="relative ml-3">
                        <div>
                            <button type="button"
                                class="relative flex max-w-xs items-center rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                                id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                <span class="absolute -inset-1.5"></span>
                                <span class="sr-only">Open user menu</span>
                                <img class="h-8 w-8 rounded-full"
                                    src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                    alt="">
                            </button>
                        </div>

                        <div class="absolute right-0 z-50 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                            role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                            <!-- Active: "bg-gray-100", Not Active: "" -->
                                <a href="/isitec/public/perfil.php" class="block px-4 py-2 text-sm text-gray-700"
                                role="menuitem" tabindex="-1" id="user-menu-item-0">perfil</a>
                            
                                <a href="/isitec/controller/logout.php" class="block px-4 py-2 text-sm text-gray-700"
                                role="menuitem" tabindex="-1" id="user-menu-item-2">Log out</a>
                            
                                <a href="/isitec/public/cursos/cursosCreados.php" class="block px-4 py-2 text-sm text-gray-700"
                                role="menuitem" tabindex="-1" id="user-menu-item-2">Cursos creados</a>

                        </div>
                    </div>
                </div>
            </div>
            <div class="-mr-2 flex md:hidden">


                <!-- Mobile menu button -->
                <button type="button"
                    class="relative inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                    aria-controls="mobile-menu" aria-expanded="false" id="mobile-menu-button">
                    <span class="absolute -inset-0.5"></span>
                    <span class="sr-only">Open main menu</span>
                    <!-- Menu open: "hidden", Menu closed: "block" -->
                    <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <!-- Menu open: "block", Menu closed: "hidden" -->
                    <svg class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state. -->

    <div class="md:hidden hidden " id="mobile-menu">

        <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">

            <a href="/isitec/public/home.php"
                class="text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium <?php echo ($current_page == 'home.php' ? 'bg-gray-900 text-white' : ''); ?>"
                <?php echo ($current_page == 'home.php' ? 'aria-current="page"' : ''); ?>>Home</a>


            <a href="/isitec/public/cursos/addCourse.php"
                class="text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium <?php echo ($current_page == 'addCourse.php' ? 'bg-gray-900 text-white' : ''); ?>"
                <?php echo ($current_page == 'addCourse.php' ? 'aria-current="page"' : ''); ?>>Añadir curso</a>


            <?php if ($esAutor): ?>
            <a href="/isitec/public/cursos/addLesson.php" class="<?php print_link_class('addLesson.php');?>"
                <?php echo ($current_page == 'addLesson.php' ? 'aria-current="page"' : ''); ?>> Añadir Leccion</a>
            <?php endif;?>


            <?php if ($esEstudiante): ?>
            <a href="/isitec/public/cursos/misCursos.php"
                class="text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium <?php print_link_class('misCursos.php');?>"
                <?php echo ($current_page == 'misCursos.php' ? 'aria-current="page"' : ''); ?>> Mis Cursos</a>
            <?php endif;?>
        </div>

        <?php

$current_page = basename($_SERVER['PHP_SELF']);

if ($current_page != 'addCourse.php' && $current_page != 'addLesson.php') {
    ?>
        <div class="pb-3 border-t border-gray-700">
            <form class="max-w-md mx-auto">
                <label for="mobile-search"
                    class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Buscar</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <input type="search" id="mobile-search" name="search"
                        class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-600 rounded-lg bg-gray-700 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="React, Angular, php, c# ..." required />
                    <button type="submit" class="absolute right-2.5 bottom-2.5 bg-gray-600 hover:bg-gray-500 text-white border border-gray-600 focus:ring-4 focus:outline-none focus:ring-gray-300
                    font-medium rounded-lg text-sm px-4 py-2 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                        Buscar
                    </button>
                </div>
            </form>
        </div>
        <?php
}
?>

        <div class="border-t border-gray-700 pb-3 pt-4">
            <div class="flex items-center px-5">
                <button type="button"
                    class="relative ml-auto flex-shrink-0 rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                </button>
            </div>
            <div class="mt-3 space-y-1 px-2">
                <a href="/isitec/public/perfil.php"
                    class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">Perfil</a>

                <a href="/isitec/controller/logout.php"
                    class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">Log out</a>
                
                <a href="/isitec/public/cursos/cursosCreados.php" 
                    class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">Cursos creados</a>

            </div>
        </div>
    </div>
</nav>