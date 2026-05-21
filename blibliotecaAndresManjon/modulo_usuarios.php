<?php
/**
 * MÓDULO USUARIOS - Gestión de usuarios del sistema
 * Permite crear, editar y eliminar usuarios (Alumnos, Profesores, Admins)
 */

include 'admin_api.php';

// Procesar eliminación (PHP)
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $tipo = $_GET['tipo'] ?? 'usuarios';
    
    if ($tipo === 'alumnado') {
        $conn->query("DELETE FROM alumnado WHERE id_alumno = $id");
    } else {
        $conn->query("DELETE FROM usuarios WHERE id_usuario = $id");
    }
    
    header("Location: admin.php?modulo=usuarios");
    exit();
}

// Procesar nuevo usuario (PHP)
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_usuario'])) {
    $tipo = $_POST['tipo_usuario'];
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido'] ?? '');
    
    if ($tipo === 'alumno') {
        $carnet = 'AL' . time();
        $sql = "INSERT INTO alumnado (nombre, apellido, carnet, fecha_creacion, activo) 
                VALUES ('$nombre', '$apellido', NOW(), 1)";
        $success = $conn->query($sql);
    } else {
        $email = $_POST['email'];
        $contrasenia =$_POST['contrasenia'];
        $rol = intval($_POST['id_rol']);
        $sql = "INSERT INTO usuarios (nombre, apellido, email, username, contrasenia, id_rol, fecha_creacion) 
                VALUES ('$nombre', '$apellido', '$email', '$email', '$contrasenia', $rol, NOW())";
        $success = $conn->query($sql);
    }
    
    if ($success) {
        $mensaje = '<div class="alert-success">✓ Usuario creado exitosamente</div>';
    } else {
        $mensaje = '<div class="alert-error">✗ Error: ' . $conn->error . '</div>';
    }
}

// Obtener usuarios (PHP)
$alumnos = $conn->query("SELECT id_alumno as id, nombre, apellido, 'alumno' as tipo FROM alumnado ORDER BY nombre");
$profesores = $conn->query("SELECT id_usuario as id, nombre, apellido, email, contrasenia, id_rol, 'staff' as tipo FROM usuarios ORDER BY nombre");
?>

<div class="modulo-usuarios">
    <h2><i class="fas fa-users"></i> Gestión de Usuarios</h2>
    
    <?php echo $mensaje; ?>

    <div class="tabs-container">
        <div class="tab-buttons">
            <button class="tab-btn active" onclick="mostrarTab('tab-listado')">Listado</button>
            <button class="tab-btn" onclick="mostrarTab('tab-nuevo')">Añadir Nuevo</button>
        </div>

        <!-- TAB: LISTADO -->
        <div id="tab-listado" class="tab-panel active">
            <input type="text" id="buscador" placeholder="🔍 Buscar usuario..." class="buscador">
            
            <table class="admin-table" id="tablaUsuarios">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Datos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Alumnos (PHP)
                    while ($alumno = $alumnos->fetch_assoc()) {
                        echo "<tr>
                            <td>{$alumno['nombre']} {$alumno['apellido']}</td>
                            <td><span class='badge badge-blue'>Alumno</span></td>
                            <td>ID: {$alumno['id']}</td>
                            <td>
                                <a href='admin.php?modulo=usuarios&eliminar={$alumno['id']}&tipo=alumnado' class='btn-icon delete' onclick='return confirm(&quot;¿Confirmar eliminación?&quot;)'>
                                    <i class='fas fa-trash'></i>
                                </a>
                            </td>
                        </tr>";
                    }
                    
    // Profesores y Admins (PHP)
                    while ($usuario = $profesores->fetch_assoc()) {
                        $rol_texto = $usuario['id_rol'] == 1 ? 'Admin' : 'Profesor';
                        $rol_clase = $usuario['id_rol'] == 1 ? 'badge-red' : 'badge-green';
                        echo "<tr>
                            <td>{$usuario['nombre']} {$usuario['apellido']}</td>
                            <td><span class='badge $rol_clase'>$rol_texto</span></td>
                            <td>Email: {$usuario['email']}</td>
                            <td>
                                <a href='admin.php?modulo=usuarios&eliminar={$usuario['id']}&tipo=usuarios' class='btn-icon delete' onclick='return confirm(\"¿Confirmar eliminación?\")'>
                                    <i class='fas fa-trash'></i>
                                </a>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- TAB: NUEVO USUARIO -->
        <div id="tab-nuevo" class="tab-panel">
            <form method="POST" class="form-usuarios">
                <input type="hidden" name="guardar_usuario" value="1">
                
                <label>Tipo de Usuario:</label>
                <select name="tipo_usuario" id="tipo-usuario" onchange="cambiarFormulario(this.value)">
                    <option value="alumno">Alumno</option>
                    <option value="profesor">Profesor</option>
                    <option value="admin">Administrador</option>
                </select>

                <label>Nombre:</label>
                <input type="text" name="nombre" required>

                <label>Apellido:</label>
                <input type="text" name="apellido">

                <label for="clase">Clase:</label>
                <input type="text" id="clase" name="clase">

                <!-- Campos para Alumno -->
                <div id="campos-alumno" class="campos-dinamicos" style="display: none;">
                    <p><em>Alumno registrado como: {nombre} {apellido}</em></p>
                </div>

                <!-- Campos para Staff -->
                <div id="campos-staff" class="campos-dinamicos" style="display: none;">
                    <label>Email:</label>
                    <input type="email" name="email" placeholder="usuario@colegio.es">

                    <label>Contraseña:</label>
                    <input type="contrasenia" name="contrasenia" placeholder="Mínimo 6 caracteres">

                    <label>Rol:</label>
                    <select name="id_rol">
                        <option value="2">Profesor</option>
                        <option value="1">Administrador</option>
                    </select>
                </div>

                <button type="submit" class="btn-save">Guardar Usuario</button>
            </form>
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

function cambiarFormulario(tipo) {
    const camposAlumno = document.getElementById('campos-alumno');
    const camposStaff = document.getElementById('campos-staff');
    
    if (tipo === 'alumno') {
        camposAlumno.style.display = 'block';
        camposStaff.style.display = 'none';
    } else {
        camposAlumno.style.display = 'none';
        camposStaff.style.display = 'block';
    }
}

// Buscador
document.getElementById('buscador').addEventListener('keyup', function() {
    const texto = this.value.toLowerCase();
    const filas = document.getElementById('tablaUsuarios').getElementsByTagName('tr');
    
    for (let i = 1; i < filas.length; i++) {
        const nombre = filas[i].cells[0].textContent.toLowerCase();
        const mostrar = nombre.includes(texto);
        filas[i].style.display = mostrar ? '' : 'none';
    }
});
</script>
