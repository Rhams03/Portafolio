<?php
/**
 * PROCESADOR DE ACCIONES DEL ADMIN
 * Maneja todas las solicitudes POST/GET del panel de administración
 */

session_start();
include 'admin_api.php';
include 'conexion.php';


$accion = $_GET['accion'] ?? null;

// ============================================================
// USUARIOS
// ============================================================

if ($accion === 'crear_usuario') {
    $tipo = $_POST['tipo_usuario']; // viene del <select>
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    
    // CASO ALUMNO
    if ($tipo == 'alumno' || $tipo == 'miniprofesor') {
        $carnet = $_POST['carnet'];
        $clase = $_POST['clase'];
        
        // Llamamos a la función con los datos de alumno
        if (crearUsuario('alumno', $nombre, $apellido, $carnet, $clase, 3, 3)) {
            echo json_encode(['success' => true, 'mensaje' => 'Alumno creado con límite de 3 libros']);
        } else {
            echo json_encode(['success' => false, 'mensaje' => 'Error al crear alumno']);
        }

    // CASO PROFESOR / ADMIN
    } else {
        $email = $_POST['email'];
        $contrasenia = $_POST['contrasenia'];
        $id_rol = ($tipo == 'profesor') ? 2 : 1; // 2=profe, 1=admin
        
        // Llamamos a la función con los datos de staff
        if (crearUsuario($tipo_registro, $nombre, $apellido, $dato1, $dato2, $dato3 , $dato4 )) {
            echo json_encode(['success' => true, 'mensaje' => 'Usuario Staff creado correctamente']);
        } else {
            echo json_encode(['success' => false, 'mensaje' => 'Error: El email ya existe o falló la BD']);
        }
    }
    exit;
}

elseif ($accion === 'editar_usuario') {
    $id = $_POST['id_usuario'];
    $tipo = $_POST['tipo_usuario'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $max_libros = $_POST['max_libros'] ?? 3;
    
    if ($tipo == 'alumno' || $tipo == 'miniprofesor') {
        $carnet = $_POST['carnet'];
        $clase = $_POST['clase'];
        
        // Llamamos a la función con los datos de alumno
        if (editarUsuario($id, $tipo, $nombre, $email_carnet, $apellido, $clase  , $contrasenia  , $id_rol  , $max_libros)) {
            echo json_encode(['success' => true, 'mensaje' => 'Alumno actualizado correctamente']);
        } else {
            echo json_encode(['success' => false, 'mensaje' => 'Error al actualizar alumno']);
        }
    } else {
        $email = $_POST['email'];
        $password = $_POST['password'] ?? null;
        $id_rol = $_POST['id_rol'];
        
        if (editarUsuario($id, 'staff', $nombre, $apellido, $email, null, $password, $id_rol, $max_libros)) {
            echo json_encode(['success' => true, 'mensaje' => 'Usuario actualizado']);
        } else {
            echo json_encode(['success' => false, 'mensaje' => 'Error al actualizar usuario']);
        }
    }
}

elseif ($accion === 'eliminar_usuario') {
    $id = $_GET['id'];
    $tipo = $_GET['tipo'];
    
    // Si el que elimina es un profesor, solo puede eliminar alumnos (tipo 3)
    if ($_SESSION['id_rol'] == 2 && $tipo !== 'alumno') {
        header("Location: profesor.php?modulo=alumnos&error=No tienes permisos");
        exit;
    }

    if (eliminarUsuario($id, $tipo)) {
        $redir = ($_SESSION['id_rol'] == 1) ? "admin.php?tab=usuarios" : "profesor.php?modulo=alumnos";
        header("Location: $redir&mensaje=Usuario eliminado");
    } else {
        header("Location: admin.php?modulo=usuarios&error=Error");
    }
}

// ============================================================
// CATÁLOGO
// ============================================================

elseif ($accion === 'crear_libro') {
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $isbn = $_POST['isbn'];
    $editorial = $_POST['editorial'];
    $categoria = $_POST['categoria'];
    $estado = $_POST['estado'] ?? 'activo';
    
    $imagen = '';
    if (isset($_FILES['portada']) && $_FILES['portada']['error'] === 0) {
        $nombreArchivo = uniqid() . '_' . basename($_FILES['portada']['name']);
        $rutaDestino = 'img/' . $nombreArchivo;
        
        if (move_uploaded_file($_FILES['portada']['tmp_name'], $rutaDestino)) {
            $imagen = $nombreArchivo;
        }
    }
    
    if (crearLibro($titulo, $autor, $isbn, $editorial, $categoria, $imagen, $estado)) {
        echo json_encode(['success' => true, 'mensaje' => 'Libro creado']);
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'Error al crear libro']);
    }
}

