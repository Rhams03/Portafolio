<?php
// nav.php — menú lateral compartido por todas las páginas del admin
// antes de incluirlo, la página ya tiene que tener:  $pagina = basename($_SERVER['PHP_SELF']);

$paginas_cat = ['modi_cat.php', 'lista_categorias.php', 'editar_categoria.php'];
$esta_en_cat = in_array($pagina, $paginas_cat);
?>

<input type="checkbox" id="admin-nav-toggle" class="nav-toggle-input">

<!-- ══════════════════════════════════════
     SIDEBAR ESCRITORIO
     ══════════════════════════════════════ -->
<aside class="navegacion-lateral">

    <header class="cabecera-navegacion">
        <span class="nombre-logo">
            <i class="fa-solid fa-user"></i> Administradora
        </span>
    </header>

    <nav class="lista-opciones">
        <span class="etiqueta-seccion">Navegación</span>
        <ul>

            <li>
                <a href="/admin/admin.php" class="<?= ($pagina == 'admin.php') ? 'enlace-activo' : '' ?>">
                    <i class="fa-solid fa-circle-user"></i> Inicio
                </a>
            </li>

            <li>
                <a href="/admin/gestion_usuarias.php" class="<?= ($pagina == 'gestion_usuarias.php') ? 'enlace-activo' : '' ?>">
                    <i class="fa-solid fa-users-gear"></i> Gestionar Usuarias
                </a>
            </li>

            <li>
                <a href="/admin/blog_admin.php" class="<?= ($pagina == 'blog_admin.php') ? 'enlace-activo' : '' ?>">
                    <i class="fa-solid fa-pen-to-square"></i> Gestionar Blog
                </a>
            </li>

            <li class="opcion-desplegable">
                <details <?= $esta_en_cat ? 'open' : '' ?>>
                    <summary class="<?= $esta_en_cat ? 'enlace-activo' : '' ?>">
                        <i class="fa-solid fa-list-check"></i> Categorías
                    </summary>
                    <ul class="sublista-categorias">
                        <li>
                            <a href="/admin/modi_cat.php" class="<?= ($pagina == 'modi_cat.php') ? 'enlace-activo' : '' ?>">
                                Añadir Categoría
                            </a>
                        </li>
                        <li>
                            <a href="/admin/lista_categorias.php" class="<?= ($pagina == 'lista_categorias.php') ? 'enlace-activo' : '' ?>">
                                Modificar Categorías
                            </a>
                        </li>
                    </ul>
                </details>
            </li>

        </ul>
    </nav>

    <footer class="pie-navegacion">
        <a href="../index.php" class="boton-volver-inicio">
            <i class="fa-solid fa-house"></i> Volver a Inicio
        </a>
        <a href="../logout.php" class="boton-cerrar-sesion">
            <i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar Sesión
        </a>
    </footer>

</aside>

<!-- ══════════════════════════════════════
     CAPA OSCURA MÓVIL
     ══════════════════════════════════════ -->
<label for="admin-nav-toggle" class="overlay-movil"></label>

<!-- ══════════════════════════════════════
     CAJÓN LATERAL MÓVIL
     ══════════════════════════════════════ -->
<nav class="cajon-movil">

    <header class="cajon-cabecera">
        <span style="color:white; font-weight:700; font-size:15px;">
            <i class="fa-solid fa-user"></i> Administradora
        </span>
        <label for="admin-nav-toggle" class="boton-cerrar-cajon" aria-label="Cerrar menú">
            <i class="fa-solid fa-xmark"></i>
        </label>
    </header>

    <ul class="cajon-nav">
        <li><a href="admin.php"><i class="fa-solid fa-circle-user"></i> Inicio</a></li>
        <li><a href="gestion_usuarias.php"><i class="fa-solid fa-users-gear"></i> Gestionar Usuarias</a></li>
        <li><a href="blog_admin.php"><i class="fa-solid fa-pen-to-square"></i> Gestionar Blog</a></li>
        <li><a href="modi_cat.php"><i class="fa-solid fa-plus"></i> Añadir Categoría</a></li>
        <li><a href="lista_categorias.php"><i class="fa-solid fa-list-check"></i> Modificar Categorías</a></li>
    </ul>

    <footer class="cajon-pie">
        <a href="../index.php" class="boton-volver-inicio">
            <i class="fa-solid fa-house"></i> Volver a Inicio
        </a>
        <a href="../logout.php">
            <i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar Sesión
        </a>
    </footer>

</nav>

<!-- ══════════════════════════════════════
     BARRA SUPERIOR MÓVIL
     ══════════════════════════════════════ -->
<header class="barra-admin-movil">
    <span><i class="fa-solid fa-user"></i> Panel Admin</span>
    <label for="admin-nav-toggle" class="boton-hamburguesa-admin" aria-label="Abrir menú">
        <i class="fa-solid fa-bars"></i>
    </label>
</header>