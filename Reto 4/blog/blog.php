<?php
// cargamos las clases necesarias
require_once __DIR__ . '/../class/Conexion.php';
require_once __DIR__ . '/../class/Blog.php';

// conectamos y traemos todas las noticias
$conexion = (new Database())->connect();
$noticias = Blog::GetAllBlogs($conexion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - MDM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/movil.css" media="screen and (max-width: 768px)">
    <link rel="icon" href="img/mdmicono.png" type="image/png">
    <script src="../js/i18n.js" defer></script>
</head>
<body class="cuerpo-publico">

    <header class="contenedor-hero">
        <nav class="barra-nav">
            <img class="medicos-logo" src="../img/logomedicos.png" alt="logo medicos del mundo">

            <!-- botón hamburguesa para móvil -->
            <button class="boton-hamburguesa" onclick="abrirCajon()" aria-label="Abrir menú">
                <i class="fa-solid fa-bars"></i>
            </button>

            <ul class="lista-navegacion">
                <li><a href="../index.php"     class="enlace-menu" data-i18n="nav_volver">Volver al inicio</a></li>
                <li><a href="../categorias.php" class="enlace-menu" data-i18n="nav_categorias">Categorías</a></li>
            </ul>
        </nav>

        <section class="texto-hero">
            <h2 data-i18n="blog_h2">Actualidad y Salud</h2>
            <p  data-i18n="blog_sub">Infórmate con los mejores artículos escritos por profesionales.</p>
        </section>
    </header>

    <main class="contenedor-blog">

        <header class="titulo-seccion">
            <h2 data-i18n="blog_ultimas">Últimas Publicaciones</h2>
        </header>

        <section class="rejilla-blog">

            <?php if (count($noticias) == 0): ?>

                <p class="aviso-vacio" data-i18n="blog_vacio">Aún no hay noticias publicadas.</p>

            <?php else: ?>

                <?php foreach ($noticias as $noticia): ?>
                    <article class="tarjeta-noticia">

                        <header class="cabecera-foto">
                            <img src="<?= htmlspecialchars($noticia['url_icono']) ?>"
                                 alt="<?= htmlspecialchars($noticia['titulo']) ?>">
                        </header>

                        <section class="cuerpo-tarjeta">
                            <time class="fecha-post">
                                <?= date('d M, Y', strtotime($noticia['fecha_modificacion'])) ?>
                            </time>
                            <!-- data-ai-translate → Claude traduce el título y la descripción de cada noticia -->
                            <h3 data-ai-translate><?= htmlspecialchars($noticia['titulo']) ?></h3>
                            <p  data-ai-translate><?= htmlspecialchars($noticia['descripcion']) ?></p>
                        </section>

                        <footer class="pie-tarjeta">
                            <a href="detalle_noticia.php?id=<?= $noticia['id'] ?>" class="boton-leer" data-i18n="blog_leer">
                                Leer más
                            </a>
                        </footer>

                    </article>
                <?php endforeach; ?>

            <?php endif; ?>

        </section>
    </main>

    <!-- cajón de navegación para móvil -->
    <div class="overlay-movil" id="overlay" onclick="cerrarCajon()"></div>

    <nav class="cajon-movil" id="cajon">
        <div class="cajon-cabecera">
            <img src="../img/logomedicos.png" alt="Logo MDM">
            <button class="boton-cerrar-cajon" onclick="cerrarCajon()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <ul class="cajon-nav">
            <li><a href="../index.php">      <i class="fa-solid fa-house"></i>            <span data-i18n="nav_inicio">Inicio</span></a></li>
            <li><a href="../categorias.php"> <i class="fa-solid fa-list"></i>             <span data-i18n="nav_categorias">Categorías</span></a></li>
            <li><a href="../login.php">      <i class="fa-solid fa-right-to-bracket"></i> <span data-i18n="nav_login">Iniciar Sesión</span></a></li>
        </ul>
    </nav>

    <script>
        function abrirCajon() {
            document.getElementById('cajon').classList.add('activo');
            document.getElementById('overlay').classList.add('activo');
            document.body.style.overflow = 'hidden';
        }
        function cerrarCajon() {
            document.getElementById('cajon').classList.remove('activo');
            document.getElementById('overlay').classList.remove('activo');
            document.body.style.overflow = '';
        }
    </script>

</body>
</html>
