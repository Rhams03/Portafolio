<?php
include 'conexion.php';

// Array de iconos (mismo que en el index para mantener consistencia)
$iconos = [
    "INFANTIL Y 1º CICLO" => "👶", "2º Y 3º CICLO" => "👦", "ANIMALES Y NATURALEZA" => "🌱",
    "VALORES" => "🤝", "EMOCIONES" => "❤️", "IGUALDAD" => "⚖️",
    "INGLÉS" => "🇬🇧", "COLECCIONES" => "📚", "CÓMIC" => "💥", "MÚSICA" => "🎵"
];

$q = isset($_POST['consulta']) ? $conn->real_escape_string($_POST['consulta']) : '';

// Consulta de búsqueda
$sql = "SELECT * FROM catalogo";
if ($q != "") {
    $sql .= " WHERE titulo LIKE '%$q%' OR autor LIKE '%$q%' OR categoria LIKE '%$q%'";
}

$result = $conn->query($sql);
$categorias = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $catNombre = !empty($row['categoria']) ? $row['categoria'] : "Otros";
        
        // Si es la primera vez que vemos esta categoría, la inicializamos
        if (!isset($categorias[$catNombre])) {
            $categorias[$catNombre] = [
                "color" => $row['ubicacion_por_colores'],
                "icono" => isset($iconos[$catNombre]) ? $iconos[$catNombre] : "📗",
                "libros" => []
            ];
        }
        
        // Guardamos el libro
        $categorias[$catNombre]["libros"][] = $row;
    }
}

// --- AQUI EMPIEZA LO QUE SE MUESTRA EN PANTALLA ---
if (empty($categorias)) {
    echo "<div style='text-align:center; padding: 40px; color: #666;'>
            <h3>No encontramos libros con esa búsqueda 😔</h3>
            <p>Intenta con otra palabra o el nombre del autor.</p>
          </div>";
} else {
    foreach($categorias as $nombreCat => $data): 
        // Generamos un ID para mantener el estilo, aunque al buscar el scroll no es prioritario
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
                                    // Tu lógica de imágenes exacta
                                    $imgDB = isset($libro['imagen']) ? $libro['imagen'] : '';
                                    $nombreImagen = ($imgDB == 'default.jpg' || empty($imgDB)) 
                                                    ? 'no_hay_imagen.jgp.jpg' : $imgDB;
                                ?>
                                <img src="img/<?php echo $nombreImagen; ?>" alt="Portada" 
                                     onerror="this.src='img/no_hay_imagen.jgp.jpg';">
                            </div>
                            <div class="book-details">
                                <h3><?php echo $libro['titulo']; ?></h3>
                                <p><?php echo $libro['autor']; ?></p>
                                <span class="isbn">ISBN: <?php echo $libro['codigo_de_barra']; ?></span>
                            </div>
                        </article>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endforeach; 
}
?>