<?php
include 'conexion.php';
include 'admin_api.php';

// Recibir datos del formulario de edición
$id = $_POST['id_original'];
$tipo_original = $_POST['tipo_original']; // 'alumno' o 'staff'
$nombre = $_POST['nombre'];
$nuevo_rol = $_POST['id_rol'];

// CASO 1: EL USUARIO SIGUE SIENDO DEL MISMO TIPO (Ej: Alumno sigue siendo Alumno)
// (Simplificamos: No permitimos cambiar de Alumno a Profesor fácilmente porque implica cambiar de tabla en la BD)

if ($tipo_original == 'alumno' && $nuevo_rol == 3) {
    // Actualizar datos de Alumno
    $clase = $_POST['clase'];
    $sql = "UPDATE alumnado SET nombre = ?, clase = ? WHERE id_alumno = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nombre, $clase, $id);
    $stmt->execute();

} elseif ($tipo_original == 'staff' && $nuevo_rol != 3) {
    // Actualizar datos de Staff (Profe o Admin)
    $email = $_POST['email'];
    $pass = $_POST['password'];

    if (!empty($pass)) {
        // Si escribió contraseña nueva
        $sql = "UPDATE usuarios SET nombre = ?, username = ?, contrasenia = ?, id_rol = ? WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssii", $nombre, $email, $pass, $nuevo_rol, $id);
    } else {
        // Si NO cambió la contraseña
        $sql = "UPDATE usuarios SET nombre = ?, username = ?, id_rol = ? WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssii", $nombre, $email, $nuevo_rol, $id);
    }
    $stmt->execute();
}

// Redirigir de vuelta al panel
header("Location: admin.php#modal-usuarios");
exit();
?>