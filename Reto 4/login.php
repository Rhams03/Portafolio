<?php
// la sesión siempre tiene que estar al principio, antes del html
session_start();

// cargamos las clases
require_once 'class/Conexion.php';
require_once 'class/Login.php';

// conectamos
$db   = new Database();
$conn = $db->connect();
$user = new Login($conn);

// variable para guardar si ha habido error de login
$error_login = false;

// si se ha enviado el formulario procesamos el login
if (isset($_POST['username']) && isset($_POST['password'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    // comprobamos en la base de datos
    $result = $user->login($username, $password);

    if ($result) {
        // guardamos datos en sesión
        $_SESSION['usuario']    = $result['nombre'];
        $_SESSION['rol']        = $result['rol_id'];
        $_SESSION['usuario_id'] = $result['id'];

        // redirigimos según el rol (0 = admin, 1 = editora)
        if ($result['rol_id'] == 0) {
            header('Location: admin/admin.php');
            exit;
        } else {
            header('Location: editora/editora.php');
            exit;
        }

    } else {
        // marcamos que hubo error para mostrarlo en el formulario
        $error_login = true;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión - MDM</title>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/movil.css" media="screen and (max-width: 768px)">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" href="img/mdmicono.ico" type="image/x-icon">
</head>
<body>

    <!-- video de fondo a pantalla completa -->
    <section class="video-background">
        <video autoplay loop muted playsinline>
            <source src="mdm.gif" type="img/gif">
        </video>
    </section>

    <!-- tarjeta de login centrada -->
    <section class="formulario">

        <!-- imagen decorativa a la izquierda -->
        <img src="img/medicosdm2.jpg" alt="Médicos del Mundo" class="imagen-lateral">

        <!-- formulario a la derecha -->
        <form action="login.php" method="POST">

            <!-- etiqueta grande con logo y nombre del sistema -->
            <section class="login-etiqueta">
                <img src="img/logomedicos.png" alt="Logo MDM" class="login-etiqueta-logo">
                <span>Médicas del Mundo</span>
            </section>

            <h1>Inicio de Sesión</h1>

            <!-- aviso de error si el login falla -->
            <?php if ($error_login): ?>
                <p class="login-error">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Nombre o contraseña incorrectos.
                </p>
            <?php endif; ?>

            <p>
                <label for="username">Nombre de usuaria</label>
                <input type="text" name="username" id="username" placeholder="Tu nombre" required>
            </p>
            <p>
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" placeholder="Tu contraseña" required>
            </p>
            <p>
                <button type="submit">
                    <i class="fa-solid fa-right-to-bracket"></i> Entrar
                </button>
            </p>

            <p><a href="index.php" class="volver-link">← Volver al inicio</a></p>

        </form>
    </section>

</body>
</html>
