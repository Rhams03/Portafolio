<?php
// ... comentarios ...
include 'admin_api.php';

// ==============================================================================
// 1. BACKEND: RESPUESTAS AJAX (Buscador en tiempo real)
// ==============================================================================
if (isset($_GET['ajax_search'])) {
    // Limpiamos cualquier salida anterior para que sea JSON puro
    ob_clean();
    
    $buscar = isset($_GET['buscar']) ? $conn->real_escape_string($_GET['buscar']) : '';
    
    $sql = "SELECT * FROM catalogo WHERE 1=1";
    
    if ($buscar != "") {
        $sql .= " AND (titulo LIKE '%$buscar%' OR autor LIKE '%$buscar%' OR categoria LIKE '%$buscar%' OR codigo_de_barra LIKE '%$buscar%')";
    }
    
    $sql .= " ORDER BY id DESC LIMIT 50";
    
    $resultado = $conn->query($sql);
    $libros = [];
    
    while($row = $resultado->fetch_assoc()) {
        $libros[] = $row;
    }
    
    echo json_encode($libros);
    exit; // Importante: Detener el script aquí para no enviar HTML después
}

// ==============================================================================
// 2. BACKEND: PROCESAR ELIMINACIÓN
// ==============================================================================
if (isset($_GET['eliminar_libro'])) {
    $id = intval($_GET['eliminar_libro']);
    $conn->query("DELETE FROM catalogo WHERE id = $id");
    // Redireccionamos para limpiar la URL
    echo "<script>window.location.href='admin.php?modulo=catalogo';</script>";
    exit;
}

// ==============================================================================
// 3. BACKEND: PROCESAR GUARDADO/EDICIÓN (POST)
// ==============================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_libro'])) {
    $id = $_POST['id_libro']; // Si tiene ID es editar, si no es nuevo
    $titulo = $conn->real_escape_string($_POST['titulo']);
    $autor = $conn->real_escape_string($_POST['autor']);
    $isbn = $conn->real_escape_string($_POST['isbn']);
    $categoria = $conn->real_escape_string($_POST['categoria']);
    $estado = intval($_POST['estado']);
    $disponible = isset($_POST['disponible']) ? 1 : 0;
    
    // Subida de Imagen (Opcional)
    $imagen = $_POST['imagen_actual']; // Por defecto mantenemos la que hay
    if (isset($_FILES['imagen_portada']) && $_FILES['imagen_portada']['error'] === 0) {
        $nombre_archivo = time() . "_" . $_FILES['imagen_portada']['name'];
        move_uploaded_file($_FILES['imagen_portada']['tmp_name'], "img/" . $nombre_archivo);
        $imagen = $nombre_archivo;
    }

    if ($id == "") {
        // INSERTAR NUEVO
        $sql = "INSERT INTO catalogo (titulo, autor, codigo_de_barra, categoria, estado, disponible, imagen) 
                VALUES ('$titulo', '$autor', '$isbn', '$categoria', '$estado', '$disponible', '$imagen')";
    } else {
        // ACTUALIZAR EXISTENTE
        $sql = "UPDATE catalogo SET 
                titulo='$titulo', autor='$autor', codigo_de_barra='$isbn', 
                categoria='$categoria', estado='$estado', disponible='$disponible', imagen='$imagen' 
                WHERE id='$id'";
    }

    $conn->query($sql);
    echo "<script>window.location.href='admin.php?modulo=catalogo';</script>";
    exit;
}
?>

<div class="modulo-catalogo">
    
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2><i class="fas fa-book"></i> Gestión del Catálogo</h2>
        <button class="btn-save" onclick="abrirModalLibro()">+ Añadir Libro</button>
    </div>

    <div class="search-box" style="margin-bottom: 20px; position: relative;">
        <i class="fas fa-search" style="position: absolute; left: 15px; top: 12px; color: #aaa;"></i>
        <input type="text" id="buscadorLibros" placeholder="Escribe título, autor o ISBN..." 
               style="width: 100%; padding: 10px 10px 10px 40px; border-radius: 20px; border: 1px solid #ddd; outline: none;"
               autocomplete="off">
    </div>

    <div style="background: white; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); overflow: hidden;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Portada</th>
                    <th>Título / Autor</th>
                    <th>Categoría</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-libros-body">
                </tbody>
        </table>
    </div>
</div>

