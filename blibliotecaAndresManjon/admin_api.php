<?php
/**
 * ADMIN API - Backend para el Panel de Administración
 * Archivo de funciones que manejan todas las operaciones del admin
 */

include 'conexion.php';

// ============================================================
//                    USUARIOS (CRUD)
// ============================================================

if (!function_exists('obtenerUsuarios')) {
    function obtenerUsuarios($filtro_rol = null, $filtro_clase = null, $buscar = null) {
        global $conn;
        
        $sql = "SELECT 'admin_profesor' AS tipo, id_usuario AS id, nombre, apellido, username AS email, 
                id_rol, max_libros_prestables, fecha_creacion FROM usuarios";
        
        $condiciones = [];
        if ($filtro_rol) $condiciones[] = "id_rol = " . intval($filtro_rol);
        if ($buscar) $condiciones[] = "(nombre LIKE '%$buscar%' OR apellido LIKE '%$buscar%' OR username LIKE '%$buscar%')";
        
        if (!empty($condiciones)) {
            $sql .= " WHERE " . implode(" AND ", $condiciones);
        }
        
        $sql .= " UNION ALL SELECT 'alumno' AS tipo, id_alumno AS id, nombre, apellido, carnet AS email, 
                 3 AS id_rol, max_libros_prestables, fecha_creacion FROM alumnado";
        
        if ($filtro_clase) {
            $sql .= ($buscar ? " AND " : " WHERE ") . "clase = '$filtro_clase'";
        }
        if ($buscar) {
            $sql .= " AND (nombre LIKE '%$buscar%' OR apellido LIKE '%$buscar%')";
        }
        
        $resultado = $conn->query($sql);
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }
}

// ... código anterior de admin_api.php ...

