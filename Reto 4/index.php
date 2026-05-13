<?php session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Médicos del Mundo</title>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/movil.css" media="screen and (max-width: 768px)">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" href="../img/mdmicono.png" type="image/png">
    <script src="js/i18n.js" defer></script>
</head>
<body>

<!-- ══════════════════════════════════════
     CHECKBOX OCULTO — controla el cajón sin JS.
     El label con for="nav-toggle" actúa como botón de apertura/cierre.
     ══════════════════════════════════════ -->
<input type="checkbox" id="nav-toggle" style="display: none !important;">

<!-- ══════════════════════════════════════
     CABECERA HERO
     ══════════════════════════════════════ -->
<header class="contenedor-hero" id="inicio">
    <nav class="barra-nav">

        <!-- botón hamburguesa: label que activa el checkbox (solo visible en móvil) -->
        <label for="nav-toggle" class="boton-hamburguesa" aria-label="Abrir menú">
            <i class="fa-solid fa-bars"></i>
        </label>

        <!-- logo a la izquierda -->
        <img class="medicos-logo" src="img/logomedicos.png" alt="logo medicos">

        <!-- enlaces del centro -->
        <ul class="lista-navegacion">
            <li><a href="#inicio"        class="enlace-menu" data-i18n="nav_inicio">Inicio</a></li>
            <li><a href="categorias.php" class="enlace-menu" data-i18n="nav_categorias">Categorías</a></li>
            <li><a href="#sobrenosotras" class="enlace-menu" data-i18n="nav_sobre">Sobre Nosotras</a></li>
            <li><a href="blog/blog.php"  class="enlace-menu" data-i18n="nav_blog">Blog</a></li>
            <li><a href="#contacto"      class="enlace-menu" data-i18n="nav_contacto">Contacto</a></li>
        </ul>

        <!-- botón de acceso al login arriba a la derecha -->
         <?php 
         if(isset($_SESSION['usuario'])){
            echo "<a href='admin/admin.php' class='boton-acceso' data-i18n='nav_admin'>Panel Admin</a>";
         } else {
            echo "<a href='login.php' class='boton-acceso' data-i18n='nav_login'>INICIAR SESIÓN</a>";
         }
         ?>
    </nav>

    <!-- título y subtítulo hero -->
    <h1 class="titulo-gigante" data-i18n="hero_titulo">Bienvenida</h1>
    <p class="subtitulo-hero" data-i18n="hero_sub">Conoce tus derechos y encuentra el apoyo que necesitas</p>
</header>

<!-- ══════════════════════════════════════
     SECCIONES DE CONTENIDO
     ══════════════════════════════════════ -->
<main class="contenedor-secciones">

    <!-- bloque 1: derechos laborales -->
    <article class="caja-info">
        <section class="columna-texto">
            <h1 class="titulo-seccion" data-i18n="s1_titulo">Tus derechos en España</h1>
            <p data-i18n="s1_texto">En Médicos del Mundo te ayudamos a entender cómo funciona el mundo laboral. Queremos que conozcas tus derechos para evitar abusos y que sepas que tienes un equipo que te respalda.</p>
            <a href="categorias.php" class="enlace-saber-mas" data-i18n="s1_cta">Saber más <i class="fa-solid fa-arrow-right"></i></a>
        </section>
        <section class="columna-imagen">
            <img src="img/medicosdm1.jpg" alt="Atención" class="imagen-circular">
        </section>
    </article>

    <!-- bloque 2: acompañamiento (imagen a la izquierda) -->
    <article class="caja-info2">
        <section class="columna-texto">
            <h2 class="titulo-seccion" data-i18n="s2_titulo">Acción y Acompañamiento</h2>
            <p data-i18n="s2_texto">Nuestro equipo no solo te orienta, te acompaña. Estamos presentes para que tu voz sea escuchada y garantizando un acceso al trabajo digno.</p>
            <a href="blog/blog.php" class="enlace-saber-mas" data-i18n="s2_cta">Ver blog <i class="fa-solid fa-arrow-right"></i></a>
        </section>
        <section class="columna-imagen">
            <img src="img/medicosdm2.jpg" alt="Acción" class="imagen-circular">
        </section>
    </article>
