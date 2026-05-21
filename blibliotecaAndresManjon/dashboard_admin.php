<?php
/**
 * DASHBOARD - Página principal del panel de administración
 * Muestra estadísticas rápidas y accesos a módulos
 */

include 'admin_api.php';

// Obtener estadísticas
$stats_usuarios = $conn->query("SELECT COUNT(*) as total FROM usuarios")->fetch_assoc();
$stats_alumnos = $conn->query("SELECT COUNT(*) as total FROM alumnado")->fetch_assoc();
$stats_libros = $conn->query("SELECT COUNT(*) as total FROM catalogo")->fetch_assoc();
$stats_prestamos = obtenerEstadisticasPrestamos();

?>

<div class="dashboard">
    <h1 style="margin-bottom: 30px; color: #2c3e50;">
        <i class="fas fa-chart-line"></i> Dashboard - Resumen General
    </h1>

    <!-- TARJETAS ESTADÍSTICAS -->
    <div class="dashboard-grid">
        <div class="dashboard-card" style="border-left-color: #3498db;">
            <i class="fas fa-users" style="color: #3498db;"></i>
            <h3><?php echo $stats_usuarios['total'] + $stats_alumnos['total']; ?></h3>
            <p>Usuarios Totales</p>
            <small><?php echo $stats_usuarios['total']; ?> Staff · <?php echo $stats_alumnos['total']; ?> Alumnos</small>
        </div>

        <div class="dashboard-card" style="border-left-color: #27ae60;">
            <i class="fas fa-book" style="color: #27ae60;"></i>
            <h3><?php echo $stats_libros['total']; ?></h3>
            <p>Libros en Catálogo</p>
        </div>

        <div class="dashboard-card" style="border-left-color: #f39c12;">
            <i class="fas fa-exchange-alt" style="color: #f39c12;"></i>
            <h3><?php echo $stats_prestamos['total']; ?></h3>
            <p>Préstamos Registrados</p>
        </div>

        <div class="dashboard-card" style="border-left-color: #e74c3c;">
            <i class="fas fa-clock" style="color: #e74c3c;"></i>
            <h3><?php echo $stats_prestamos['pendientes']; ?></h3>
            <p>Pendientes de Aprobación</p>
        </div>
    </div>

    <!-- ACCESO RÁPIDO A MÓDULOS -->
    <h2 style="margin-top: 40px; margin-bottom: 20px; color: #2c3e50;">Acceso Rápido a Módulos</h2>
    
    <div class="dashboard-grid" style="margin-bottom: 40px;">
        <div class="dashboard-card" onclick="location.href='admin.php?modulo=usuarios'" style="cursor: pointer; border-left-color: #667eea;">
            <i class="fas fa-user-tie" style="color: #667eea;"></i>
            <h3>Gestionar Usuarios</h3>
            <p>Crear, editar y eliminar usuarios. Cambiar roles y permisos.</p>
            <small style="color: #3498db;">→ Ir al módulo</small>
        </div>

        <div class="dashboard-card" onclick="location.href='admin.php?modulo=catalogo'" style="cursor: pointer; border-left-color: #16a085;">
            <i class="fas fa-book-open" style="color: #16a085;"></i>
            <h3>Administrar Catálogo</h3>
            <p>Agregar, editar y eliminar libros. Gestionar portadas e información.</p>
            <small style="color: #3498db;">→ Ir al módulo</small>
        </div>

        <div class="dashboard-card" onclick="location.href='admin.php?modulo=prestamos'" style="cursor: pointer; border-left-color: #c0392b;">
            <i class="fas fa-redo" style="color: #c0392b;"></i>
            <h3>Gestionar Préstamos</h3>
            <p>Ver, editar y aprobar préstamos. Controlar devoluciones y retrasos.</p>
            <small style="color: #3498db;">→ Ir al módulo</small>
        </div>

        <div class="dashboard-card" onclick="location.href='admin.php?modulo=cuenta'" style="cursor: pointer; border-left-color: #8e44ad;">
            <i class="fas fa-lock" style="color: #8e44ad;"></i>
            <h3>Mi Cuenta</h3>
            <p>Cambiar contraseña, email y ver historial de tu perfil.</p>
            <small style="color: #3498db;">→ Ir al módulo</small>
        </div>
    </div>

    <!-- INFORMACIÓN ÚTIL -->
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <h3 style="color: #2c3e50; margin-bottom: 15px;">
            <i class="fas fa-info-circle" style="color: #3498db;"></i> Información del Sistema
        </h3>
        <p style="margin: 10px 0; color: #555;">
            <strong>Base de Datos:</strong> sistemabiblioteca (MySQLi)
        </p>
        <p style="margin: 10px 0; color: #555;">
            <strong>Tu perfil:</strong> <?php echo $_SESSION['nombre']; ?> (Administrador)
        </p>
        <p style="margin: 10px 0; color: #555;">
            <strong>Última actualización del sistema:</strong> Febrero 2026
        </p>
    </div>
</div>
