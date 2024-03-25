<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="icon" href="../assets/img/perfil.webp" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <link rel="stylesheet" href="../assets/css/common.css">
</head>

<body class="bg-gray-900 text-white">

    <?php require_once __DIR__ . '\..\includes\navBar.php';?>
    <main class="container mx-auto py-10 px-4">
        <!-- Perfil -->
        <section class="mb-10">
            <h1 class="text-4xl font-bold mb-4">Perfil de Usuario</h1>
            <div class="flex flex-col md:flex-row gap-6">
                <div class="md:w-1/3">
                    <!-- Información personal -->
                    <div class="bg-gray-800 p-4 rounded-lg shadow-md">
                        <h2 class="text-2xl font-semibold mb-2">Información Personal</h2>
                        <?php $user = getUserDetails($userId);?>
                        <p>Nombre: <?=$user['username']?></p>
                        <p>Email: <?=$user['mail']?></p>
                        <p>Fecha de inscripción: <?=date('d-m-Y', strtotime($user['creationDate']))?></p>
                        <!-- Puede agregar más información personal aquí -->
                    </div>
                </div>
                <div class="md:w-2/3">
                    <!-- Cursos inscritos -->
                    <div class="bg-gray-800 p-4 rounded-lg shadow-md mb-6">
                        <h2 class="text-2xl font-semibold mb-2">Cursos Inscritos</h2>
                        <!-- Lista de cursos -->
                        <?php $cursos = obtenerMisCursos($userId);
foreach ($cursos as $curso) {
    echo htmlspecialchars($curso['title']) . '<br>';
}

?>

                    </div>
                    <!-- Actividad reciente -->
                    <div class="bg-gray-800 p-4 rounded-lg shadow-md">
                        <h2 class="text-2xl font-semibold mb-2">Actividad Reciente</h2>
                        <!-- Lista de actividad reciente -->
                        <ul>
                            <li>Completaste 'Lección 5: JavaScript Avanzado'.</li>
                            <li>Te inscribiste en 'Curso de Marketing Digital'.</li>
                            <!-- Más actividad -->
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <!-- Configuración de la cuenta -->
        <section class="mb-10">
            <h2 class="text-2xl font-semibold mb-4">Configuración de la Cuenta</h2>
            <div class="bg-gray-800 p-4 rounded-lg shadow-md">
                <p>Aquí puedes añadir opciones para cambiar la contraseña, configurar la privacidad, y más.</p>
                <!-- Opciones de configuración -->
            </div>
        </section>
    </main>

    <?php require_once __DIR__ . '\..\includes\footer.php';?>
    <script src="../assets/js/home.js"></script>
</body>

</html>