<!-- bloque 3: calculadora de salario neto -->
    <article class="caja-info2">
        <section class="columna-texto">
            <h2 class="titulo-seccion">Calcula tu salario neto</h2>
            <p>Descubre cuánto cobrarás en mano introduciendo tu salario bruto y los porcentajes de
               Seguridad Social e IRPF que te aplican.</p>

            <!-- tarjeta de la calculadora -->
            <article class="calculadora">
                <h3><i class="fa-solid fa-calculator"></i> Calcula tu salario neto</h3>

                <form action="index.php" method="POST">

                    <!-- campo salario bruto -->
                    <p class="calculadora-campo">
                        <label for="sb">Salario bruto anual (€)</label>
                        <input type="number" id="sb" name="sb" placeholder="Ej: 24000" required>
                    </p>

                    <!-- campo seguridad social -->
                    <p class="calculadora-campo">
                        <label for="ss">Seguridad Social (%)</label>
                        <span class="calculadora-campo-inline">
                            <input type="number" id="ss" name="ss" placeholder="Ej: 6.35" required>
                            <span>%</span>
                        </span>
                    </p>

                    <!-- campo irpf -->
                    <p class="calculadora-campo">
                        <label for="irpf">IRPF (%)</label>
                        <span class="calculadora-campo-inline">
                            <input type="number" id="irpf" name="irpf" placeholder="Ej: 15" required>
                            <span>%</span>
                        </span>
                    </p>

                    <p>
                        <button type="submit">Calcular</button>
                    </p>

                </form>

                <!-- resultado — solo aparece si se ha enviado el formulario -->
                <?php if (isset($_POST['sb'], $_POST['ss'], $_POST['irpf'])): ?>
                    <p class="calculadora-resultado">
                        Salario neto estimado:
                        <span>
                            <?= number_format(
                                (float)$_POST['sb']
                                - ((float)$_POST['sb'] * (float)$_POST['ss']   / 100)
                                - ((float)$_POST['sb'] * (float)$_POST['irpf'] / 100),
                                2, ',', '.'
                            ) ?> €
                        </span>
                    </p>
                <?php endif; ?>

            </article>
        </section>
        <section class="columna-imagen">
            <img src="img/dineros.jpg" alt="Acción" class="imagen-circular">
        </section>
    </article>
    <!-- bloque 3: sobre nosotras -->
    <article id="sobrenosotras" class="caja-sobre-vertical">
        <img src="img/medicosmundo3.jpg" alt="Sobre nosotras" class="foto-perfil">
        <section class="texto-centrado">
            <h2 class="titulo-seccion" data-i18n="s3_titulo">Sobre Nosotras</h2>
            <p data-i18n="s3_texto">Somos una ONG de sanitarias y voluntarias. Contamos con psicólogas y orientadoras profesionales a tu disposición.</p>
        </section>
    </article>
 <!-- ══════════════════════════════════════
     SECCIÓN CONTACTO — formulario
     ══════════════════════════════════════ -->
<section class="seccion-contacto-form" id="contacto">

    <header class="contacto-form-cabecera">
        <h2><i class="fa-solid fa-envelope"></i> Escríbenos</h2>
        <p>¿Tienes alguna pregunta? Rellena el formulario y te responderemos lo antes posible.</p>
    </header>

    <?php if ($contacto_ok): ?>
        <p class="contacto-aviso-ok">
            <i class="fa-solid fa-circle-check"></i> Mensaje enviado Te responderemos pronto.
        </p>
    <?php elseif ($contacto_error): ?>
        <p class="contacto-aviso-error">
            <i class="fa-solid fa-circle-exclamation"></i> Algo ha fallado. Revisa los campos e inténtalo de nuevo.
        </p>
    <?php endif; ?>

    <form class="contacto-formulario" method="POST" action="#contacto">

        <p class="contacto-campo-grupo">
            <label for="cf-nombre"><i class="fa-solid fa-user"></i> Nombre</label>
            <input type="text" id="cf-nombre" name="nombre"
                   placeholder="Tu nombre completo" required
                   value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
        </p>

        <p class="contacto-campo-grupo">
            <label for="cf-telefono"><i class="fa-solid fa-phone"></i> Teléfono</label>
            <input type="tel" id="cf-telefono" name="telefono"
                   placeholder="600 000 000"
                   value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>">
        </p>

        <p class="contacto-campo-grupo">
            <label for="cf-email"><i class="fa-solid fa-at"></i> Correo electrónico</label>
            <input type="email" id="cf-email" name="email"
                   placeholder="*************@gmail.com" required
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </p>

        <p class="contacto-campo-grupo">
            <label for="cf-mensaje"><i class="fa-solid fa-comment-dots"></i> Mensaje</label>
            <textarea id="cf-mensaje" name="mensaje" rows="5"
                      placeholder="Escribe aquí tu mensaje..." required><?= htmlspecialchars($_POST['mensaje'] ?? '') ?></textarea>
        </p>
        <footer class="contacto-form-acciones">
            <button type="submit" name="contacto_enviar" class="contacto-boton-enviar">
                <i class="fa-solid fa-paper-plane"></i> Enviar mensaje
            </button>
        </footer>

    </form>
