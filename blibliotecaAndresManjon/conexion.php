<?php
$host = "localhost";
$user = "root";      // Usuario por defecto
$pass = "";          // Contraseña vacía por defecto
$db   = "sistemabiblioteca";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Configurar para que acepte tildes y Ñ
$conn->set_charset("utf8");
?>