<?php
/**
 * MÓDULO PRESTAMOS - Gestión de solicitudes de préstamo
 * Permite ver, aprobar y rechazar solicitudes de préstamo
 */

include 'admin_api.php';

// Procesar aprobación de préstamo
if (isset($_GET['aprobar'])) {
    $id_prestamo = intval($_GET['aprobar']);
    
    // Obtener datos del préstamo
    $sql_prestamo = "SELECT id_alumno, id_libro FROM prestamos WHERE id_prestamo = ?";
    $stmt = $conn->prepare($sql_prestamo);
    $stmt->bind_param("i", $id_prestamo);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $prestamo = $result->fetch_assoc();
        $id_libro = $prestamo['id_libro'];
        
        // Actualizar estado del préstamo a 'aprobado'
        $sql_update = "UPDATE prestamos SET estado = 'aprobado', estado_prestamo = 'aprobado', fecha_inicio = NOW() WHERE id_prestamo = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("i", $id_prestamo);
        $stmt_update->execute();
        $stmt_update->close();
        
        // Obtener información del libro
        $sql_libro = "SELECT cantidad_ejemplares FROM catalogo WHERE id = ?";
        $stmt_libro = $conn->prepare($sql_libro);
        $stmt_libro->bind_param("i", $id_libro);
        $stmt_libro->execute();
        $result_libro = $stmt_libro->get_result();
        
        if ($result_libro->num_rows > 0) {
            $libro = $result_libro->fetch_assoc();
            $nuevos_ejemplares = $libro['cantidad_ejemplares'] - 1;
            
            // Actualizar cantidad de ejemplares
            $sql_update_libro = "UPDATE catalogo SET cantidad_ejemplares = ? WHERE id = ?";
            $stmt_update_libro = $conn->prepare($sql_update_libro);
            $stmt_update_libro->bind_param("ii", $nuevos_ejemplares, $id_libro);
            $stmt_update_libro->execute();
            $stmt_update_libro->close();
        }
        
        header("Location: admin.php?modulo=prestamos");
        exit();
    }
    $stmt->close();
}

// Procesar rechazo de préstamo
if (isset($_GET['rechazar'])) {
    $id_prestamo = intval($_GET['rechazar']);
    
    $sql_delete = "DELETE FROM prestamos WHERE id_prestamo = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $id_prestamo);
    $stmt->execute();
    $stmt->close();
    
    header("Location: admin.php?modulo=prestamos");
    exit();
}

// Obtener solicitudes pendientes
$sql = "SELECT p.id_prestamo, a.nombre as alumno_nombre, a.apellido as alumno_apellido, 
        c.titulo as libro_titulo, c.autor, p.fecha, c.cantidad_ejemplares
        FROM prestamos p
        INNER JOIN alumnado a ON p.id_alumno = a.id_alumno
        INNER JOIN catalogo c ON p.id_libro = c.id
        WHERE p.estado = 'pendiente'
        ORDER BY p.fecha DESC";

$result = $conn->query($sql);
?>

<div class="modulo-prestamos">
    <h2>📚 Gestión de Solicitudes de Préstamo</h2>

    <div class="tabs-container">
        <div class="tab-buttons">
            <button class="tab-btn active" onclick="mostrarTab('tab-pendientes')">Pendientes</button>
            <button class="tab-btn" onclick="mostrarTab('tab-aprobados')">Aprobados</button>
        </div>

        <!-- TAB: SOLICITUDES PENDIENTES -->
        <div id="tab-pendientes" class="tab-panel active">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Libro</th>
                        <th>Autor</th>
                        <th>Ejemplares Disponibles</th>
                        <th>Fecha Solicitud</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $fecha = date('d/m/Y H:i', strtotime($row['fecha']));
                            echo "<tr>
                                <td>{$row['alumno_nombre']} {$row['alumno_apellido']}</td>
                                <td>{$row['libro_titulo']}</td>
                                <td>{$row['autor']}</td>
                                <td><span class='badge badge-blue'>{$row['cantidad_ejemplares']}</span></td>
                                <td>$fecha</td>
                                <td>
                                    <a href='admin.php?modulo=prestamos&aprobar={$row['id_prestamo']}' class='btn-icon' title='Aprobar' onclick='return confirm(\"¿Aprobar este préstamo?\")'>
                                        ✓
                                    </a>
                                    <a href='admin.php?modulo=prestamos&rechazar={$row['id_prestamo']}' class='btn-icon' title='Rechazar' onclick='return confirm(\"¿Rechazar esta solicitud?\")'>
                                        ✗
                                    </a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' style='text-align:center; padding: 20px;'>No hay solicitudes pendientes</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- TAB: PRÉSTAMOS APROBADOS -->
        <div id="tab-aprobados" class="tab-panel">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Libro</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Devolución Esperada</th>
                        <th>Devolución Real</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql_aprobados = "SELECT p.id_prestamo, a.nombre, a.apellido, c.titulo, 
                                     p.fecha_inicio, p.fecha_entrega_esperada, p.fecha_entrega_real, p.estado_prestamo
                                     FROM prestamos p
                                     INNER JOIN alumnado a ON p.id_alumno = a.id_alumno
                                     INNER JOIN catalogo c ON p.id_libro = c.id
                                     WHERE p.estado = 'aprobado'
                                     ORDER BY p.fecha_inicio DESC";
                    
                    $result_aprobados = $conn->query($sql_aprobados);
                    
                    if ($result_aprobados && $result_aprobados->num_rows > 0) {
                        while ($row = $result_aprobados->fetch_assoc()) {
                            $fecha_inicio = $row['fecha_inicio'] ? date('d/m/Y', strtotime($row['fecha_inicio'])) : '--';
                            $fecha_esperada = $row['fecha_entrega_esperada'] ? date('d/m/Y', strtotime($row['fecha_entrega_esperada'])) : '--';
                            $fecha_real = $row['fecha_entrega_real'] ? date('d/m/Y', strtotime($row['fecha_entrega_real'])) : '--';
                            
                            $estado_badge = '';
                            if ($row['estado_prestamo'] === 'devuelto') {
                                $estado_badge = '<span class="badge" style="background: #27ae60; color: white;">Devuelto</span>';
                            } elseif ($row['estado_prestamo'] === 'retrasa') {
                                $estado_badge = '<span class="badge" style="background: #e74c3c; color: white;">Retrasa</span>';
                            } else {
                                $estado_badge = '<span class="badge badge-blue">Activo</span>';
                            }
                            
                            echo "<tr>
                                <td>{$row['nombre']} {$row['apellido']}</td>
                                <td>{$row['titulo']}</td>
                                <td>$fecha_inicio</td>
                                <td>$fecha_esperada</td>
                                <td>$fecha_real</td>
                                <td>$estado_badge</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' style='text-align:center; padding: 20px;'>No hay préstamos aprobados</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function mostrarTab(tabId) {
    document.querySelectorAll('.tab-panel').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');
    event.target.classList.add('active');
}
</script>


