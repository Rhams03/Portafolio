<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../class/Conexion.php';
require_once __DIR__ . '/../class/Categoria.php';
require_once __DIR__ . '/../class/Bloque.php';

$db   = new Database();
$conn = $db->connect();
$cat  = new Categoria($conn);

// recogemos el id de la url
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    header("Location: lista_categorias.php");
    exit;
}

// si se ha enviado el formulario actualizamos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre   = $_POST['nombre']      ?? '';
    $desc     = $_POST['descripcion'] ?? '';
    $url_cat  = $_POST['url_cat']     ?? '';
    $url_sub  = $_POST['url_subcat']  ?? '';
    $id_madre = (!empty($_POST['id_madre'])) ? (int)$_POST['id_madre'] : null;

    $resultado = $cat->actualizar($id, $nombre, $desc, $url_cat, $url_sub, $id_madre);

    if ($resultado === true) {
        // Si es una subcategoría (tiene madre) y se ha escrito contenido o URL oficial, gestionamos el bloque
        if ($id_madre !== null && (isset($_POST['contenido_bloque']) || isset($_POST['url_oficial']))) {
            $bloque_existente = Bloque::FindblocDeCat($conn, $id);
            if ($bloque_existente) {
                $bloque_obj = new Bloque($conn);
                $bloque_obj->updateBloque($bloque_existente['id'], $nombre, $desc, $_POST['contenido_bloque'], $_POST['url_oficial']);
            } else {
                $bloque_obj = new Bloque($conn);
                $bloque_obj->rellenarDatos($nombre, $desc, $id, $_POST['contenido_bloque'], $_POST['url_oficial']);
                $bloque_obj->inserta();
            }
        }

        echo "<script>alert('Categoría actualizada correctamente'); window.location='lista_categorias.php';</script>";
        exit;
    } else {
        $error = $resultado;
    }
}

// cargamos los datos actuales para rellenar el formulario
$datos = $cat->obtenerPorId($id);

if (!$datos) {
    header("Location: lista_categorias.php");
    exit;
}

// Buscamos si tiene un bloque de contenido (solo para subcategorías)
$bloque_datos = null;
if ($datos['id_madre'] !== null) {
    $bloque_datos = Bloque::FindblocDeCat($conn, $id);
}

// todas las categorías para el select (ahora incluye id_madre para separar niveles)
$opciones = Categoria::AllCATforModi($conn);

// ── Separamos en tres niveles ──
$ids_principales = array_column(
    array_filter($opciones, fn($o) => $o['id_madre'] === null),
    'id'
);
$ids_subcats = array_column(
    array_filter($opciones, fn($o) => $o['id_madre'] !== null && in_array($o['id_madre'], $ids_principales)),
    'id'
);

$principales  = array_filter($opciones, fn($o) => $o['id_madre'] === null);
$subcats      = array_filter($opciones, fn($o) => $o['id_madre'] !== null && in_array($o['id_madre'], $ids_principales));
$subsubcats   = array_filter($opciones, fn($o) => $o['id_madre'] !== null && in_array($o['id_madre'], $ids_subcats));