// ============================================================
//              CREAR USUARIO (Función Faltante)
// ============================================================
if (!function_exists('crearUsuario')) {
    function crearUsuario($tipo_registro, $nombre, $apellido, $dato1, $dato2, $dato3 , $dato4 ) {
        global $conn;

        $nombre_completo = trim($nombre . ' ' . $apellido);

        if ($tipo_registro === 'alumno') {
            // --- CASO ALUMNO ---
            $carnet = $dato1;
            $clase = $dato2;

            // Verificamos si ya existe ese carnet para no dar error SQL
            $check = $conn->prepare("SELECT id_alumno FROM alumnado WHERE carnet = ?");
            $check->bind_param("s", $carnet);
            $check->execute();
            if ($check->get_result()->num_rows > 0) return false;

            $sql = "INSERT INTO alumnado (nombre, carnet, clase, fecha_creacion, activo) VALUES (?, ?, ?, NOW(), 1)";
            $stmt = $conn->prepare($sql);
            // "sss" significa 3 Strings: nombre, carnet, curso
            $stmt->bind_param("sss", $nombre_completo, $carnet, $clase);

        } else { 
            // --- CASO STAFF (Profesor/Admin) ---
            // Recibimos: dato1 = email, dato2 = password, dato3 = id_rol
            $email = $dato1;
            $contrasenia = $dato2; 
            $rol = $dato3;

            // Verificamos si ya existe el usuario
            $check = $conn->query("SELECT id_usuario FROM usuarios WHERE username = '$email'");
            if ($check->num_rows > 0) return false;

            $sql = "INSERT INTO usuarios (nombre, username, contrasenia, id_rol, fecha_creacion) VALUES (?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            // "sssi" = string, string, string, int
            $stmt->bind_param("sssi", $nombre_completo, $email, $contrasenia, $rol);
        }

        // Ejecutamos y devolvemos true o false
        return $stmt->execute();
    }
}
if (!function_exists('editarUsuario')) {
    function editarUsuario($id, $tipo, $nombre, $email_carnet, $apellido, $clase  , $contrasenia  , $id_rol  , $max_libros  ) {
        global $conn;
        
        if ($tipo == 'alumno' || $tipo == 'miniprofesor') {
            $sql = "UPDATE alumnado SET nombre = ?, apellido = ?, carnet = ?, clase = ?";
            if ($max_libros) $sql .= ", max_libros_prestables = " . intval($max_libros);
            $sql .= " WHERE id_alumno = ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $nombre, $apellido, $email_carnet, $clase, $id);
        } else {
            $sql = "UPDATE usuarios SET nombre = ?, apellido = ?, username = ?, email = ?, id_rol = ?";
            if ($contrasenia) $sql .= ", contrasenia = ?";
            if ($max_libros) $sql .= ", max_libros_prestables = " . intval($max_libros);
            $sql .= " WHERE id_usuario = ?";
            
            if ($contrasenia) {
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssisi", $nombre, $apellido, $email_carnet, $email_carnet, $id_rol, $contrasenia, $id);
            } else {
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssi", $nombre, $apellido, $email_carnet, $email_carnet, $id_rol, $id);
            }
        }
        return $stmt->execute();
    }
}
if (!function_exists('eliminarUsuario')) {
    function eliminarUsuario($id, $tipo) {
        global $conn;
        
        if ($tipo == 'alumno' || $tipo == 'miniprofesor') {
            $sql = "DELETE FROM alumnado WHERE id_alumno = ?";
        } else {
            $sql = "DELETE FROM usuarios WHERE id_usuario = ?";
        }
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
// ============================================================
//                    CATÁLOGO (CRUD)
// ============================================================
if (!function_exists('obtenerLibros')) {
    function obtenerLibros($filtro_categoria = null, $filtro_estado = null, $buscar = null) {
        global $conn;
        
        $sql = "SELECT * FROM catalogo WHERE 1=1";
        
        if ($filtro_categoria) $sql .= " AND categoria = '$filtro_categoria'";
        if ($filtro_estado) $sql .= " AND estado = '$filtro_estado'";
        if ($buscar) $sql .= " AND (titulo LIKE '%$buscar%' OR autor LIKE '%$buscar%' OR codigo_de_barra LIKE '%$buscar%')";
        
        $sql .= " ORDER BY titulo ASC";
        
        $resultado = $conn->query($sql);
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }
}
if (!function_exists('obtenerLibroPorId')) {
    function obtenerLibroPorId($id) {
        global $conn;
        $sql = "SELECT * FROM catalogo WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}

if (!function_exists('crearLibro')) {
    function crearLibro($titulo, $autor, $isbn, $editorial, $categoria, $imagen, $estado = 'activo') {
        global $conn;
        
        $sql = "INSERT INTO catalogo (titulo, autor, codigo_de_barra, editorial, categoria, imagen, estado, fecha_agregado, cantidad_total) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 1)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $titulo, $autor, $isbn, $editorial, $categoria, $imagen, $estado);
        return $stmt->execute();
    }
}

if (!function_exists('editarLibro')) {
    function editarLibro($id, $titulo, $autor, $isbn, $editorial, $categoria, $imagen = null, $estado = null) {
        global $conn;
        
        $sql = "UPDATE catalogo SET titulo = ?, autor = ?, codigo_de_barra = ?, editorial = ?, categoria = ?";
        if ($imagen) $sql .= ", imagen = ?";
        if ($estado) $sql .= ", estado = ?";
        $sql .= " WHERE id = ?";
        
        $stmt = $conn->prepare($sql);
        
        if ($imagen && $estado) {
            $stmt->bind_param("sssssssi", $titulo, $autor, $isbn, $editorial, $categoria, $imagen, $estado, $id);
        } elseif ($imagen) {
            $stmt->bind_param("ssssssi", $titulo, $autor, $isbn, $editorial, $categoria, $imagen, $id);
        } elseif ($estado) {
            $stmt->bind_param("ssssssii", $titulo, $autor, $isbn, $editorial, $categoria, $estado, $id);
        } else {
            $stmt->bind_param("ssssssi", $titulo, $autor, $isbn, $editorial, $categoria, $id);
        }
        
        return $stmt->execute();
    }
}

if (!function_exists('eliminarLibro')) {
    function eliminarLibro($id) {
        global $conn;
        $sql = "DELETE FROM catalogo WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
// ============================================================
//                 PRÉSTAMOS (CRUD + Estadísticas)
// ============================================================
if (!function_exists('obtenerPrestamos')) {
    function obtenerPrestamos($estado = null, $filtro_usuario = null) {
        global $conn;
        
        $sql = "SELECT p.*, COALESCE(c.titulo, 'Desconocido') AS libro_titulo, 
                COALESCE(u.nombre, a.nombre) AS usuario_nombre
                FROM prestamos p
                LEFT JOIN catalogo c ON p.id_libro = c.id
                LEFT JOIN usuarios u ON p.id_usuario = u.id_usuario
                LEFT JOIN alumnado a ON p.id_alumno = a.id_alumno
                WHERE 1=1";
        
        if ($estado) $sql .= " AND p.estado_prestamo = '$estado'";
        if ($filtro_usuario) $sql .= " AND (u.nombre LIKE '%$filtro_usuario%' OR a.nombre LIKE '%$filtro_usuario%' OR u.apellido LIKE '%$filtro_usuario%')";
        
        $sql .= " ORDER BY p.fecha_inicio DESC";
        
        $resultado = $conn->query($sql);
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }
}

if (!function_exists('crearPrestamo')) {
    function crearPrestamo($id_usuario_o_alumno, $es_alumno, $id_libro, $fecha_entrega) {
        global $conn;
        
        if ($es_alumno) {
            $sql = "INSERT INTO prestamos (id_alumno, id_libro, fecha_inicio, fecha_entrega_esperada, estado_prestamo) 
                    VALUES (?, ?, NOW(), ?, 'pendiente')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $id_usuario_o_alumno, $id_libro, $fecha_entrega);
        } else {
            $sql = "INSERT INTO prestamos (id_usuario, id_libro, fecha_inicio, fecha_entrega_esperada, estado_prestamo) 
                    VALUES (?, ?, NOW(), ?, 'pendiente')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $id_usuario_o_alumno, $id_libro, $fecha_entrega);
        }
        
        return $stmt->execute();
    }
}

if (!function_exists('actualizarEstadoPrestamo')) {
    function actualizarEstadoPrestamo($id_prestamo, $nuevo_estado) {
        global $conn;
        
        $sql = "UPDATE prestamos SET estado_prestamo = ?";
        
        if ($nuevo_estado == 'devuelto') {
            $sql .= ", fecha_entrega_real = NOW()";
        } elseif ($nuevo_estado == 'retrasado') {
            // Se establece automáticamente si fecha_entrega_esperada < hoy
        }
        
        $sql .= " WHERE id_prestamo = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $nuevo_estado, $id_prestamo);
        return $stmt->execute();
    }
}

if (!function_exists('editarPrestamo')) {
    function editarPrestamo($id_prestamo, $fecha_entrega = null, $estado = null) {
        global $conn;
        
        $actualizaciones = [];
        $params = [];
        $tipos = "";
        
        if ($fecha_entrega) {
            $actualizaciones[] = "fecha_entrega_esperada = ?";
            $params[] = $fecha_entrega;
            $tipos .= "s";
        }
        
        if ($estado) {
            $actualizaciones[] = "estado_prestamo = ?";
            $params[] = $estado;
            $tipos .= "s";
        }
        
        if (empty($actualizaciones)) return false;
        
        $params[] = $id_prestamo;
        $tipos .= "i";
        
        $sql = "UPDATE prestamos SET " . implode(", ", $actualizaciones) . " WHERE id_prestamo = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($tipos, ...$params);
        return $stmt->execute();
    }
}

if (!function_exists('eliminarPrestamo')) {
    function eliminarPrestamo($id_prestamo) {
        global $conn;
        $sql = "DELETE FROM prestamos WHERE id_prestamo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_prestamo);
        return $stmt->execute();
    }
}

if (!function_exists('obtenerEstadisticasPrestamos')) {
    function obtenerEstadisticasPrestamos() {
        global $conn;
        
        $stats = [];
        
        // Total de préstamos
        $result = $conn->query("SELECT COUNT(*) as total FROM prestamos");
        $stats['total'] = $result->fetch_assoc()['total'];
        
        // Pendientes de aprobación
        $result = $conn->query("SELECT COUNT(*) as total FROM prestamos WHERE estado_prestamo = 'pendiente'");
        $stats['pendientes'] = $result->fetch_assoc()['total'];
        
        // Activos
        $result = $conn->query("SELECT COUNT(*) as total FROM prestamos WHERE estado_prestamo = 'aprobado'");
        $stats['activos'] = $result->fetch_assoc()['total'];
        
        // Devueltos
        $result = $conn->query("SELECT COUNT(*) as total FROM prestamos WHERE estado_prestamo = 'devuelto'");
        $stats['devueltos'] = $result->fetch_assoc()['total'];
        
        // Retrasados
        $result = $conn->query("SELECT COUNT(*) as total FROM prestamos WHERE estado_prestamo = 'retrasado'");
        $stats['retrasados'] = $result->fetch_assoc()['total'];
        
        return $stats;
    }
}

if (!function_exists('contarPrestamosActivos')) {
// Función para contar cuántos libros tiene un usuario actualmente (pendientes o aprobados)
    function contarPrestamosActivos($id_usuario, $tipo_usuario) {
        global $conn;
        // Si es alumno buscamos por id_alumno, si es staff por id_usuario
        $columna = ($tipo_usuario === 'alumno') ? 'id_alumno' : 'id_usuario';
        
        $sql = "SELECT COUNT(*) as total FROM prestamos 
                WHERE $columna = ? AND estado IN ('pendiente', 'aprobado')";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total'];
    }
}
// ============================================================
//              CUENTA (Información personal)
// ============================================================
if (!function_exists('obtenerInfoAdmin')) {
    function obtenerInfoAdmin($id_usuario) {
        global $conn;
        $sql = "SELECT * FROM usuarios WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}

if (!function_exists('actualizarContraseña')) {
    function actualizarContraseña($id_usuario, $nueva_password) {
        global $conn;
        $sql = "UPDATE usuarios SET contrasenia = ? WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $nueva_password, $id_usuario);
        return $stmt->execute();
    }
}

if (!function_exists('actualizarEmail')) {
    function actualizarEmail($id_usuario, $nuevo_email) {
        global $conn;
        $sql = "UPDATE usuarios SET username = ?, email = ? WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $nuevo_email, $nuevo_email, $id_usuario);
        return $stmt->execute();
    }
}

if (!function_exists('obtenerHistorialRoles')) {
    function obtenerHistorialRoles($id_usuario) {
        global $conn;
        
        // Por ahora retornamos el rol actual (puede expandirse con historial JSON)
        $sql = "SELECT id_rol, fecha_creacion FROM usuarios WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>
