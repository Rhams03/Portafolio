<?php
session_start();
include 'conexion.php';
include 'admin_api.php';

$mensaje = '';

// 1. SEGURIDAD: Si no hay sesión, fuera.
if (!isset($_SESSION['nombre'])) {
    header("Location: sesion.php?return_to=solicitud.php");
    exit();
}

$id_usuario_sesion = $_SESSION['usuario_id'];
$id_rol = $_SESSION['id_rol'];

// 2. LOGICA: Si venimos del Index con un libro seleccionado
$libro_preseleccionado = null;
if (isset($_GET['id_libro'])) {
    $id_req = intval($_GET['id_libro']);
    $res_lib = $conn->query("SELECT * FROM catalogo WHERE id = $id_req");
    if ($res_lib->num_rows > 0) {
        $libro_preseleccionado = $res_lib->fetch_assoc();
    }
}

// 3. API AJAX: Para el buscador en vivo (Debe estar ANTES de cualquier HTML)
if (isset($_GET['buscar_libro'])) {
    // Limpiamos buffer por si acaso hay espacios en blanco en los includes
    ob_clean(); 
    $titulo = trim($_GET['buscar_libro']);
    $sql_libro = "SELECT id, titulo, autor, isbn, portada, imagen FROM catalogo WHERE LOWER(titulo) LIKE LOWER(?) LIMIT 10";
    $stmt = $conn->prepare($sql_libro);
    $busqueda = "%$titulo%";
    $stmt->bind_param("s", $busqueda);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $libros = [];
    while ($row = $result->fetch_assoc()) {
        // Aseguramos que 'imagen' tenga valor (algunas BD usan 'portada', otras 'imagen')
        $row['imagen'] = !empty($row['imagen']) ? $row['imagen'] : ($row['portada'] ?? '');
        $libros[] = $row;
    }
    $stmt->close();
    
    header('Content-Type: application/json');
    echo json_encode($libros);
    exit();
}

// 4. LÓGICA DE PRE-VERIFICACIÓN (Límites de libros)
$puede_pedir_mas = true;
$aviso_limite = "";

if ($id_rol == 3) { // Es Alumno
    $res = $conn->query("SELECT max_libros_prestables FROM alumnado WHERE id_alumno = $id_usuario_sesion");
    $user_data = $res->fetch_assoc();
    $limite = $user_data['max_libros_prestables'] ?? 2;
    
    if (function_exists('contarPrestamosActivos')) {
        $actuales = contarPrestamosActivos($id_usuario_sesion, 'alumno');
        if ($actuales >= $limite) {
            $puede_pedir_mas = false;
            $aviso_limite = "⚠️ Has alcanzado tu límite de $limite libros. Debes devolver alguno antes.";
        }
    }
}

