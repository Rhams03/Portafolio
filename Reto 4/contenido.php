<?php
require_once "class/Conexion.php";
require_once "class/Categoria.php";

$conexion = (new Database())->connect();

// recogemos el id desde la url
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// si no hay id volvemos a categorías
if (!$id) {
    header("Location: categorias.php");
    exit;
}

// buscamos los datos de la categoría con ese id
$categoria = null;
$todas     = Categoria::getDatosIndexcat($conexion);

foreach ($todas as $cat) {
    if ((int)$cat['id'] === $id) {
        $categoria = $cat;
        break;
    }
}

// si no existe la categoría volvemos
if (!$categoria) {
    header("Location: categorias.php");
    exit;
}

// buscamos los bloques de texto de esta categoría en la tabla bloque
$sql  = "SELECT id, titulo, descripcion, contenido, url_oficial FROM bloque WHERE id_categoria = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$bloques   = [];
while ($fila = $resultado->fetch_assoc()) {
    $bloques[] = $fila;
}

// enlace para volver: si tiene madre volvemos a subcategorías, si no a categorías
$id_madre      = $categoria['id_madre'];
$enlace_volver = $id_madre ? "subcategoria.php?id=" . $id_madre : "categorias.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($categoria['nombre_categoria']) ?> - Médicos del Mundo</title>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/movil.css" media="screen and (max-width: 768px)">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" href="img/mdmicono.ico" type="image/x-icon">
</head>
<body class="pagina-contenido">

    <header class="cabecera-minimal">
        <section class="contenedor-header">
            <a href="<?= $enlace_volver ?>" class="btn-volver-atras">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </a>
            <span class="titulo-cabecera"><?= htmlspecialchars($categoria['nombre_categoria']) ?></span>
        </section>
    </header>

    <main class="contenido-principal-wrapper">

        <!-- hero con imagen de fondo y título -->
        <?php if (!empty($categoria['url_catIcono'])): ?>
            <section class="contenido-hero">
                <div class="contenido-hero-imagen"
                     style="background-image: url('<?= htmlspecialchars($categoria['url_catIcono']) ?>')">
                </div>
                <div class="contenido-hero-overlay"></div>
                <div class="contenido-hero-texto">
                    <h1><?= htmlspecialchars($categoria['nombre_categoria']) ?></h1>
                    <?php if (!empty($categoria['descripcion'])): ?>
                        <p><?= htmlspecialchars($categoria['descripcion']) ?></p>
                    <?php endif; ?>
                </div>
            </section>
        <?php endif; ?>

        <!-- bloques de texto (vienen de la tabla bloque en la BD) -->
        <section class="contenido-bloques">
            <?php if (empty($bloques)): ?>
                <div class="contenido-vacio">
                    <i class="fa-solid fa-clock"></i>
                    <p>Estamos preparando esta información. ¡Vuelve pronto!</p>
                </div>
            <?php else: ?>
                <?php foreach ($bloques as $bloque): ?>
                    <article class="bloque-info">

                        <!-- cabecera: icono + título del bloque -->
                        <header class="bloque-info-header">
                            <div class="bloque-icono">
                                <i class="fa-solid fa-circle-info"></i>
                            </div>
                            <h2><?= htmlspecialchars($bloque['titulo']) ?></h2>
                        </header>

                        <?php if (!empty($bloque['descripcion'])): ?>
                            <p class="bloque-descripcion">
                                <?= htmlspecialchars($bloque['descripcion']) ?>
                            </p>
                        <?php endif; ?>

                        <!-- contenido largo — nl2br respeta los saltos de línea de la BD -->
                        <div class="bloque-cuerpo">
                            <?= nl2br(htmlspecialchars($bloque['contenido'])) ?>
                        </div>

                        <!-- Link oficial si existe -->
                        <?php if (!empty($bloque['url_oficial'])): ?>
                            <div class="bloque-enlace-oficial" style="margin-top: 20px; text-align: center;">
                                <a href="<?= htmlspecialchars($bloque['url_oficial']) ?>" target="_blank" class="btn-volver-grande" style="background: #0056b3; color: white; text-decoration: none; padding: 10px 20px; border-radius: 5px; display: inline-block;">
                                    <i class="fa-solid fa-external-link"></i> Visitar Página Oficial
                                </a>
                            </div>
                        <?php endif; ?>

                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

        <!-- botón para volver -->
        <div class="contenido-footer-nav">
            <a href="<?= $enlace_volver ?>" class="btn-volver-grande">
                <i class="fa-solid fa-arrow-left"></i> Volver
            </a>
        </div>

    </main>

</body>
</html>
