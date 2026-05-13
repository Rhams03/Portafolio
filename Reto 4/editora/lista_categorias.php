<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../class/Conexion.php';
require_once __DIR__ . '/../class/Categoria.php';

$db   = new Database();
$conn = $db->connect();

// BORRAR CATEGORIA
if (isset($_GET['borrar_cat'])) {
    $id_borrar = (int) $_GET['borrar_cat'];
    if (Categoria::deleteWithSubcategories($conn, $id_borrar)) {
        header("Location: lista_categorias.php?borrado_cat=1");
        exit;
    }
}

// CARGAR CATEGORÍAS
$categorias_principales = Categoria::AllCAT($conn);
$todas_subcats          = Categoria::AllSubCAT($conn);

// Separamos subcategorías de sub-subcategorías:
$ids_principales = array_column($categorias_principales, 'id');

$subcategorias    = [];
$subsubcategorias = [];

foreach ($todas_subcats as $s) {
    if (in_array($s['id_madre'], $ids_principales)) {
        $subcategorias[] = $s;
    } else {
        $subsubcategorias[] = $s;
    }
}

$pagina = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Categorías - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/movil.css" media="screen and (max-width: 768px)">
</head>
<body class="cuerpo-administracion">

    <?php include 'includes/nav.php'; ?>

    <main class="area-principal">

        <header class="encabezado-contenido">
            <section class="texto-bienvenida">
                <h1>Modificar Categorías</h1>
                <p>Gestiona las secciones y subsecciones de la web.</p>
            </section>

            <form class="admin-buscador-formulario" onsubmit="return false;">
                <input type="text" placeholder="Buscar categoría..." onkeyup="filtrar(this.value)">
                <i class="fa-solid fa-magnifying-glass"></i>
            </form>
        </header>

        <?php if (isset($_GET['borrado_cat'])): ?>
            <p class="aviso-ok-inline">Categoría eliminada correctamente.</p>
        <?php endif; ?>

        <!-- ── CATEGORÍAS PRINCIPALES ── -->
        <h2 class="titulo-seccion">
            <i class="fa-solid fa-folder"></i> Categorías Principales
        </h2>

        <section class="admin-grid-categorias">
            <?php foreach ($categorias_principales as $c):
                $datos = (new Categoria($conn))->obtenerPorId($c['id']);
            ?>
                <article class="admin-tarjeta-categoria etiqueta" data-nombre="<?= mb_strtolower(htmlspecialchars($datos['nombre_categoria']), 'UTF-8') ?>">
                    <header class="admin-tarjeta-header">
                        <img src="<?= htmlspecialchars($datos['url_catIcono']) ?>" alt="Icono">
                    </header>
                    <section class="admin-tarjeta-cuerpo">
                        <h3><?= htmlspecialchars($datos['nombre_categoria']) ?></h3>
                        <p><?= htmlspecialchars($datos['descripcion']) ?></p>
                    </section>
                    <footer class="admin-tarjeta-acciones" style="display:flex; gap:10px;">
                        <a href="editar_categoria.php?id=<?= $datos['id'] ?>"
                           class="admin-boton-editar-card" style="flex:1;">
                            <i class="fa-solid fa-pen"></i> Modificar
                        </a>
                        <a href="?borrar_cat=<?= $datos['id'] ?>"
                           class="boton-borrar-categoria" style="flex:1;"
                           onclick="return confirm('¿Borrar categoría y todas sus subcategorías?')">
                            <i class="fa-solid fa-trash"></i> Borrar
                        </a>
                    </footer>
                </article>
            <?php endforeach; ?>
        </section>

        <!-- ── SUBCATEGORÍAS ── -->
        <h2 class="titulo-seccion">
            <i class="fa-solid fa-folder-tree"></i> Subcategorías
        </h2>

        <section class="admin-grid-categorias">
            <?php if (empty($subcategorias)): ?>
                <p style="color:#888;">No hay subcategorías creadas aún.</p>
            <?php else: ?>
                <?php foreach ($subcategorias as $s): ?>
                    <article class="admin-tarjeta-categoria etiqueta" data-nombre="<?= mb_strtolower(htmlspecialchars($s['nombre_categoria']), 'UTF-8') ?>">
                        <header class="admin-tarjeta-header">
                            <img src="<?= htmlspecialchars($s['url_catIcono']) ?>" alt="Icono">
                        </header>
                        <section class="admin-tarjeta-cuerpo">
                            <p><strong>Categoría:</strong> <?= htmlspecialchars($s['nombre_madre']) ?></p>
                            <h3><?= htmlspecialchars($s['nombre_categoria']) ?></h3>
                            <p><?= htmlspecialchars($s['descripcion']) ?></p>
                        </section>
                        <footer class="admin-tarjeta-acciones" style="display:flex; gap:10px;">
                            <a href="editar_categoria.php?id=<?= $s['id'] ?>"
                               class="admin-boton-editar-card" style="flex:1;">
                                <i class="fa-solid fa-pen"></i> Modificar
                            </a>
                            <a href="?borrar_cat=<?= $s['id'] ?>"
                               class="boton-borrar-categoria" style="flex:1;"
                               onclick="return confirm('¿Borrar subcategoría?')">
                                <i class="fa-solid fa-trash"></i> Borrar
                            </a>
                        </footer>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

        <!-- ── SUB-SUBCATEGORÍAS ── -->
        <h2 class="titulo-seccion">
            <i class="fa-solid fa-sitemap"></i> Sub-subcategorías
        </h2>

        <section class="admin-grid-categorias">
            <?php if (empty($subsubcategorias)): ?>
                <p style="color:#888;">No hay sub-subcategorías creadas aún.</p>
            <?php else: ?>
                <?php foreach ($subsubcategorias as $ss): ?>
                    <article class="admin-tarjeta-categoria etiqueta" data-nombre="<?= mb_strtolower(htmlspecialchars($ss['nombre_categoria']), 'UTF-8') ?>">
                        <header class="admin-tarjeta-header">
                            <img src="<?= htmlspecialchars($ss['url_catIcono']) ?>" alt="Icono">
                        </header>
                        <section class="admin-tarjeta-cuerpo">
                            <p><strong>Subcategoría:</strong> <?= htmlspecialchars($ss['nombre_madre']) ?></p>
                            <h3><?= htmlspecialchars($ss['nombre_categoria']) ?></h3>
                            <p><?= htmlspecialchars($ss['descripcion']) ?></p>
                        </section>
                        <footer class="admin-tarjeta-acciones" style="display:flex; gap:10px;">
                            <a href="editar_categoria.php?id=<?= $ss['id'] ?>"
                               class="admin-boton-editar-card" style="flex:1;">
                                <i class="fa-solid fa-pen"></i> Modificar
                            </a>
                            <a href="?borrar_cat=<?= $ss['id'] ?>"
                               class="boton-borrar-categoria" style="flex:1;"
                               onclick="return confirm('¿Borrar sub-subcategoría?')">
                                <i class="fa-solid fa-trash"></i> Borrar
                            </a>
                        </footer>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

    </main>

<script>
    function filtrar(valor) {
        var texto = valor.toLowerCase();
        document.querySelectorAll('.etiqueta').forEach(function(el) {
            el.style.display = el.dataset.nombre.includes(texto) ? '' : 'none';
        });
    }
</script>

</body>
</html>