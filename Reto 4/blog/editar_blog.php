<?php
session_start();

// cargamos las clases necesarias
require_once '../class/Conexion.php';
require_once '../class/Blog.php';

// conectamos a la base de datos
$conexion = (new Database())->connect();

// recogemos el id de la noticia desde la url (?id=X)
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// si no hay id válido no tiene sentido estar aquí
if (!$id) {
    header('Location: ../blog/blog.php');
    exit;
}

// buscamos la noticia en la base de datos
$noticia = Blog::FindBlog($conexion, $id);

// si se ha enviado el formulario, actualizamos la noticia
if (isset($_POST['actualizar'])) {

    $blog = new Blog($conexion);

    $blog->rellenarDatos(
        $_POST['titulo'],
        $_POST['descripcion'],
        $_POST['contenido'],
        $_POST['url_imagen'],
        $_POST['url_extra'],
        date('Y-m-d H:i:s')
    );

    if ($blog->updateAll($_POST['id'])) {
        // redirigimos según el rol (0 = admin, 1 = editora)
        if (isset($_SESSION['rol']) && $_SESSION['rol'] == 1) {
            header('Location: ../editora/blog_editora.php');
        } else {
            header('Location: ../admin/blog_admin.php');
        }
        exit;
    }
}

// para que el menú lateral sepa en qué página estamos
$pagina = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Noticia - MDM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/movil.css" media="screen and (max-width: 768px)">
    <link rel="icon" href="../img/mdmicono.ico" type="image/x-icon">
</head>
<body class="cuerpo-administracion">

    <?php
    // incluimos el nav según el rol de quien está logueado
    if (isset($_SESSION['rol']) && $_SESSION['rol'] == 1) {
        include '../editora/includes/nav.php';
    } else {
        include '../admin/includes/nav.php';
    }
    ?>

    <main class="area-principal">

        <header class="encabezado-contenido">
            <section class="texto-bienvenida">
                <h1>Modificar Noticia</h1>
            </section>
        </header>

        <!-- enlace para volver al listado correcto según el rol -->
        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] == 1): ?>
            <a href="../editora/blog_editora.php" class="admin-link-volver">
                <i class="fa-solid fa-arrow-left"></i> Volver al listado
            </a>
        <?php else: ?>
            <a href="../admin/blog_admin.php" class="admin-link-volver">
                <i class="fa-solid fa-arrow-left"></i> Volver al listado
            </a>
        <?php endif; ?>

        <?php if ($noticia): ?>

            <section class="tarjeta-blanca">
                <header class="cabecera-tarjeta">
                    <h2>
                        <i class="fa-solid fa-pen-to-square"></i> Editar Noticia
                    </h2>
                </header>

                <form action="editar_blog.php?id=<?= $id ?>" method="POST" class="formulario-compacto">

                    <!-- id oculto para saber qué noticia estamos guardando -->
                    <input type="hidden" name="id" value="<?= $id ?>">

                    <section class="campo-fila">
                        <label>Título</label>
                        <input type="text" name="titulo"
                               value="<?= htmlspecialchars($noticia->getTitulo()) ?>" required>
                    </section>

                    <section class="campo-fila">
                        <label>Descripción</label>
                        <input type="text" name="descripcion"
                               value="<?= htmlspecialchars($noticia->getDescripcion()) ?>" required>
                    </section>

                    <section class="campo-fila">
                        <label>URL de la Imagen</label>
                        <input type="url" name="url_imagen"
                               value="<?= htmlspecialchars($noticia->getUrlIcono()) ?>">
                    </section>

                    <section class="campo-fila">
                        <label>URL de Página Oficial (Leer más)</label>
                        <input type="url" name="url_extra"
                               value="<?= htmlspecialchars($noticia->getUrlExtra()) ?>"
                               placeholder="https://...">
                    </section>

                    <section class="campo-fila">
                        <label>Contenido</label>
                        <textarea name="contenido" rows="10"><?= htmlspecialchars($noticia->getContenido()) ?></textarea>
                    </section>

                    <button type="submit" name="actualizar" class="boton-subir-noticia">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar Cambios
                    </button>

                </form>
            </section>

        <?php else: ?>

            <section class="tarjeta-blanca">
                <p>Noticia no encontrada.</p>
            </section>

        <?php endif; ?>

    </main>

</body>
</html>
