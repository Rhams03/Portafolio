<?php
// cargamos las clases necesarias
require_once __DIR__ . '/../class/Conexion.php';
require_once __DIR__ . '/../class/Blog.php';

// conectamos a la base de datos
$conexion = (new Database())->connect();

// buscamos la noticia por el id que viene en la url (?id=X)
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// si no hay id válido volvemos al blog
if (!$id) {
    header('Location: blog.php');
    exit;
}

$noticia = Blog::FindBlog($conexion, $id);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticia - MDM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/movil.css" media="screen and (max-width: 768px)">
    <link rel="icon" href="../img/mdmicono.ico" type="image/x-icon">
</head>
<body class="cuerpo-publico">

    <main class="contenedor-blog">

        <?php if ($noticia): ?>

            <article class="detalle-noticia">

                <!-- enlace a la página oficial si existe -->
                <?php if ($noticia->getUrlExtra()): ?>
                    <p class="detalle-enlace-oficial">
                        <a href="<?= htmlspecialchars($noticia->getUrlExtra()) ?>" target="_blank">
                            <i class="fa-solid fa-circle-info"></i>
                            Leer información en la página oficial
                        </a>
                    </p>
                <?php endif; ?>

                <img src="<?= htmlspecialchars($noticia->getUrlIcono()) ?>"
                     alt="<?= htmlspecialchars($noticia->getTitulo()) ?>">

                <h2><?= htmlspecialchars($noticia->getTitulo()) ?></h2>

                <p><?= htmlspecialchars($noticia->getDescripcion()) ?></p>

                <!-- nl2br convierte los saltos de línea guardados en la BD en <br> -->
                <section>
                    <?= nl2br(htmlspecialchars($noticia->getContenido())) ?>
                </section>

                <a href="blog.php" class="detalle-volver">
                    <i class="fa-solid fa-arrow-left"></i> Volver al blog
                </a>

            </article>

        <?php else: ?>

            <p>Noticia no encontrada.</p>
            <a href="blog.php">← Volver al blog</a>

        <?php endif; ?>

    </main>

</body>
</html>
