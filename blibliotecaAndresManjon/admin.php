<?php
session_start();
include 'conexion.php';

// SEGURIDAD: Solo admin puede entrar
if (!isset($_SESSION['id_rol']) || $_SESSION['id_rol'] != 1) {
    header("Location: sesion.php");
    exit();
}

$modulo = $_GET['modulo'] ?? 'dashboard';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Biblioteca</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="admin-panel">

    <!-- HEADER -->
    <header class="admin-header">
        <div class="header-left">
            <img src="img/logo.jpg" alt="Logo">
            <h1>Panel Administrador</h1>
        </div>
        <div class="header-right">
            <span class="admin-name">Hola, <?php echo $_SESSION['nombre']; ?></span>
            <a href="logout.php" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Salir
            </a>
        </div>
    </header>

    <!-- SIDEBAR -->
    <aside class="admin-sidebar">
        <ul class="sidebar-menu">
            <li><a href="admin.php?modulo=dashboard" class="<?php echo $modulo === 'dashboard' ? 'active' : ''; ?>">
                <i class="fas fa-home"></i> Dashboard
            </a></li>
            <li><a href="admin.php?modulo=usuarios" class="<?php echo $modulo === 'usuarios' ? 'active' : ''; ?>">
                <i class="fas fa-users"></i> Usuarios
            </a></li>
            <li><a href="admin.php?modulo=catalogo" class="<?php echo $modulo === 'catalogo' ? 'active' : ''; ?>">
                <i class="fas fa-book"></i> Catálogo
            </a></li>
            <li><a href="admin.php?modulo=prestamos" class="<?php echo $modulo === 'prestamos' ? 'active' : ''; ?>">
                <i class="fas fa-exchange-alt"></i> Préstamos
            </a></li>
            <li><a href="admin.php?modulo=cuenta" class="<?php echo $modulo === 'cuenta' ? 'active' : ''; ?>">
                <i class="fas fa-user-cog"></i> Mi Cuenta
            </a></li>
        </ul>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="admin-main">
        <?php
        if ($modulo === 'dashboard') {
            include 'dashboard_admin.php';
        } elseif ($modulo === 'usuarios') {
            include 'modulo_usuarios.php';
        } elseif ($modulo === 'catalogo') {
            include 'modulo_catalogo.php';
        } elseif ($modulo === 'prestamos') {
            include 'modulo_prestamos.php';
        } elseif ($modulo === 'cuenta') {
            include 'modulo_cuenta.php';
        } else {
            include 'dashboard_admin.php';
        }
        ?>
    </main>

    <script>
        function openTab(evt, tabName) {
            const parent = evt.currentTarget.closest('.modal-content') || document.body;
            const tabcontents = parent.getElementsByClassName("tab-content");
            for (let i = 0; i < tabcontents.length; i++) {
                tabcontents[i].classList.remove("active");
            }
            
            const tablinks = parent.getElementsByClassName("tab-link");
            for (let i = 0; i < tablinks.length; i++) {
                tablinks[i].classList.remove("active");
            }
            
            document.getElementById(tabName).classList.add("active");
            evt.currentTarget.classList.add("active");
        }

        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) modal.classList.add('show');
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) modal.classList.remove('show');
        }

        // Cerrar modal al hacer clic fuera
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('show');
            }
        }
    </script>
<footer class="site-footer">
  <div class="footer-content">
    
    <div class="footer-left">
      <strong>RRAZ studios</strong> &copy; 2026 | <a href="https://www.aepd.es/politica-de-privacidad-y-aviso-legal">Privacy Policy</a>
    </div>

    <div class="footer-right">
      <a href="https://www.instagram.com/ceip_andresmanjon/" class="social-link">
        <img src="img/icono_instagram.png" alt="Instagram"></img>
      </a>
      <a href="https://www.micole.net/zaragoza/zaragoza/colegio-andres-manjon" class="social-link">
        <img src="img/icono_micole.png" alt="Micole"></img>
      </a>
      <a href="http://www.educateca.com/centros/ceip-andres-manjon-z.asp#f5" class="social-link">
        <img src="img/icono_educateca.jpg" alt="Educateca"></img>
      </a>
      <a href="https://www.zaragoza.es/sede/servicio/equipamiento/548" class="social-link">
        <img src="img/icono_ayuntamiento.png" alt="Zaragoza"></img>
      </a>
    </div>
    
  </div>
</footer>

<script src="https://kit.fontawesome.com/your-code.js" crossorigin="anonymous"></script>
</body>
</html>