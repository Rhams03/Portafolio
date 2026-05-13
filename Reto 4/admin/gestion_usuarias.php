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
$db      = new Database();
$conn    = $db->connect();
$usuaria = new Usuaria($conn);

// --- si se envía el formulario de crear usuaria ---
if (!empty($_POST['nombre']) && !empty($_POST['email']) && !empty($_POST['pass'])) {

    $nombre   = $_POST['nombre'];
    $email    = $_POST['email'];
    $password = $_POST['pass'];

    // convertimos el valor del select al número de rol correcto
    // el select envía "admin" o "editora" como texto
    if ($_POST['rol'] == 'admin') {
        $rol = 0;  // 0 = administradora
    } else {
        $rol = 1;  // 1 = editora
    }

    // rellenamos el objeto con los datos del formulario
    $usuaria->rellenarDatos($nombre, $email, $password, $rol);

    // intentamos insertar en la base de datos
    if ($usuaria->inserta()) {
        header('Location: gestion_usuarias.php?ok=1');
        exit;
    } else {
        $error = "No se pudo crear la usuaria. Comprueba los datos.";
    }
}

// --- si se pide borrar una usuaria ---
if (isset($_GET['borrar'])) {
    $usuaria->DeleteUsuaria((int)$_GET['borrar']);
    header('Location: gestion_usuarias.php');
    exit;
}

// obtenemos la lista completa de usuarias
$lista = Usuaria::GetAllUsuarias($conn);

// nombre del archivo para el nav
$pagina = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarias - MDM</title>
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
                <h1>Gestión de Usuarias</h1>
                <p>Crea y gestiona las cuentas de acceso al panel.</p>
            </section>
        </header>

        <!-- formulario para crear una nueva usuaria -->
        <section class="tarjeta-blanca admin-form-centrado">
            <header class="cabecera-tarjeta">
                <h2><i class="fa-solid fa-user-plus"></i> Nueva Usuaria</h2>
            </header>

            <!-- aviso de éxito -->
            <?php if (isset($_GET['ok'])): ?>
                <p class="perfil-aviso-ok">
                    <i class="fa-solid fa-circle-check"></i> Usuaria creada correctamente.
                </p>
            <?php endif; ?>

            <!-- aviso de error -->
            <?php if (isset($error)): ?>
                <p class="aviso-error">
                    <i class="fa-solid fa-circle-xmark"></i> <?= htmlspecialchars($error) ?>
                </p>
            <?php endif; ?>

            <form action="gestion_usuarias.php" method="POST" class="formulario-blog">

                <label for="nombre">Nombre completo</label>
                <input type="text" id="nombre" name="nombre" placeholder="Nombre" required>

                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" placeholder="correo@ejemplo.com" required>

                <label for="pass">Contraseña</label>
                <input type="password" id="pass" name="pass" placeholder="Contraseña" required>

                <label for="rol">Rol de acceso</label>
                <!-- el valor debe ser exactamente "admin" o "editora" para que el PHP de arriba lo lea bien -->
                <select id="rol" name="rol" class="selector-admin">
                    <option value="editora">Editora de Contenidos</option>
                    <option value="admin">Administradora Total</option>
                </select>

                <button type="submit" class="boton-subir-noticia">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar Usuaria
                </button>

            </form>
        </section>

        <!-- tabla con la lista de usuarias existentes -->
        <section class="tarjeta-blanca">
            <header class="cabecera-tarjeta">
                <h2><i class="fa-solid fa-users"></i> Usuarias registradas</h2>
            </header>

            <table class="tabla-admin">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($lista)): ?>
                        <?php foreach ($lista as $u): ?>
                            <tr>
                                <td class="titulo-celda"><?= htmlspecialchars($u['nombre']) ?></td>
                                <td><?= htmlspecialchars($u['correo'] ?? '') ?></td>
                                <td>
                                    <!-- el tipo es un string "admin" o "editora" — lo mostramos bonito -->
                                    <span class="etiqueta-rol">
                                        <?= ($u['tipo'] == 'admin') ? 'Administradora' : 'Editora' ?>
                                    </span>
                                </td>
                                <td class="celda-acciones">
                                    <a href="editar_usuaria.php?id=<?= $u['id'] ?>" class="accion-editar" title="Editar">
                                        <i class="fa-solid fa-user-pen"></i>
                                    </a>
                                    <a href="gestion_usuarias.php?borrar=<?= $u['id'] ?>"
                                       class="accion-borrar"
                                       title="Borrar"
                                       onclick="return confirm('¿Segura que quieres borrar esta usuaria?')">
                                        <i class="fa-solid fa-user-xmark"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align:center; padding: 30px; color: #aaa;">
                                No hay usuarias registradas todavía.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </section>

    </main>

</body>
</html>
