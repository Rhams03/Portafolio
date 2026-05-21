<?php
include 'admin_api.php';
include 'conexion.php';
// Filtramos para obtener solo alumnos
$alumnos = obtenerUsuarios(3); 
?>

<div class="modulo-usuarios">
    <div class="header-modulo">
        <h2><i class="fas fa-user-graduate"></i> Gestión de Alumnos</h2>
        <button class="btn-add" onclick="openModal('modal-alumno')"><i class="fas fa-plus"></i> Nuevo Alumno</button>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email/Carnet</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($alumnos as $u): ?>
            <tr>
                <td><?php echo $u['nombre']; ?></td>
                <td><?php echo $u['apellido']; ?></td>
                <td><?php echo $u['email']; ?></td>
                <td>
                    <a href="admin_procesar.php?accion=eliminar_usuario&id=<?php echo $u['id']; ?>&tipo=<?php echo $u['tipo']; ?>" 
                       class="btn-delete" onclick="return confirm('¿Eliminar alumno?')">
                       <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div id="modal-alumno" class="modal">
    <div class="modal-content">
        <h3>Agregar Nuevo Alumno</h3>
        <form action="admin_procesar.php?accion=crear_usuario" method="POST">
            <input type="hidden" name="tipo_usuario" value="alumno">
            <div class="input-box">
                <label form="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre"required>
            </div>
            <div class="input-box">
                <label for="apellido">Apellido</label>
                <input type="text" name="apellido" id="apellido" required>
            </div>
            <div class="input-box">
                <label for="carnet">Carnet / Usuario</label>
                <input type="text" name="carnet" id="carnet"required>
            </div>
            <div class="input-box">
                <label for="clase">Clase</label>
                <input type="text" name="clase" id="clase"required>
            </div>
            <div class="btn-group">
                <button type="button" onclick="closeModal('modal-alumno')">Cancelar</button>
                <button type="submit" class="btn-save">Guardar Alumno</button>
            </div>
        </form>
    </div>
</div>