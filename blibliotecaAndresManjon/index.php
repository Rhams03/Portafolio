<?php
session_start();
include 'conexion.php';
include 'admin_api.php';

$iconos = [
    "INFANTIL Y 1º CICLO" => "👶",
    "2º Y 3º CICLO" => "👦",
    "ANIMALES Y NATURALEZA" => "🌱",
    "VALORES" => "🤝",
    "EMOCIONES" => "❤️",
    "IGUALDAD" => "⚖️",
    "INGLÉS" => "🇬🇧",
    "COLECCIONES" => "📚",
    "CÓMIC" => "💥",
    "MÚSICA" => "🎵"
];

$search = isset($_GET['q']) ? $conn->real_escape_string(strtolower($_GET['q'])) : '';

$sql = "SELECT * FROM catalogo";
if ($search) {
    $sql .= " WHERE LOWER(titulo) LIKE '%$search%' 
              OR LOWER(autor) LIKE '%$search%' 
              OR categoria LIKE '%$search%'";
}

$puede_pedir = true;
$mensaje_limite = "";
$prestados = 0;

if (isset($_SESSION['usuario_id'])) {
    $rol = $_SESSION['id_rol'];
    $user_id = $_SESSION['usuario_id'];
    
    if ($rol == 3) { // Es ALUMNO
        // 1. Obtenemos su límite actual desde la BD (por si el admin lo cambió)
        $res = $conn->query("SELECT max_libros_prestables FROM alumnado WHERE id_alumno = $user_id");
        $datos = $res->fetch_assoc();
        $limite = $datos['max_libros_prestables'] ?? 2;

        // 2. Contamos sus préstamos activos
        $prestados = contarPrestamosActivos($user_id, 'alumno');

        // 3. Verificamos si puede pedir más
        if ($prestados >= $limite) {
            $puede_pedir = false;
            $mensaje_limite = "🚫 Has alcanzado tu límite de $limite libros.";
        } else {
            $disponibles = $limite - $prestados;
            $mensaje_limite = "📚 Puedes pedir $disponibles libro(s) más.";
        }
    } else {
        // Es PROFESOR o ADMIN (Ilimitado)
        $puede_pedir = true;
        $mensaje_limite = "✨ Modo Profesor: Préstamos ilimitados";
    }
}

$result = $conn->query($sql);
$categorias = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $catNombre = !empty($row['categoria']) ? $row['categoria'] : "Otros";
        if (!isset($categorias[$catNombre])) {
            $categorias[$catNombre] = [
                "color" => $row['ubicacion_por_colores'],
                "icono" => isset($iconos[$catNombre]) ? $iconos[$catNombre] : "📗",
                "libros" => []
            ];
        }
        $categorias[$catNombre]["libros"][] = [
            "id"     => $row['id'],
            "titulo" => $row['titulo'],
            "autor"  => $row['autor'],
            "isbn"   => $row['codigo_de_barra'],
            "img"    => isset($row['imagen']) ? $row['imagen'] : 'default.jpg'
        ];
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Biblioteca CEIP Andrés Manjón</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="bg-pattern">

<header class="main-header">
    <section class="header-left">
        <img src="img/logo.jpg" alt="Logo Colegio" class="school-logo">
        <div class="school-info">
            <h1>Andrés Manjón</h1>
            <span>Biblioteca Escolar</span>
        </div>
    </section>

    <section class="header-right">
            <?php if(isset($_SESSION['nombre'])): ?>
            <div style="text-align: right; margin-right: 15px;">
                <span class="user-name" style="display: block; color: #fff9f9;">Hola, <?php echo $_SESSION['nombre']; ?>!</span>
                <small style="color: #fff9f9; font-weight: bold;"><?php echo $mensaje_limite; ?></small>
            </div>
            
            <?php if(isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 1): ?>
                <a href="admin.php" class="btn-admin">⚙️ Panel Admin</a>
            <?php endif; ?>

            <?php if(isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 2): ?>
            <a href="profesor.php" class="btn-volver-panel"> Volver al Panel</a>
            <?php endif; ?>

            <a href="logout.php" class="btn-logout">Salir</a>
            <?php else: ?>
            <a href="solicitud.php" class="btn-loan">Préstamo</a>
            <a href="sesion.php" class="btn-login">Iniciar Sesion</a>
            <?php endif; ?>
    </section>
</header>

<section class="search-area" style="margin-top: 80px;"> <div class="search-container">
        <input type="text" id="inputBusqueda" placeholder="Escribe aquí para buscar..." autocomplete="off">
    </div>
</section>

<div class="super-contenedor">
    
    <nav class="side-nav">
        <h3>Categorías</h3>
        <ul>
            <?php foreach($categorias as $nombreCat => $data): 
                $idCat = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $nombreCat)));
            ?>
                <li><a href="#<?php echo $idCat; ?>"><?php echo $data['icono'] . " " . $nombreCat; ?></a></li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <main class="catalog" id="contenedorLibros">
        <?php foreach($categorias as $nombreCat => $data): 
            $idCat = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $nombreCat)));
        ?>
            <section class="category-block" id="<?php echo $idCat; ?>">
                <h2 style="background: <?php echo $data['color']; ?>; color: white; padding: 10px; border-radius: 8px;">
                    <?php echo $data['icono'] . " " . $nombreCat; ?>
                </h2>
                <div class="book-grid">
                    <?php foreach($data['libros'] as $libro): ?>
                        <a href="detalles.php?id=<?php echo $libro['id']; ?>" class="book-link">
                            <article class="book-card">
                                <div class="book-image">
                                    <?php 
                                        $nombreImagen = ($libro['img'] == 'default.jpg' || empty($libro['img'])) 
                                                        ? 'no_hay_imagen.jgp.jpg' : $libro['img'];
                                    ?>
                                    <img src="img/<?php echo $nombreImagen; ?>" alt="Portada" 
                                         onerror="this.src='img/no_hay_imagen.jgp.jpg';">
                                </div>
                                <div class="book-details">
                                    <h3><?php echo $libro['titulo']; ?></h3>
                                    <p><?php echo $libro['autor']; ?></p>
                                    <span class="isbn">ISBN: <?php echo $libro['isbn']; ?></span>
                                </div>
                            </article>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </main>
</div>

<button id="btnSubir" title="Ir arriba">▲</button>

<script>
    // AJAX para búsqueda: Conectado al inputBusqueda original
    const inputBusqueda = document.getElementById('inputBusqueda');
    const contenedor = document.getElementById('contenedorLibros');

    inputBusqueda.addEventListener('keyup', function() {
        let valor = inputBusqueda.value;
        let peticion = new XMLHttpRequest();
        peticion.open('POST', 'buscar.php', true);
        peticion.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        peticion.onload = function() {
            contenedor.innerHTML = this.responseText;
        }
        peticion.send('consulta=' + valor);
    });

    // Botón Subir
    const btnSubir = document.getElementById("btnSubir");
    window.onscroll = function() {
        if (document.documentElement.scrollTop > 300) {
            btnSubir.style.display = "block";
        } else {
            btnSubir.style.display = "none";
        }
    };
    btnSubir.onclick = function() {
        window.scrollTo({top: 0, behavior: 'smooth'});
    };
</script>
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
</html>