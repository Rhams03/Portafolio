<?php
session_start();

// si no hay sesión mandamos al login
if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../class/Conexion.php';
require_once __DIR__ . '/../class/Blog.php';

$conexion = (new Database())->connect();
$blog     = new Blog($conexion);

// ── publicar noticia ────────────────────────────────────────
if (isset($_POST['publicar'])) {
    $blog->rellenarDatos(
        $_POST['titulo'],
        $_POST['descripcion'],
        $_POST['contenido'],
        $_POST['url_imagen'],
        $_POST['url_extra'], // Recogemos la URL de página oficial
        date("Y-m-d H:i:s")
    );
    if ($blog->inserta() !== false) {
        header("Location: blog_admin.php?exito=1");
        exit;
    }
}

// ── borrar noticia ──────────────────────────────────────────
if (isset($_GET['borrar'])) {
    $blog->DeleteBlog($_GET['borrar']);
    header("Location: blog_admin.php");
    exit;
}

$noticias = Blog::GetAllBlogs($conexion);
$pagina   = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Blog</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/movil.css" media="screen and (max-width: 768px)">
    <link rel="icon" href="../img/mdmicono.ico" type="image/x-icon">
    <style>
        /* Estilo de respaldo para miniatura-tabla en caso de que no esté en estilos.css */
        .miniatura-tabla {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }
        /* Ajustes adicionales para la tabla si es necesario */
        .tabla-admin td {
            vertical-align: middle;
        }
        .celda-acciones a {
            margin: 0 5px;
            text-decoration: none;
        }
        .accion-editar { color: #007bff; }
        .accion-borrar { color: #dc3545; }
    </style>
</head>
<body class="cuerpo-administracion">

    <?php include 'includes/nav.php'; ?>

    <main class="area-principal">

        <header class="encabezado-contenido">
            <section class="texto-bienvenida">
                <h1>Gestión del Blog</h1>
            </section>
        </header>

        <!-- formulario de nueva noticia -->
        <section class="tarjeta-blanca">
            <header class="cabecera-tarjeta">
                <h2 class="titulo-seccion">
                    <i class="fa-solid fa-link"></i> Publicar nueva noticia
                </h2>
            </header>

            <?php if (isset($_GET['exito'])): ?>
                <p class="aviso-ok-inline">
                    <i class="fa-solid fa-circle-check"></i> Noticia publicada correctamente.
                </p>
            <?php endif; ?>

            <form action="blog_admin.php" method="POST" class="formulario-compacto">

                <section class="campo-fila">
                    <label>Título</label>
                    <input type="text" name="titulo" required>
                </section>

                <section class="campo-fila">
                    <label>Descripción</label>
                    <input type="text" name="descripcion" required>
                </section>

                <section class="campo-fila">
                    <label>URL de la Imagen</label>
                    <input type="url" name="url_imagen" placeholder="https://ejemplo.com/imagen.jpg" required>
                </section>

                <section class="campo-fila">
                    <label>URL de Página Oficial (Leer más)</label>
                    <input type="url" name="url_extra" placeholder="https://...">
                </section>

                <section class="campo-fila">
                    <label>Contenido</label>
                    <textarea name="contenido" rows="4" required></textarea>
                </section>

                <button type="submit" name="publicar" class="boton-subir-noticia">Publicar</button>

            </form>
        </section>

        <!-- tabla de noticias existentes -->
        <section class="tarjeta-blanca">
            <table class="tabla-admin">
                <thead>
                    <tr>
                        <th>Miniatura</th>
                        <th>Título</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($noticias as $noticia): ?>
                    <tr>
                        <td>
                            <img src="<?= htmlspecialchars($noticia['url_icono']) ?>" class="miniatura-tabla" alt="Miniatura">
                        </td>
                        <td><?= htmlspecialchars($noticia['titulo']) ?></td>
                        <td class="celda-acciones">
                            <a href="../blog/editar_blog.php?id=<?= $noticia['id'] ?>&user_id=admin" class="accion-editar">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <a href="blog_admin.php?borrar=<?= $noticia['id'] ?>" class="accion-borrar">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

    </main>

</body>
</html>