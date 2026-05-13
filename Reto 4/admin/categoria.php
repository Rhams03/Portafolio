<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/movil.css" media="screen and (max-width: 768px)">
    <link rel="icon" href="../img/mdmicono.ico" type="image/x-icon">
</head>
<body class="body-admin">

    <aside class="nav-lateral">
        <header class="admin-cabecera">
        <!-- aqui va una imagen del logo-->
            <span>Panel de Control</span>
        </header>
        
        <nav class="lista-opciones">
            <span class="etiqueta-seccion">Navegación</span>
            <ul>
                <li>
                    <a href="admin.php" class="enlace-activo">
                        <i class="fa-solid fa-gauge-high"></i> Inicio
                    </a>
                </li>
                <li>
                    <a href="gestion_usuarias.php">
                        <i class="fa-solid fa-users-gear"></i> Gestionar Usuarias
                    </a>
                </li>
                <li>
                    <a href="blog_admin.php">
                        <i class="fa-solid fa-pen-to-square"></i> Gestionar Blog
                    </a>
                </li>
                
                <li class="opcion-desplegable">
                    <details>
                        <summary><i class="fa-solid fa-list-check"></i> Categorías</summary>
                        <ul class="sublista-categorias">
                            <li><a href="modi_cat.php?id=trabajo">Añadir Categoria</a></li>
                            <li><a href="lista_categorias.php">Modificar Categorias</a></li>
                        </ul>
                    </details>
                </li>

                <li>
                    <a href="#">
                        <i class="fa-solid fa-gear"></i> Configuración
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fa-solid fa-user-circle"></i> Mi Perfil
                    </a>
                </li>
            </ul>
        </nav>
<!-- el footer tiene que ir aquí si no no sale-->
        <footer class="admin-pie">
            <!-- la salida está en la raíz del proyecto, no dentro de /admin -->
            <a href="../logout.php" class="boton-salir">Cerrar Sesión</a>
        </footer>
    </aside>
<!-- esto ya es l aparte derecha-->
    <main class="contenido-principal">
        <header class="saludo-admin">
            <h1>Panel de Gestión</h1>
            <p>Bienvenida, Administradora.</p>
        </header>

        <section class="pantalla-blanca">
            <article>
                <h2 class="titulo-seccion">Listado de Usuarias</h2>
                <p>Aqui quiero hacer la gestion de las usuarias en plan editoras y tal.</p>
                </article>
        </section>
    </main>


    <?php
    require_once '../class/Conexion.php';
    require_once '../class/Categoria.php';
    // Creamos primero el objeto de base de datos para que la conexión no falle.
    $db = new Database();
    $conn = $db->connect();



    
    
    ?>

</body>
</html>