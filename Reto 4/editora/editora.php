<?php
// arrancamos la sesión
session_start();

// si no hay sesión activa mandamos al login
if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit;
}

// cargamos las clases necesarias
require_once __DIR__ . '/../class/Conexion.php';
require_once __DIR__ . '/../class/Usuaria.php';

// conectamos a la base de datos
$db       = new Database();
$conexion = $db->connect();

// buscamos los datos del perfil de quien está logueada
$perfil = Usuaria::FindByNombre($conexion, $_SESSION['usuario']);

// si se ha enviado el formulario de actualizar perfil
if (isset($_POST['guardar_perfil'])) {

    $nuevo_nombre   = $_POST['nombre'];
    $nueva_foto     = $_POST['url_foto'];
    $nueva_password = $_POST['pass'];

    // actualizamos en la base de datos
    Usuaria::UpdatePerfil($conexion, $_SESSION['usuario_id'], $nuevo_nombre, $nueva_password);

    // Actualizmos la foto de perfil si proporcionó una
    if(!empty($nueva_foto)){
        Usuaria::updateFoto($conexion, $_SESSION['usuario_id'], $nueva_foto);
    }
    // actualizamos el nombre en sesión por si lo ha cambiado
    $_SESSION['usuario'] = $nuevo_nombre;

    // recargamos la página
    header('Location: editora.php?ok=1');
    exit;
}

// nombre del archivo para marcar el enlace activo en el nav
$pagina = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - MDM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/movil.css" media="screen and (max-width: 768px)">
    <link rel="icon" href="../img/mdmicono.ico" type="image/x-icon">
</head>
<body class="cuerpo-administracion">

    <!-- menú lateral -->
    <?php include 'includes/nav.php'; ?>

    <!-- área principal -->
    <main class="area-principal">

        <!-- cabecera de bienvenida -->
        <header class="encabezado-contenido">
            <section class="texto-bienvenida">
                <h1>Bienvenida, <?= htmlspecialchars($_SESSION['usuario']) ?></h1>
                <p>Gestiona el contenido de la web desde aquí.</p>
            </section>
        </header>

        <!-- aviso de guardado correcto -->
        <?php if (isset($_GET['ok'])): ?>
            <p class="perfil-aviso-ok">
                <i class="fa-solid fa-circle-check"></i> Perfil actualizado correctamente.
            </p>
        <?php endif; ?>

        <!-- tarjeta de perfil centrada -->
        <section class="perfil-card-wrapper">
            <article class="perfil-card">

                <!-- foto de perfil que sobresale por arriba -->
                <!-- si la usuaria tiene url de foto la mostramos, si no un icono de persona -->
                <?php if (!empty($perfil['url_foto'])): ?>
                    <img src="<?= htmlspecialchars($perfil['url_foto']) ?>"
                         alt="Foto de perfil"
                         class="perfil-card-foto">
                <?php else: ?>
                    <span class="perfil-card-foto-icono">
                        <i class="fa-solid fa-user"></i>
                    </span>
                <?php endif; ?>

                <!-- nombre y rol de la usuaria -->
                <p class="perfil-card-nombre">
                    <?= htmlspecialchars($perfil['nombre'] ?? $_SESSION['usuario']) ?>
                </p>
                <p class="perfil-card-rol">
                    <?= ($_SESSION['rol'] == 0) ? 'Administradora' : 'Editora' ?>
                </p>

                <!-- formulario para editar el perfil -->
                <form action="editora.php" method="POST">

                    <!-- campo nombre -->
                    <p class="perfil-campo">
                        <input type="text"
                               name="nombre"
                               value="<?= htmlspecialchars($perfil['nombre'] ?? $_SESSION['usuario']) ?>"
                               placeholder="Tu nombre"
                               required>
                        <span class="perfil-campo-icono">
                            <i class="fa-solid fa-pen"></i>
                        </span>
                    </p>

                    <!-- campo url foto de perfil -->
                    <p class="perfil-campo">
                        <input type="url"
                               name="url_foto"
                               value="<?= htmlspecialchars($perfil['url_foto'] ?? '') ?>"
                               placeholder="URL de tu foto de perfil">
                        <span class="perfil-campo-icono">
                            <i class="fa-solid fa-image"></i>
                        </span>
                    </p>

                    <!-- campo contraseña (opcional) -->
                    <p class="perfil-campo">
                        <input type="password"
                               name="pass"
                               placeholder="Nueva contraseña (opcional)">
                        <span class="perfil-campo-icono">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                    </p>

                    <!-- botón guardar -->
                    <button type="submit" name="guardar_perfil" class="perfil-card-btn">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar cambios
                    </button>

                </form>

            </article>
        </section>

        <!-- enlace al manual de uso abajo a la derecha -->
        <section class="manual-wrapper">
            <a href="../manual_admin.pdf" target="_blank" class="manual-btn">
                <i class="fa-solid fa-file-pdf"></i> Manual de uso
            </a>
        </section>

    </main>

</body>
</html>
