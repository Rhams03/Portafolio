<?php
include 'conexion.php';

$nombre = $_POST['nombre'];
$rol    = $_POST['id_rol'];

if ($rol == "3") {
    // --- ES ALUMNO ---
    $clase = $_POST['clase']; // Recogemos el curso
    $carnet = $_POST['carnet'];
    
    // Asegúrate de tener la columna 'curso' en la BD
    $sql = "INSERT INTO alumnado (nombre, carnet, clase) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nombre, $carnet, $clase);

} else {
    // --- ES STAFF ---
    $email = $_POST['email'];
    $pass = $_POST['password']; 
    
    $sql = "INSERT INTO usuarios (nombre, username, contrasenia, id_rol) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nombre, $email, $pass, $rol);
}

if ($stmt->execute()) {
    // Volver al panel de admin automáticamente
    echo "<script>window.location.href='admin.php#modal-usuarios';</script>";
} else {
    echo "Error: " . $conn->error;
}
?>