</main>

<!-- botón flotante de volver arriba -->
<a href="#inicio" class="boton-subir">↑</a>

<!-- ══════════════════════════════════════
     FOOTER
     ══════════════════════════════════════ -->
<footer id="contacto" class="pie-pagina">

    <section class="columna-footer">
        <img src="img/logomedicos.png" alt="logo footer" class="logo-footer-blanco">
        <p><span data-i18n="footer_tel">Contáctanos:</span> <strong>915 436 033</strong></p>
    </section>

    <section class="columna-footer">
        <h3 data-i18n="footer_sig">Síguenos</h3>
        <nav class="redes-sociales">
            <a href="https://www.instagram.com/medicosdelmundoespana/"><i class="fab fa-instagram"></i></a>
            <a href="https://x.com/medicosdelmundo?lang=es"><i class="fa-brands fa-twitter"></i></a>
            <a href="https://www.facebook.com/medicosdelmundo.espana/"><i class="fab fa-facebook"></i></a>
        </nav>
    </section>

    <section class="columna-footer">
        <h3 data-i18n="footer_enc">Encuéntranos</h3>
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5961.861824259072!2d-0.8918133!3d41.6572347!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd5914eaa1fa8ccd%3A0x64aae6a11ea538f3!2sM%C3%A9dicos%20del%20Mundo%20Zaragoza%2C%20Arag%C3%B3n!5e0!3m2!1ses!2ses!4v1775945375644!5m2!1ses!2ses"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </section>

</footer>

<!-- ══════════════════════════════════════
     CAPA OSCURA — label para cerrar el cajón al pulsar fuera
     ══════════════════════════════════════ -->
<label for="nav-toggle" class="overlay-movil"></label>

<!-- ══════════════════════════════════════
     CAJÓN DE NAVEGACIÓN LATERAL (móvil)
     Controlado íntegramente por CSS mediante #nav-toggle:checked
     ══════════════════════════════════════ -->
<nav class="cajon-movil">

    <!-- cabecera del cajón: logo y botón cerrar -->
    <div class="cajon-cabecera">
        <img src="img/logomedicos.png" alt="Logo MDM">
        <!-- botón X: otro label que desmarca el checkbox y cierra el cajón -->
        <label for="nav-toggle" class="boton-cerrar-cajon" aria-label="Cerrar menú">
            <i class="fa-solid fa-xmark"></i>
        </label>
    </div>

    <!-- enlaces de navegación -->
    <ul class="cajon-nav">
        <li><a href="#inicio">        <i class="fa-solid fa-house"></i>               <span data-i18n="nav_inicio">Inicio</span></a></li>
        <li><a href="categorias.php"> <i class="fa-solid fa-list"></i>                <span data-i18n="nav_categorias">Categorías</span></a></li>
        <li><a href="#sobrenosotras"> <i class="fa-solid fa-heart"></i>               <span data-i18n="nav_sobre">Sobre Nosotras</span></a></li>
        <li><a href="blog/blog.php">  <i class="fa-solid fa-newspaper"></i>           <span data-i18n="nav_blog">Blog</span></a></li>
        <li><a href="#contacto">      <i class="fa-solid fa-phone"></i>               <span data-i18n="nav_contacto">Contacto</span></a></li>
        <li><a href="login.php">      <i class="fa-solid fa-right-to-bracket"></i>    <span data-i18n="nav_login">Iniciar Sesión</span></a></li>
    </ul>

    <!-- pie del cajón con acceso al login -->
    <div class="cajon-pie">
        <a href="login.php">
            <i class="fa-solid fa-right-to-bracket"></i>
            <span data-i18n="nav_login">Iniciar Sesión</span>
        </a>
    </div>

</nav>

</body>
</html>
