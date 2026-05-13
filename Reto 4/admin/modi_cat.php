<?php
// arrancamos la sesión para comprobar que hay alguien logueado
session_start();

// si no hay sesión mandamos al login
if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit;
}

// cargamos los archivos que necesitamos para conectar y usar categorías
require_once __DIR__ . '/../class/Conexion.php';
require_once __DIR__ . '/../class/Categoria.php';
require_once __DIR__ . '/../class/Bloque.php';

// creamos la conexión a la base de datos
$db        = new Database();
$conexion  = $db->connect();
$categoria = new Categoria($conexion);

// ── CREAR CATEGORÍA ─────────────────────────────────────────
// esto solo se ejecuta cuando se envía el formulario (botón "Guardar Categoría")
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_categoria'])) {

    // recogemos lo que ha escrito la usuaria en cada campo
    $nombre      = $_POST['nombre']      ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $url_cat     = $_POST['url_cat']     ?? '';
    $url_subcat  = $_POST['url_subcat']  ?? '';
    $fecha       = date('Y-m-d');

    // si no eligió ninguna supercategoría lo dejamos como null
    // null significa que es una categoría principal (no depende de ninguna otra)
    $id_madre = !empty($_POST['id_supercat']) ? $_POST['id_supercat'] : null;

    // metemos los datos en el objeto para poder insertarlo
    $categoria->rellenarDatos($nombre, $descripcion, $url_cat, $id_madre, $url_subcat, $fecha);

    // intentamos guardar en la base de datos
    if ($categoria->inserta()) {
        // Si es una subcategoría (tiene madre) y se ha escrito contenido o URL oficial, creamos el bloque
        $id_nueva = $conexion->insert_id;
        if ($id_madre !== null && (!empty($_POST['contenido_bloque']) || !empty($_POST['url_oficial']))) {
            $bloque = new Bloque($conexion);
            // Usamos el nombre de la subcategoría como título del bloque por defecto
            $bloque->rellenarDatos($nombre, $descripcion, $id_nueva, $_POST['contenido_bloque'], $_POST['url_oficial']);
            $bloque->inserta();
        }

        // si fue bien mandamos a la lista de categorías
        echo "<script>alert('¡Categoría creada correctamente!'); window.location='lista_categorias.php';</script>";
        exit;
    } else {
        // si algo falló guardamos el mensaje de error para mostrarlo
        $error_categoria = "No se pudo crear la categoría. Revisa los campos.";
    }
}

// traemos todas las categorías principales para el selector de supercategoría
// AllCAT solo devuelve las que no tienen madre (las principales)
$listado = Categoria::AllCAT($conexion);

// guardamos el nombre del archivo actual para que el nav sepa qué enlace marcar como activo
$pagina = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Categoría - MDM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/movil.css" media="screen and (max-width: 768px)">
    <link rel="icon" href="../img/mdmicono.ico" type="image/x-icon">
</head>
<body class="cuerpo-administracion">

    <?php include 'includes/nav.php'; ?>

    <main class="area-principal">

        <header class="encabezado-contenido">
            <section class="texto-bienvenida">
                <h1>Nueva Categoría</h1>
                <p>Crea una nueva sección en la plataforma</p>
            </section>
        </header>

        <!-- formulario para crear categoría -->
        <section class="admin-zona-creacion">
            <article class="admin-formulario-moderno">

                <!-- mostramos el error si hubo algún problema al guardar -->
                <?php if (isset($error_categoria)): ?>
                    <p class="aviso-ok-inline" style="background:#fee2e2; color:#991b1b; border-color:#ef4444;">
                        <i class="fa-solid fa-circle-exclamation"></i> <?= $error_categoria ?>
                    </p>
                <?php endif; ?>

                <form action="" method="POST">

                    <p class="admin-campo-grupo">
                        <label><i class="fa-solid fa-tag"></i> Nombre</label>
                        <input type="text" name="nombre" placeholder="Nombre de la categoría..." required>
                    </p>

                    <p class="admin-campo-grupo">
                        <label><i class="fa-solid fa-align-left"></i> Descripción breve</label>
                        <input type="text" name="descripcion" placeholder="¿De qué trata?">
                    </p>

                    <fieldset class="admin-grupo-imagenes">
                        <p class="admin-campo-grupo">
                            <label><i class="fa-solid fa-image"></i> Imagen Principal (URL)</label>
                            <input type="text" name="url_cat" placeholder="https://...">
                        </p>
                        <p class="admin-campo-grupo">
                            <label><i class="fa-solid fa-file-image"></i> Miniatura (URL)</label>
                            <input type="text" name="url_subcat" placeholder="URL subcategoría...">
                        </p>
                    </fieldset>

                    <p class="admin-campo-grupo">
                        <label><i class="fa-solid fa-layer-group"></i> Categoría</label>
                        <select name="id_supercat" id="id_supercat" onchange="toggleBloque()">
                            <option value="">Nueva categoría</option>

                            <optgroup label="Categorías Añadidas:">
                                <?php foreach ($listado as $item): ?>
                                    <option value="<?= $item['id'] ?>">
                                        <?= htmlspecialchars($item['nombre_categoria']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                        </select>
                    </p>

                    <!-- Sección de texto largo y URL oficial para subcategorías -->
                    <section id="seccion_bloque" style="display: none;">
                        <p class="admin-campo-grupo">
                            <label><i class="fa-solid fa-file-lines"></i> Texto de la Subcategoría (Contenido)</label>
                            <textarea name="contenido_bloque" rows="6" placeholder="Escribe aquí el texto que aparecerá en la página de contenido..."></textarea>
                        </p>
                        <p class="admin-campo-grupo">
                            <label><i class="fa-solid fa-link"></i> URL Oficial (Link externo)</label>
                            <input type="text" name="url_oficial" placeholder="https://pagina-oficial.com">
                        </p>
                    </section>

                    <footer class="admin-form-acciones">
                        <button type="submit" name="guardar_categoria" class="admin-boton-crear">
                            <span>Guardar Categoría</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </footer>

                </form>
            </article>
        </section>

    </main>

<script>
    // Definimos la función que controla la visibilidad del bloque
    function toggleBloque() {
        // Seleccionamos el elemento <select> con id 'id_supercat'
        var select = document.getElementById('id_supercat');
        
        // Seleccionamos el bloque (div, sección, etc.) con id 'seccion_bloque'
        var seccion = document.getElementById('seccion_bloque');
        
        // Comprobamos si el valor seleccionado en el <select> NO está vacío
        // (es decir, si el usuario eligió una opción con valor distinto de "")
        if (select.value !== "") {
            // Si hay un valor, mostramos el bloque cambiando su estilo a 'block'
            seccion.style.display = "block";
        } else {
            // Si el valor está vacío (opción por defecto), ocultamos el bloque
            seccion.style.display = "none";
        }
    }
    
    // Al cargar completamente la página, ejecutamos la función toggleBloque
    // Esto asegura que el bloque aparezca oculto o visible según el estado inicial del <select>
    window.onload = toggleBloque;
</script>

</body>
</html>
