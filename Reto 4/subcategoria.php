<?php
require_once "class/Conexion.php";
require_once "class/Categoria.php";

$conexion = (new Database())->connect();

// recogemos el id de la categoría madre desde la url
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// si no hay id volvemos a categorías
if (!$id) {
    header("Location: categorias.php");
    exit;
}

// buscamos las subcategorías que tienen este id como madre
$subcategorias = Categoria::getsubDatos($id, $conexion);

// buscamos el nombre de la categoría madre para la cabecera
$nombre_madre = '';
$todas        = Categoria::getDatosIndexcat($conexion);
foreach ($todas as $cat) {
    if ((int)$cat['id'] === $id) {
        $nombre_madre = $cat['nombre_categoria'];
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($nombre_madre) ?> - Médicos del Mundo</title>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/movil.css" media="screen and (max-width: 768px)">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" href="img/mdmicono.ico" type="image/x-icon">
</head>
<body class="pagina-categorias">

    <header class="cabecera-minimal">
        <section class="contenedor-header">
            <a href="categorias.php" class="btn-volver-atras">
                <i class="fa-solid fa-arrow-left"></i> Volver a categorías
            </a>
            <?php if ($nombre_madre): ?>
                <span class="titulo-cabecera"><?= htmlspecialchars($nombre_madre) ?></span>
            <?php endif; ?>
        </section>
    </header>

    <div class="zona-busqueda">
        <div class="barra-busqueda-input">
            <input type="text" id="buscador" placeholder="¿Qué necesitas buscar?" oninput="filtrar(this.value)">
            <button type="button"><i class="fa-solid fa-magnifying-glass"></i></button>
        </div>
    </div>

    <main class="grid-subcategorias" id="grid-subcategorias">

        <?php if (empty($subcategorias)): ?>
            <p class="aviso-vacio">No hay subcategorías disponibles todavía.</p>
        <?php else: ?>
            <?php foreach ($subcategorias as $sub): ?>
                <a href="<?= Categoria::getEnlace($sub, $conexion) ?>"
                   class="tarjeta-subcat-nueva"
                   data-nombre="<?= strtolower(htmlspecialchars($sub['nombre_categoria'])) ?>">

                    <!-- imagen arriba de la tarjeta — columna url_catIcono de la BD -->
                    <div class="subcat-imagen-wrap">
                        <?php if (!empty($sub['url_catIcono'])): ?>
                            <img src="<?= htmlspecialchars($sub['url_catIcono']) ?>"
                                 alt="<?= htmlspecialchars($sub['nombre_categoria']) ?>">
                        <?php else: ?>
                            <div class="subcat-sin-imagen">
                                <i class="fa-solid fa-file-lines"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- texto debajo de la imagen -->
                    <div class="subcat-texto">
                        <h2><?= htmlspecialchars($sub['nombre_categoria']) ?></h2>
                        <span><?= htmlspecialchars($sub['descripcion']) ?></span>
                    </div>

                </a>
            <?php endforeach; ?>
        <?php endif; ?>

    </main>

<script>
    // BUSCADOR: función que filtra tarjetas según el texto ingresado
    function filtrar(valor) {
        // Convertimos el valor ingresado (lo que escribe el usuario) a minúsculas
        // para que la búsqueda no distinga entre mayúsculas y minúsculas
        var texto = valor.toLowerCase();
        
        // Seleccionamos todos los elementos que tienen la clase 'tarjeta-subcat-nueva'
        // y por cada uno de ellos ejecutamos una función
        document.querySelectorAll('.tarjeta-subcat-nueva').forEach(function(el) {
            // Comparamos el texto del atributo 'data-nombre' del elemento (convertido a minúsculas)
            // con el texto buscado. Si el texto buscado está contenido en 'data-nombre',
            // mostramos el elemento (display = '' que restaura el valor por defecto),
            // de lo contrario lo ocultamos (display = 'none')
            el.style.display = el.dataset.nombre.includes(texto) ? '' : 'none';
        });
    }
</script>

</body>
</html>
