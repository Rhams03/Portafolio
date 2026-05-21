<?php
/**
 * MÓDULO MI CUENTA - Gestión de datos personales del Admin
 * Maneja: Cambiar contraseña, email, ver historial de roles
 */

include 'admin_api.php';

$id_usuario = $_SESSION['id_usuario'] ?? $_SESSION['usuario_id'] ?? null;
$rol_actual = $_SESSION['id_rol'] ?? $_SESSION['rol'] ?? 0;

$info_usuario = null;
if ($id_usuario) {
    $info_usuario = obtenerInfoAdmin($id_usuario);
}
$historial = 0;
if ($id_usuario) {
    $historial = obtenerInfoAdmin($id_usuario);
}

$etiqueta_rol = ($rol_actual == 1) ? "Administrador" : "Profesor";
 
?>


<div class="modulo-cuenta">
    <h2><i class="fas fa-user-cog"></i> Mi Cuenta</h2>

    <div class="modal-tabs">
        <button class="tab-link active" onclick="openTab(event, 'tab-info-personal')">👤 Información Personal</button>
        <button class="tab-link" onclick="openTab(event, 'tab-cambiar-contrasena')">🔑 Cambiar Contraseña</button>
        <button class="tab-link" onclick="openTab(event, 'tab-historial')">📋 Historial</button>
    </div>

    <!-- PESTAÑA: INFORMACIÓN PERSONAL -->
    <div id="tab-info-personal" class="tab-content active">
        <div class="info-box">
            <h3>Datos de Perfil</h3>
            
            <div class="info-item">
                <label>Nombre Completo:</label>
                <p><?php echo $info_usuario['nombre'] ?? '' . ' ' . ($info_usuario['apellido'] ?? ''); ?></p>
            </div>

            <div class="info-item">
                <label>Correo Electrónico:</label>
                <p><?php echo $info_usuario['username'] ?? ''; ?></p>
            </div>

            <div class="info-item">
                <label>Rol:</label>
                <p><?php echo $etiqueta_rol 
                    ?>
                </p>
            </div>

            <div class="info-item">
                <label>Máximo de Libros a Prestar:</label>
                <p><?php echo $info_usuario['max_libros_prestables'] ?? 5; ?> libros</p>
            </div>

            <div class="info-item">
                <label>Fecha de Creación del Perfil:</label>
                <p><?php echo date('d de F de Y', strtotime($info_usuario['fecha_creacion'])); ?></p>
            </div>

            <button class="btn-editar-info" onclick="openTab(event, 'tab-cambiar-contrasena')" style="margin-top: 20px; background: #3498db; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                🔐 Actualizar Datos
            </button>
        </div>
    </div>

    <!-- PESTAÑA: CAMBIAR CONTRASEÑA -->
    <div id="tab-cambiar-contrasena" class="tab-content">
        <form id="form-cambiar-contrasena" class="form-modal">
            <h3>Cambiar Contraseña</h3>

            <label>Correo Actual:</label>
            <input type="email" value="<?php echo $info_usuario['username'] ?? ''; ?>" disabled style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #ccc; background: #f0f0f0;">

            <label>Nuevo Correo (Opcional):</label>
            <input type="email" name="nuevo_email" placeholder="Dejar vacío si no deseas cambiar" style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #ccc;">

            <label>Contraseña Actual:</label>
            <input type="password" name="contrasena_actual" required placeholder="Ingresa tu contraseña actual" style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #ccc;">

            <label>Nueva Contraseña:</label>
            <input type="password" name="nueva_contrasena" placeholder="Ingresa la nueva contraseña" style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #ccc;">

            <label>Confirmar Nueva Contraseña:</label>
            <input type="password" name="confirmar_contrasena" placeholder="Confirma la nueva contraseña" style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #ccc;">

            <div style="background: #fffacd; padding: 15px; border-radius: 5px; margin-bottom: 15px; border-left: 4px solid #f39c12;">
                <p style="margin: 0; color: #856404;"><strong>⚠️ Nota:</strong> Después de cambiar tu contraseña, deberás iniciar sesión nuevamente.</p>
            </div>

            <button type="submit" style="width: 100%; background: #27ae60; color: white; padding: 12px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">Guardar Cambios</button>
        </form>
    </div>

    <!-- PESTAÑA: HISTORIAL -->
    <div id="tab-historial" class="tab-content">
        <div class="info-box">
            <h3>Historial de la Cuenta</h3>

            <div class="historial-item">
                <label>Rol Actual:</label>
                <p> <?php echo $etiqueta_rol ?>(desde <?php echo date('d/m/Y', strtotime($info_usuario['fecha_creacion'])); ?>)</p>
            </div>

            <div class="historial-item">
                <label>Cambios Recientes:</label>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 10px;">
                    <p style="color: #666; margin: 0;">
                        <strong>Perfil creado:</strong> <?php echo date('d \d\e F \d\e Y \a \l\a\s H:i', strtotime($info_usuario['fecha_creacion'])); ?>
                    </p>
                    <?php if($info_usuario['historial_roles']): ?>
                        <p style="color: #666; margin-top: 10px;">
                            <strong>Historial de roles:</strong> <?php echo htmlspecialchars($info_usuario['historial_roles']); ?>
                        </p>
                    <?php else: ?>
                        <p style="color: #999; margin-top: 10px; font-style: italic;">No hay cambios de rol registrados. Has sido administrador desde la creación.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="historial-item" style="margin-top: 20px;">
                <label>Seguridad:</label>
                <div style="background: #e8f5e9; padding: 15px; border-radius: 5px; margin-top: 10px;">
                    <p style="margin: 0; color: #2e7d32;">
                        <i class="fas fa-shield-alt"></i> Tu cuenta está protegida con contraseña.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
        tabcontent[i].classList.remove("active");
    }
    tablinks = document.getElementsByClassName("tab-link");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    document.getElementById(tabName).classList.add("active");
    evt.currentTarget.className += " active";
}

// Envío del formulario de contraseña con AJAX
document.getElementById('form-cambiar-contrasena').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const nueva = document.querySelector('input[name="nueva_contrasenia"]').value;
    const confirmar = document.querySelector('input[name="confirmar_contrasenia"]').value;
    
    if (nueva !== confirmar) {
        alert('❌ Las contraseñas no coinciden');
        return;
    }
    
    const formData = new FormData(this);
    
    fetch('admin_procesar.php?accion=cambiar_contrasenia', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) // Esperamos JSON del servidor
    .then(data => {
        if (data.success) {
            alert('✅ ' + data.mensaje);
            this.reset();
        } else {
            alert('❌ Error: ' + data.mensaje);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Hubo un error al procesar la solicitud.');
    });
});
</script>