// 5. PROCESAR FORMULARIO (POST)
// 5. PROCESAR FORMULARIO (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario_sesion = $_SESSION['usuario_id'];
    $id_libro = isset($_POST['id_libro']) ? intval($_POST['id_libro']) : 0;
    $id_rol = $_SESSION['id_rol'];
    
    // Variables para la base de datos
    $db_id_alumno = null;
    $db_id_usuario = null;
    $error_validacion = false;

    // A. VALIDACIÓN SEGÚN ROL
    if ($id_rol == 3) { // ES ALUMNO
        $db_id_alumno = $id_usuario_sesion;
        $numero_carnet = trim($_POST['numero_carnet'] ?? '');

        // 1. Verificar si el carnet es obligatorio y llegó vacío
        if (empty($numero_carnet)) {
            $mensaje = '<div class="alert-error">⚠️ El número de carnet es obligatorio para alumnos.</div>';
            $error_validacion = true;
        }
        
        // 2. Verificar límite de préstamos (usando tu lógica de pre-verificación)
        if (!$puede_pedir_mas) {
            $mensaje = '<div class="alert-error">' . $aviso_limite . '</div>';
            $error_validacion = true;
        }

    } else { // ES PROFESOR O ADMIN
        $db_id_usuario = $id_usuario_sesion;
        // Aquí podrías añadir validación de password si fuera necesario, 
        // pero para la inserción técnica, usamos el ID de la sesión.
    }

    // B. INSERCIÓN SI TODO ESTÁ BIEN
    if (!$error_validacion && $id_libro > 0) {
        $fecha_inicio = date('Y-m-d H:i:s');
        $fecha_esperada = date('Y-m-d', strtotime('+15 days'));

        // El SQL con 5 parámetros variables (?)
        $sql_insert = "INSERT INTO prestamos (id_alumno, id_usuario, id_libro, fecha, estado, estado_prestamo, fecha_inicio, fecha_entrega_esperada) 
                       VALUES (?, ?, ?, NOW(), 'pendiente', 'pendiente', ?, ?)";
        
        $stmt_insert = $conn->prepare($sql_insert);
        
        if ($stmt_insert) {
            // "iiiss" -> 3 Integers (alumno, usuario, libro) y 2 Strings (fechas)
            // Esto soluciona el error ArgumentCountError
            $stmt_insert->bind_param("iiiss", 
                $db_id_alumno, 
                $db_id_usuario, 
                $id_libro, 
                $fecha_inicio, 
                $fecha_esperada
            );
            
            if ($stmt_insert->execute()) {
                $mensaje = '<div class="alert-success">✅ ¡Solicitud enviada! Un profesor revisará tu préstamo pronto.</div>';
            } else {
                $mensaje = '<div class="alert-error">❌ Error en la base de datos: ' . $stmt_insert->error . '</div>';
            }
            $stmt_insert->close();
        }
    } elseif ($id_libro <= 0) {
        $mensaje = '<div class="alert-error">⛔ Por favor, selecciona un libro válido.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Préstamo - CEIP Andrés Manjón</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="login-body">

<div class="login-container">
    <div class="login-visual-panel">
        <img id="portada-dinamica" src="<?php echo ($libro_preseleccionado && !empty($libro_preseleccionado['imagen'])) ? 'img/'.$libro_preseleccionado['imagen'] : 'img/fondo.png'; ?>" alt="Portada">
        <div class="visual-text">
            <h1 id="titulo-visual"><?php echo $libro_preseleccionado ? htmlspecialchars($libro_preseleccionado['titulo']) : '¡A leer!'; ?></h1>
            <p>CEIP Andrés Manjón</p>
        </div>
    </div>

    <div class="login-form-panel">
        <a href="index.php" class="back-to-catalog">← Volver al Catálogo</a>
        
        <div class="welcome-header">
            <h2>Formulario de Préstamo</h2>
            <?php if(!$puede_pedir_mas && $id_rol == 3) echo "<p style='color:red;'>$aviso_limite</p>"; ?>
        </div>

        <?php if (!empty($mensaje)) echo $mensaje; ?>

        <form method="POST" class="form-login" id="form-prestamo">
            <input type="hidden" name="id_libro" id="id_libro" value="<?php echo $libro_preseleccionado ? $libro_preseleccionado['id'] : ''; ?>">

            <div class="section-container">
                <h3>Identificación de Usuario</h3>
                
                <?php if ($id_rol == 3): // ALUMNO ?>
                    <div class="input-box">
                        <label>NOMBRE COMPLETO</label>
                        <input type="text" value="<?php echo $_SESSION['nombre']; ?>" readonly class="input-readonly">
                    </div>
                    <div class="input-box">
                        <label>NÚMERO DE CARNET</label>
                        <input type="text" name="numero_carnet" placeholder="Ej: 12345ABC" required>
                    </div>

                <?php else: // PROFESOR O ADMIN ?>
                    <div class="input-box">
                        <label>CORREO ELECTRÓNICO</label>
                        <input type="email" name="staff_email" placeholder="tu@email.com" required>
                    </div>
                    <div class="input-box">
                        <label>CONFIRMAR CONTRASEÑA</label>
                        <input type="password" name="staff_pass" placeholder="********" required>
                    </div>
                <?php endif; ?>
            </div>

            <div class="section-container">
                <h3>Detalles del Libro</h3>
                
                <?php if ($libro_preseleccionado): ?>
                    <div class="input-box">
                        <label>TÍTULO</label>
                        <input type="text" value="<?php echo htmlspecialchars($libro_preseleccionado['titulo']); ?>" readonly class="input-readonly">
                    </div>
                    <div class="input-box">
                        <label>DISPONIBILIDAD</label>
                        <input type="text" value="<?php echo (isset($libro_preseleccionado['disponible']) && $libro_preseleccionado['disponible']) ? '✅ Disponible' : '❌ No disponible'; ?>" readonly class="input-readonly">
                    </div>
                <?php else: ?>
                    <div class="input-box">
                        <label>BUSCAR TÍTULO (Escribe para buscar)</label>
                        <input type="text" id="buscar_libro_input" placeholder="Ej: Harry Potter..." autocomplete="off">
                        <div id="sugerencias-libros" class="hidden"></div>
                    </div>
                <?php endif; ?>

                <div class="input-box">
                    <label>FECHA ESTIMADA DE DEVOLUCIÓN</label>
                    <input type="text" id="fecha_devolucion" value="<?php echo date('d/m/Y', strtotime('+15 days')); ?>" readonly class="input-readonly">
                </div>
            </div>

            <button type="submit" class="submit-button" <?php echo ($id_rol == 3 && !$puede_pedir_mas) || ($libro_preseleccionado && isset($libro_preseleccionado['disponible']) && !$libro_preseleccionado['disponible']) ? 'disabled' : ''; ?>>
                CONFIRMAR PRÉSTAMO ✓
            </button>
        </form>

        <div class="register-section">
            <p><a href="logout.php">Cerrar Sesión</a></p>
        </div>

        <div class="school-footer-logos">
            <img src="img/gobierno-aragon.png" alt="Aragón" onerror="this.style.display='none'">
            <img src="img/logo-colegio.png" alt="Colegio" onerror="this.style.display='none'">
        </div>
    </div>
</div>

<footer class="site-footer">
  <div class="footer-content">
    <div class="footer-left">
      <strong>RRAZ studios</strong> &copy; 2026 | <a href="#">Privacy Policy</a>
    </div>
    <div class="footer-right">
      <a href="#" class="social-link"><img src="img/icono_instagram.png" alt="Instagram"></a>
    </div>
  </div>
</footer>

<script>
    window.addEventListener('load', function() {
        // 1. Calcular Fecha Automática
        const fechaActual = new Date();
        const fechaDevolucion = new Date(fechaActual.getTime() + (15 * 24 * 60 * 60 * 1000));
        const opciones = { day: '2-digit', month: '2-digit', year: 'numeric' };
        
        const inputFecha = document.getElementById('fecha_devolucion');
        if(inputFecha) {
            inputFecha.value = fechaDevolucion.toLocaleDateString('es-ES', opciones);
        }

        // 2. Lógica del Buscador
        const inputBusqueda = document.getElementById('buscar_libro_input');
        const sugerencias = document.getElementById('sugerencias-libros');

        if(inputBusqueda) {
            inputBusqueda.addEventListener('input', function() {
                let valor = this.value;
                if (valor.length < 3) {
                    sugerencias.innerHTML = '';
                    sugerencias.classList.add('hidden');
                    return;
                }
                
                // Llamada AJAX
                fetch(`solicitud.php?buscar_libro=${encodeURIComponent(valor)}`)
                    .then(res => res.json())
                    .then(data => {
                        sugerencias.innerHTML = '';
                        if(data.length > 0) {
                            sugerencias.classList.remove('hidden');
                            data.forEach(libro => {
                                let div = document.createElement('div');
                                div.className = 'sugerencia-item';
                                div.innerHTML = `<strong>${libro.titulo}</strong> <small>(${libro.autor})</small>`;
                                
                                // Al hacer click en una sugerencia
                                div.onclick = () => {
                                    // 1. Guardamos el ID en el input oculto (LO MAS IMPORTANTE)
                                    document.getElementById('id_libro').value = libro.id;
                                    
                                    // 2. Actualizamos lo visual
                                    inputBusqueda.value = libro.titulo;
                                    document.getElementById('titulo-visual').innerText = libro.titulo;
                                    sugerencias.classList.add('hidden');
                                    
                                    if(libro.imagen) {
                                        document.getElementById('portada-dinamica').src = 'img/' + libro.imagen;
                                    }
                                };
                                sugerencias.appendChild(div);
                            });
                        } else {
                            sugerencias.classList.add('hidden');
                        }
                    })
                    .catch(err => console.error('Error:', err));
            });

            // Cerrar sugerencias al hacer click fuera
            document.addEventListener('click', function(e) {
                if (e.target !== inputBusqueda && e.target !== sugerencias) {
                    sugerencias.classList.add('hidden');
                }
            });
        }
    });
</script>
<script src="https://kit.fontawesome.com/your-code.js" crossorigin="anonymous"></script>
</body>
</html>