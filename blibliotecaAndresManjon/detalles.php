<?php
include 'conexion.php';
include 'admin_api.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM catalogo WHERE id = ?"; // Ajusta 'id' si tu columna se llama distinto
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$id_libro = $resultado->fetch_assoc();

if (!$id_libro) {
    die("Libro no encontrado.");
}

// 1. Lógica de disponibilidad física del libro
$foto = (!empty($id_libro['imagen'])) ? "img/" . $id_libro['imagen'] : "img/no_hay_imagen.jgp.jpg";
$disponible = (isset($id_libro['disponible']) && $id_libro['disponible'] == 1) ? true : false;

// 2. LÓGICA DE LÍMITE DE USUARIO
$puede_solicitar = true;
$mensaje_error = "";

if (isset($_SESSION['usuario_id'])) {
    $rol = $_SESSION['id_rol'];
    $user_id = $_SESSION['usuario_id'];

    if ($rol == 3) { // Si es ALUMNO
        // Consultamos su límite actual
        $res = $conn->query("SELECT max_libros_prestables FROM alumnado WHERE id_alumno = $user_id");
        $datos = $res->fetch_assoc();
        $limite = $datos['max_libros_prestables'] ?? 2;

        // Contamos cuántos tiene prestados (usando la función que creamos en admin_api)
        $prestados = contarPrestamosActivos($user_id, 'alumno');

        if ($prestados >= $limite) {
            $puede_solicitar = false;
            $mensaje_error = "Has alcanzado tu límite de $limite libros prestados.";
        }
    }
}

// Lógica de imagen
$foto = (!empty($id_libro['imagen'])) ? "img/" . $id_libro['imagen'] : "img/no_hay_imagen.jgp.jpg";
// Lógica de disponibilidad (Asumiendo que tienes una columna 'estado' o 'disponible')
$disponible = (isset($id_libro['disponible']) && $id_libro['disponible'] == 1) ? true : false; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $id_libro['titulo']; ?> - Detalle</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="login-body">

<div class="login-container">
    <div class="login-visual-panel detail-visual">
        <img src="<?php echo $foto; ?>" alt="Portada" onerror="this.src='img/no_hay_imagen.jgp.jpg';">
        <div class="visual-text">
            <h2><?php echo $id_libro['categoria']; ?></h2>
        </div>
    </div>

    <div class="login-form-panel">
        <a href="index.php" class="back-to-catalog">← Volver al Catálogo</a>
        
        <div class="welcome-header">
            <h1><?php echo $id_libro['titulo']; ?></h1>
            <p>De: <strong><?php echo $id_libro['autor']; ?></strong></p>
        </div>

        <div class="book-info-list">
            <div class="info-item">
                <span>ISBN:</span>
                <strong><?php echo $id_libro['codigo_de_barra']; ?></strong>
            </div>
            <div class="info-item">
                <span>Categoría:</span>
                <strong><?php echo $id_libro['categoria']; ?></strong>
            </div>
            <div class="info-item">
                <span>Estado:</span>
                <strong class="<?php echo $disponible ? 'status-ok' : 'status-ko'; ?>">
                    <?php echo $disponible ? '✅ Disponible' : '❌ No disponible'; ?>
                </strong>
            </div>
        </div>

        <div style="margin-top: 30px;">
            <?php if($disponible): ?>
                <a href="solicitud.php?id_libro=<?php echo $id_libro['id']; ?>" class="btn-loan-detail">Solicitar Préstamo</a>
            <?php else: ?>
                <button class="btn-loan-detail disabled" disabled>No disponible</button>
            <?php endif; ?>
        </div>
    </div>
</div>
<footer class="site-footer">
  <div class="footer-content">
    
    <div class="footer-left">
      <strong>RRAZ studios</strong> &copy; 2026 | <a href="https://www.aepd.es/politica-de-privacidad-y-aviso-legal">Privacy Policy</a>
    </div>

    <div class="footer-right">
      <a href="https://www.instagram.com/ceip_andresmanjon/" class="social-link">
        <img src="img/icono_instagram.png" alt="Instagram"></img>
      </a>
      <a href="https://www.micole.net/zaragoza/zaragoza/colegio-andres-manjon" class="social-link">
        <img src="img/icono_micole.png" alt="Micole"></img>
      </a>
      <a href="http://www.educateca.com/centros/ceip-andres-manjon-z.asp#f5" class="social-link">
        <img src="img/icono_educateca.jpg" alt="Educateca"></img>
      </a>
      <a href="https://www.zaragoza.es/sede/servicio/equipamiento/548" class="social-link">
        <img src="img/icono_ayuntamiento.png" alt="Zaragoza"></img>
      </a>
    </div>
    
  </div>
</footer>

<script src="https://kit.fontawesome.com/your-code.js" crossorigin="anonymous"></script>
</body>
</body>
</html>