$pagina = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoría - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/movil.css" media="screen and (max-width: 768px)">
</head>
<body class="cuerpo-administracion">

    <?php include 'includes/nav.php'; ?>

    <main class="area-principal">
        <header class="encabezado-contenido">
            <section class="texto-bienvenida">
                <h1>Editando: <?= htmlspecialchars($datos['nombre_categoria']) ?></h1>
            </section>
        </header>

        <?php if (isset($error)): ?>
            <p class="aviso-error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <section class="admin-zona-creacion">
            <article class="admin-formulario-moderno">

                <a href="lista_categorias.php" class="admin-link-volver">
                    <i class="fa-solid fa-arrow-left"></i> Volver al listado
                </a>

                <form action="" method="POST">

                    <p class="admin-campo-grupo">
                        <label>Nombre</label>
                        <input type="text" name="nombre"
                               value="<?= htmlspecialchars($datos['nombre_categoria']) ?>" required>
                    </p>

                    <p class="admin-campo-grupo">
                        <label>Descripción breve</label>
                        <input type="text" name="descripcion"
                               value="<?= htmlspecialchars($datos['descripcion']) ?>">
                    </p>

                    <fieldset class="admin-grupo-imagenes">
                        <p class="admin-campo-grupo">
                            <label>URL Imagen Principal</label>
                            <input type="text" name="url_cat"
                                   value="<?= htmlspecialchars($datos['url_catIcono'] ?? '') ?>"
                                   placeholder="https://...">
                        </p>
                        <p class="admin-campo-grupo">
                            <label>URL Miniatura</label>
                            <input type="text" name="url_subcat"
                                   value="<?= htmlspecialchars($datos['url_subcatIcono'] ?? '') ?>"
                                   placeholder="https://...">
                        </p>
                    </fieldset>

                    <p class="admin-campo-grupo">
                        <label>Categoría General</label>
                        <select name="id_madre" id="id_madre" onchange="toggleBloque()">

                            <option value="">Sin Categoría</option>

                            <?php if (!empty($principales)): ?>
                                <optgroup label="── Categorías principales">
                                    <?php foreach ($principales as $opc): ?>
                                        <?php if ($opc['id'] == $id) continue; ?>
                                        <option value="<?= $opc['id'] ?>"
                                            <?= ($opc['id'] == $datos['id_madre']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($opc['nombre_categoria']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endif; ?>

                            <?php if (!empty($subcats)): ?>
                                <optgroup label="── Subcategorías">
                                    <?php foreach ($subcats as $opc): ?>
                                        <?php if ($opc['id'] == $id) continue; ?>
                                        <option value="<?= $opc['id'] ?>"
                                            <?= ($opc['id'] == $datos['id_madre']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($opc['nombre_categoria']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endif; ?>

                            <?php if (!empty($subsubcats)): ?>
                                <optgroup label="── Sub-subcategorías">
                                    <?php foreach ($subsubcats as $opc): ?>
                                        <?php if ($opc['id'] == $id) continue; ?>
                                        <option value="<?= $opc['id'] ?>"
                                            <?= ($opc['id'] == $datos['id_madre']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($opc['nombre_categoria']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endif; ?>

                        </select>
                    </p>

                    <!-- Sección de texto largo y URL oficial para subcategorías -->
                    <section id="seccion_bloque" style="display: <?= ($datos['id_madre'] !== null) ? 'block' : 'none' ?>;">
                        <p class="admin-campo-grupo">
                            <label><i class="fa-solid fa-file-lines"></i> Texto de la Subcategoría (Contenido)</label>
                            <textarea name="contenido_bloque" rows="8"
                                      placeholder="Escribe aquí el texto que aparecerá en la página de contenido..."><?= htmlspecialchars($bloque_datos['contenido'] ?? '') ?></textarea>
                        </p>
                        <p class="admin-campo-grupo">
                            <label><i class="fa-solid fa-link"></i> URL Oficial (Link externo)</label>
                            <input type="text" name="url_oficial"
                                   value="<?= htmlspecialchars($bloque_datos['url_oficial'] ?? '') ?>"
                                   placeholder="https://pagina-oficial.com">
                        </p>
                    </section>

                    <footer class="admin-form-acciones">
                        <button type="submit" class="admin-boton-crear">
                            <i class="fa-solid fa-save"></i> Guardar Cambios
                        </button>
                    </footer>

                </form>
            </article>
        </section>
    </main>

<script>
    function toggleBloque() {
        var select  = document.getElementById('id_madre');
        var seccion = document.getElementById('seccion_bloque');
        seccion.style.display = (select.value !== "") ? "block" : "none";
    }
</script>

</body>
</html>
