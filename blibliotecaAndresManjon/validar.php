<?php
session_start();
include 'conexion.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rol = $_POST['rol']; 

    // --- LOGICA DE ALUMNO ---
    if ($rol === 'alumno') {
        $nombre = $_POST['nombre'];
        $carnet = $_POST['carnet'];

        $sql = "SELECT * FROM alumnado WHERE nombre = ? AND carnet = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $nombre, $carnet);
    
    // --- LOGICA DE PROFESOR Y ADMIN ---
    } else {
        $user_input = $_POST['email']; 
        $pass  = $_POST['password'];
        
        // Si el formulario dice admin, buscamos rol 1. Si dice profesor, rol 2.
        $id_rol_busqueda = ($rol === 'admin') ? 1 : 2;

        // Verifica que tu columna de contraseña se llame 'contrasenia' o 'password' en la BD
        $sql = "SELECT * FROM usuarios WHERE username = ? AND contrasenia = ? AND id_rol = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $user_input, $pass, $id_rol_busqueda);
    }

    $stmt->execute();
    $resultado = $stmt->get_result();

    // --- SI LOS DATOS SON CORRECTOS ---
    if ($resultado->num_rows > 0) {
        $datos = $resultado->fetch_assoc();
        
        // Guardamos datos básicos
        $_SESSION['usuario_id'] = ($rol === 'alumno') ? $datos['id_alumno'] : $datos['id_usuario'];
        $_SESSION['nombre'] = $datos['nombre'];
        
        // Obtener el parámetro de retorno (si existe)
        $return_to = isset($_GET['return_to']) ? $_GET['return_to'] : null;
        $libro = isset($_GET['libro']) ? $_GET['libro'] : null;
        
        // --- AQUÍ ESTÁ LA SOLUCIÓN DEL PROBLEMA ---
        if ($rol === 'admin') {
            $_SESSION['id_rol'] = 1; // IMPORTANTE: Guardamos que es rol 1
            header("Location: admin.php"); // <--- AL ADMIN LO MANDAMOS AL PANEL
            exit;

        } elseif ($rol === 'profesor') {
            $_SESSION['id_rol'] = 2;
            header("Location: profesor.php"); // Al profe al catálogo
            exit;

        } else {
            $_SESSION['id_rol'] = 3;
            
            // Si viene parámetro return_to con solicitud.php, redirigir allí
            if ($return_to === 'solicitud.php') {
                $redirect = 'solicitud.php';
                if (!empty($libro)) {
                    $redirect .= '?libro=' . urlencode($libro);
                }
                header("Location: " . $redirect);
            } else {
                header("Location: index.php"); // Al alumno al catálogo
            }
            exit;
        }

    } else {
        echo "<script>alert('Datos incorrectos.'); window.location.href='sesion.php';</script>";
    }
}
?>