<div id="modal-libro" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="cerrarModalLibro()">&times;</span>
        <h2 id="modal-titulo">Datos del Libro</h2>
        
        <form action="" method="POST" enctype="multipart/form-data" class="form-modal">
            <input type="hidden" name="id_libro" id="input_id">
            <input type="hidden" name="guardar_libro" value="1">
            <input type="hidden" name="imagen_actual" id="input_imagen_actual">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <label>Título:</label>
                    <input type="text" name="titulo" id="input_titulo" required>
                </div>
                <div>
                    <label>Autor:</label>
                    <input type="text" name="autor" id="input_autor" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top:10px;">
                <div>
                    <label>ISBN / Código:</label>
                    <input type="text" name="isbn" id="input_isbn">
                </div>
                <div>
                    <label>Categoría:</label>
                    <select name="categoria" id="input_categoria">
                        <option>INFANTIL Y 1º CICLO</option>
                        <option>2º Y 3º CICLO</option>
                        <option>ANIMALES Y NATURALEZA</option>
                        <option>VALORES</option>
                        <option>INGLÉS</option>
                        <option>CÓMIC</option>
                        <option>EMOCIONES</option>
                        <option>OTROS</option>
                    </select>
                </div>
            </div>

            <div style="margin-top:10px;">
                <label>Estado (Cantidad):</label>
                <input type="number" name="estado" id="input_estado" value="1" min="0" style="width: 80px;">
                
                <label style="margin-left: 20px;">
                    <input type="checkbox" name="disponible" id="input_disponible" value="1" checked> Disponible para préstamo
                </label>
            </div>

            <div style="margin-top:10px;">
                <label>Portada:</label>
                <input type="file" name="imagen_portada" accept="image/*">
                <p style="font-size: 0.8rem; color: #777;">Deja en blanco para mantener la actual.</p>
            </div>

            <button type="submit" class="btn-save" style="margin-top: 20px;">Guardar Cambios</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cargar libros al iniciar
    buscarLibros('');

    // Evento de escritura en el buscador
    const inputBuscar = document.getElementById('buscadorLibros');
    inputBuscar.addEventListener('keyup', function() {
        buscarLibros(this.value);
    });
});

// FUNCIÓN DE BÚSQUEDA AJAX
function buscarLibros(query) {
    const tbody = document.getElementById('tabla-libros-body');
    
    // Llamada al mismo archivo pero con el parámetro ajax_search
    fetch(`admin.php?modulo=catalogo&ajax_search=1&buscar=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            let html = '';
            
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; padding:20px;">No se encontraron libros.</td></tr>';
                return;
            }

            data.forEach(libro => {
                // Definir estado
                let estadoBadge = libro.disponible == 1 
                    ? '<span class="tag tag-green">Disponible</span>' 
                    : '<span class="tag tag-red">No Disponible</span>';
                
                // Definir imagen (manejo de error básico)
                let imagenSrc = libro.imagen ? `img/${libro.imagen}` : 'img/no_hay_imagen.jgp.jpg';

                // Escapar comillas para evitar errores en el JSON del botón editar
                let jsonLibro = JSON.stringify(libro).replace(/'/g, "&apos;").replace(/"/g, "&quot;");

                html += `
                    <tr>
                        <td>
                            <img src="${imagenSrc}" style="width: 40px; height: 50px; object-fit: cover; border-radius: 4px;" onerror="this.src='img/no_hay_imagen.jgp.jpg'">
                        </td>
                        <td>
                            <strong>${libro.titulo}</strong><br>
                            <small style="color:#666;">${libro.autor}</small>
                        </td>
                        <td><span class="tag tag-blue">${libro.categoria}</span></td>
                        <td>${estadoBadge}</td>
                        <td>
                            <button class="btn-icon edit" onclick="editarLibro('${jsonLibro}')" title="Editar">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            
                            <a href="admin.php?modulo=catalogo&eliminar_libro=${libro.id}" 
                               class="btn-icon delete" 
                               onclick="return confirm('¿Estás seguro de borrar: ${libro.titulo}?');" 
                               title="Borrar">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                `;
            });
            
            tbody.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            //tbody.innerHTML = '<tr><td colspan="5">Error cargando datos.</td></tr>';
        });
}

// FUNCIONES DEL MODAL
function abrirModalLibro() {
    // Resetear formulario para libro nuevo
    document.getElementById('input_id').value = "";
    document.getElementById('input_titulo').value = "";
    document.getElementById('input_autor').value = "";
    document.getElementById('input_isbn').value = "";
    document.getElementById('input_imagen_actual').value = "";
    document.getElementById('modal-titulo').innerText = "Añadir Nuevo Libro";
    
    document.getElementById('modal-libro').style.display = 'flex';
}

function editarLibro(jsonString) {
    // Parsear datos del libro
    // NOTA: jsonString viene escapado desde la función buscarLibros
    let libro = JSON.parse(jsonString.replace(/&quot;/g, '"').replace(/&apos;/g, "'"));

    document.getElementById('input_id').value = libro.id;
    document.getElementById('input_titulo').value = libro.titulo;
    document.getElementById('input_autor').value = libro.autor;
    document.getElementById('input_isbn').value = libro.codigo_de_barra;
    document.getElementById('input_categoria').value = libro.categoria;
    document.getElementById('input_estado').value = libro.estado;
    document.getElementById('input_imagen_actual').value = libro.imagen;
    
    document.getElementById('input_disponible').checked = (libro.disponible == 1);
    
    document.getElementById('modal-titulo').innerText = "Editar Libro";
    document.getElementById('modal-libro').style.display = 'flex';
}

function cerrarModalLibro() {
    document.getElementById('modal-libro').style.display = 'none';
}
</script>