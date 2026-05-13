<?php
// cargamos el archivo que tiene la clase para conectar a la base de datos
require_once "class/Conexion.php";

// cargamos el archivo que tiene la clase Categoria, con todos sus métodos
require_once "class/Categoria.php";

// creamos una nueva conexión a la base de datos
$conexion = (new Database())->connect();

// pedimos a la base de datos TODAS las categorías que existen
$todas = Categoria::getDatosIndexcat($conexion);

// ahora filtramos: solo nos quedamos con las que NO tienen categoría madre
// id_madre === null significa que es una categoría principal (no es subcategoría de otra)
$categorias = array_filter($todas, function($c) {
    return $c['id_madre'] === null;
});
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías - Médicos del Mundo</title>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/movil.css" media="screen and (max-width: 768px)">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" href="img/mdmicono.png" type="image/png">
    <script src="js/i18n.js" defer></script>
</head>
<body class="pagina-categorias">

    <!-- cabecera con gradiente -->
    <header class="cabecera-minimal">
        <section class="contenedor-header">
            <a href="index.php" class="btn-volver-atras">
                <i class="fa-solid fa-arrow-left"></i>
                <span data-i18n="nav_volver">Volver a inicio</span>
            </a>
            <span class="titulo-cabecera" data-i18n="cat_titulo">Categorías</span>
        </section>
    </header>

    <!-- buscador -->
    <section class="zona-busqueda">
        <section class="barra-busqueda-input">
            <input type="text" id="buscador" placeholder="¿Qué necesitas buscar?" data-i18n="cat_buscador" oninput="filtrar(this.value)">
            <button type="button"><i class="fa-solid fa-magnifying-glass"></i></button>
        </section>
    </section>

    <!-- grid de círculos con imagen de fondo -->
    <main class="grid-etiquetas" id="grid-categorias">

        <?php
        // recorremos cada categoría principal una a una
        foreach ($categorias as $c):

            // getEnlace() mira si esta categoría tiene subcategorías dentro
            // si las tiene → enlaza a subcategoria.php?id=...
            // si no las tiene → enlaza a contenido.php?id=...
            $enlace = Categoria::getEnlace($c, $conexion);

            // guardamos el nombre en minúsculas para usarlo en el buscador (atributo data-nombre)
            $nombre_busqueda = strtolower(htmlspecialchars($c['nombre_categoria']));

            // htmlspecialchars() convierte caracteres especiales como < > & en texto seguro
            $nombre      = htmlspecialchars($c['nombre_categoria']);
            $descripcion = htmlspecialchars($c['descripcion']);
            $imagen      = htmlspecialchars($c['url_catIcono']);
        ?>

            <!-- cada enlace es una tarjeta circular, data-nombre se usa para el buscador -->
            <a href="<?= $enlace ?>" class="etiqueta" data-nombre="<?= $nombre_busqueda ?>">
                <article class="circulo-card">

                    <!-- anillo animado decorativo alrededor del círculo -->
                    <span class="ring-pulse"></span>

                    <!-- imagen de fondo del círculo, viene del campo url_catIcono de la BD -->
                    <?php if (!empty($imagen)): ?>
                        <span class="circulo-imagen" style="background-image: url('<?= $imagen ?>')"></span>
                    <?php endif; ?>

                    <!-- sombra oscura encima de la imagen para que se lea el texto -->
                    <span class="circulo-overlay"></span>

                    <!-- texto: nombre arriba y descripción debajo -->
                    <!-- data-ai-translate → Claude traducirá estos textos al cambiar de idioma -->
                    <footer class="circulo-texto">
                        <h2 data-ai-translate><?= $nombre ?></h2>
                        <p  data-ai-translate><?= $descripcion ?></p>
                    </footer>

                </article>
            </a>

        <?php endforeach; ?>
        <!-- fin del bucle, ya hemos pintado todas las categorías -->

    </main>

    <script>
        // ── BUSCADOR ──────────────────────────────────────────────────────────
        // Filtra por el texto visible actual (funciona tanto en español
        // como en el idioma traducido por Claude).
        function filtrar(valor) {
            var texto = valor.toLowerCase();
            document.querySelectorAll('.etiqueta').forEach(function(el) {
                // Busca en data-nombre (texto del idioma activo, actualizado abajo)
                el.style.display = el.dataset.nombre.includes(texto) ? '' : 'none';
            });
        }

        // ── SINCRONIZAR data-nombre CON LA TRADUCCIÓN IA ─────────────────────
        // Cuando Claude traduce los <h2 data-ai-translate>, actualizamos
        // data-nombre en la tarjeta padre para que el buscador siga funcionando.
        document.querySelectorAll('.etiqueta').forEach(function(tarjeta) {
            var h2 = tarjeta.querySelector('h2[data-ai-translate]');
            if (!h2) return;

            new MutationObserver(function() {
                tarjeta.dataset.nombre = h2.textContent.trim().toLowerCase();
            }).observe(h2, { childList: true, characterData: true, subtree: true });
        });
    </script>

</body>
</html>