elseif ($accion === 'editar_libro') {
    $id = $_POST['id_libro'];
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $isbn = $_POST['isbn'];
    $editorial = $_POST['editorial'];
    $categoria = $_POST['categoria'];
    $estado = $_POST['estado'];
    
    $libro = obtenerLibroPorId($id);
    $imagen = $libro['imagen'];
    
    if (isset($_FILES['portada']) && $_FILES['portada']['error'] === 0) {
        // Eliminar imagen anterior
        if (!empty($libro['imagen']) && file_exists('img/' . $libro['imagen'])) {
            unlink('img/' . $libro['imagen']);
        }
        
        $nombreArchivo = uniqid() . '_' . basename($_FILES['portada']['name']);
        $rutaDestino = 'img/' . $nombreArchivo;
        
        if (move_uploaded_file($_FILES['portada']['tmp_name'], $rutaDestino)) {
            $imagen = $nombreArchivo;
        }
    }
    
    if (editarLibro($id, $titulo, $autor, $isbn, $editorial, $categoria, $imagen, $estado)) {
        echo json_encode(['success' => true, 'mensaje' => 'Libro actualizado']);
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'Error al actualizar libro']);
    }
}

elseif ($accion === 'eliminar_libro') {
    $id = $_GET['id'];
    $libro = obtenerLibroPorId($id);
    
    if (!empty($libro['imagen']) && file_exists('img/' . $libro['imagen'])) {
        unlink('img/' . $libro['imagen']);
    }
    
    if (eliminarLibro($id)) {
        header("Location: admin.php?tab=catalogo&mensaje=Libro eliminado");
    } else {
        header("Location: admin.php?tab=catalogo&error=Error al eliminar libro");
    }
}

// ============================================================
// PRÉSTAMOS
// ============================================================

elseif ($accion === 'editar_prestamo') {
    $id_prestamo = $_POST['id_prestamo'];
    $fecha_entrega = $_POST['fecha_entrega'] ?? null;
    $estado = $_POST['estado'] ?? null;
    
    if (editarPrestamo($id_prestamo, $fecha_entrega, $estado)) {
        echo json_encode(['success' => true, 'mensaje' => 'Préstamo actualizado']);
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'Error al actualizar préstamo']);
    }
}

elseif ($accion === 'eliminar_prestamo') {
    $id = $_GET['id'];
    
    if (eliminarPrestamo($id)) {
        header("Location: admin.php?tab=prestamos&mensaje=Préstamo eliminado");
    } else {
        header("Location: admin.php?tab=prestamos&error=Error al eliminar préstamo");
    }
}

elseif ($accion === 'aprobar_prestamo') {
    $id = $_GET['id'];
    
    // Al aprobar, la función actualizarEstadoPrestamo ya debe hacer el UPDATE en la BD
    if (actualizarEstadoPrestamo($id, 'aprobado')) {
        $redir = ($_SESSION['id_rol'] == 1) ? "admin.php?tab=prestamos" : "profesor.php?modulo=prestamos";
        header("Location: $redir&mensaje=Préstamo guardado y aprobado");
    } else {
        header("Location: admin.php?tab=prestamos&error=Error");
    }
}

// ============================================================
// CUENTA
// ============================================================

elseif ($accion === 'cambiar_contrasenia') {
    if (!isset($_SESSION['id_usuario'])) {
        echo json_encode(['success' => false, 'mensaje' => 'No hay sesión activa']);
        exit;
    }

    $id_usuario = $_SESSION['id_usuario'];
    $contrasenia_actual = $_POST['contrasenia_actual'] ;
    $nueva_contrasenia = $_POST['nueva_contrasenia'] ;
    $nuevo_email = $_POST['nuevo_email'] ?? null;
    
    // Verificar contraseña actual
    $info = obtenerInfoAdmin($id_usuario);
    if ($info['contrasenia'] !== $contrasenia_actual) {
        echo json_encode(['success' => false, 'mensaje' => 'La contraseña actual es incorrecta']);
        exit;
    }
    
    $success = true;
    
    if (!empty($nuevo_email)) {
        $success = $success && actualizarEmail($id_usuario, $nuevo_email);
    }
    
    if (!empty($nueva_contrasenia)) {
        $success = $success && actualizarContraseña($id_usuario, $nueva_contrasenia);
    }
    
    if ($success) {
        echo json_encode(['success' => true, 'mensaje' => 'Datos actualizados']);
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'Error al actualizar datos']);
    }
}

else {
    echo json_encode(['success' => false, 'mensaje' => 'Acción no reconocida']);
}

?>
