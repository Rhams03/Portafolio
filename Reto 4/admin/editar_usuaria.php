<?php
// arrancamos la sesión
session_start();

// si no hay sesión activa mandamos al login
if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit;
}

// cargamos las clases
require_once __DIR__ . '/../class/Conexion.php';
require_once __DIR__ . '/../class/Usuaria.php';

// conectamos
$db       = new Database();
$conexion = $db->connect();

// recogemos el id de la url
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// si no hay id volvemos al listado
if (!$id) {
    header('Location: gestion_usuarias.php');
    exit;
}

// buscamos la usuaria en la base de datos
$datos = Usuaria::FindUsuaria($conexion, $id);

// si no existe volvemos
if (!$datos) {
    header('Location: gestion_usuarias.php');
    exit;
}

// si se ha enviado el formulario actualizamos
if (isset($_POST['guardar'])) {

    $obj = new Usuaria($conexion);

    // el rol lo dejamos vacío aquí porque no se edita en esta pantalla
    $obj->rellenarDatos($_POST['nombre'], $_POST['email'], $_POST['pass'], '');
    $obj->updateAll($id);

    header('Location: gestion_usuarias.php?ok=1');
    exit;
}

// nombre del archivo para el nav activo
$pagina = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuaria - MDM</title>
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
                <h1>Editar Usuaria</h1>
                <p>Modifica los datos de la cuenta seleccionada.</p>
            </section>
        </header>

        <!-- formulario centrado -->
        <section class="tarjeta-blanca admin-form-centrado">
            <header class="cabecera-tarjeta">
                <h2><i class="fa-solid fa-user-pen"></i> Modificar datos</h2>
            </header>

            <a href="gestion_usuarias.php" class="admin-link-volver">
                <i class="fa-solid fa-arrow-left"></i> Volver al listado
            </a>

            <form action="editar_usuaria.php?id=<?= $id ?>" method="POST" class="formulario-blog">

                <label>Nombre completo</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($datos['nombre']) ?>" required>

                <label>Correo electrónico</label>
                <input type="email" name="email" value="<?= htmlspecialchars($datos['correo'] ?? '') ?>" required>

                <label>Nueva contraseña</label>
                <!-- dejar vacío si no quiere cambiarla -->
                <input type="password" name="pass" placeholder="Solo rellena si quieres cambiarla">

                <button type="submit" name="guardar" class="boton-subir-noticia">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar Cambios
                </button>

            </form>
        </section>

    </main>

</body>
</html>
