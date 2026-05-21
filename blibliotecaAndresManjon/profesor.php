<?php
session_start();
include 'conexion.php';
include 'admin_api.php';

// SEGURIDAD: Solo profesores pueden entrar (Rol 2)
if (!isset($_SESSION['id_rol']) || $_SESSION['id_rol'] != 2) {
    header("Location: sesion.php");
    exit();
}

$modulo = $_GET['modulo'] ?? 'prestamos'; // Por defecto va a préstamos
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Profesor - Biblioteca</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="admin-panel"> 
    <header class="admin-header">
        <div class="header-left">
            <img src="img/logo.jpg" alt="Logo" style="width: 40px; border-radius: 50%;">
            <h1>Panel Profesor</h1>
        </div>
        <div class="header-right">
            <span class="admin-name">Bienvenido, <?php echo $_SESSION['nombre']; ?></span>
            <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Salir</a>
        </div>
    </header>

    <nav class="admin-sidebar">
        <ul>
            <li><a href="profesor.php?modulo=prestamos" class="<?php echo $modulo==='prestamos'?'active':''; ?>"><i class="fas fa-hand-holding-book"></i> Gestión Préstamos</a></li>
            <li><a href="profesor.php?modulo=alumnos" class="<?php echo $modulo==='alumnos'?'active':''; ?>"><i class="fas fa-user-graduate"></i> Mis Alumnos</a></li>
            <li><a href="index.php"><i class="fas fa-book-open"></i> Ver Catálogo</a></li>
            <li><a href="profesor.php?modulo=cuenta" class="<?php echo $modulo==='cuenta'?'active':''; ?>"><i class="fas fa-user-cog"></i> Mis Datos</a></li>
        </ul>
    </nav>

    <main class="admin-main">
        <?php 
        // Carga dinámica de módulos
        if ($modulo === 'prestamos') {
            include 'modulo_prestamos.php'; // Reutilizamos el del admin
        } elseif ($modulo === 'alumnos') {
            include 'modulo_alumno_profesor.php'; 
        } elseif ($modulo === 'cuenta') {
            include 'modulo_cuenta.php'; // Reutilizamos el del admin
        } else {
            include 'modulo_prestamos.php';
        }
        ?>
    </main>
    <script>
        function openModal(id) {
            document.getElementById(id).classList.add('show');
        }
        function closeModal(id) {
            document.getElementById(id).classList.remove('show');
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