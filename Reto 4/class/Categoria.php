<?php

// clase que gestiona las categorías y subcategorías
class Categoria {

    private $conn;

    // nombre correcto de la tabla en la base de datos
    private $tabla = "categoria";

    // propiedades del objeto (nombres igual que en la BD)
    public $nombre_categoria;
    public $descripcion;
    public $url_catIcono;
    public $id_madre;
    public $url_subcatIcono;
    public $fecha_actualizacion;

    // recibe la conexión y la guarda
    public function __construct($db) {
        $this->conn = $db;
    }

    // rellena las propiedades antes de insertar o actualizar
    public function rellenarDatos($nombre, $descripcion, $url_icono, $id_madre, $url_subicono, $fecha) {
        $this->nombre_categoria    = $nombre;
        $this->descripcion         = $descripcion;
        $this->url_catIcono        = $url_icono;
        $this->id_madre            = $id_madre;
        $this->url_subcatIcono     = $url_subicono;
        $this->fecha_actualizacion = $fecha;
    }

    // inserta una nueva categoría en la base de datos
    public function inserta() {
        $sql  = "INSERT INTO {$this->tabla}
                 (nombre_categoria, descripcion, url_catIcono, id_madre, url_subcatIcono, fecha_actualizacion)
                 VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        // si la consulta falla (columna mal escrita etc.) lo notificamos
        if (!$stmt) {
            return "Error al preparar: " . $this->conn->error;
        }

        // si id_madre viene vacío lo ponemos como null
        $id_madre_seguro = !empty($this->id_madre) ? (int)$this->id_madre : null;

        $stmt->bind_param("sssiss",
            $this->nombre_categoria,
            $this->descripcion,
            $this->url_catIcono,
            $id_madre_seguro,
            $this->url_subcatIcono,
            $this->fecha_actualizacion
        );

        if ($stmt->execute()) {
            return true;
        } else {
            return "Error al insertar: " . $stmt->error;
        }
    }

    // actualiza los datos de una categoría existente por su id
    public function actualizar($id, $nombre, $descripcion, $url_cat, $url_subcat, $id_madre) {
        $sql  = "UPDATE categoria
                 SET nombre_categoria = ?, descripcion = ?, url_catIcono = ?,
                     url_subcatIcono = ?, id_madre = ?, fecha_actualizacion = ?
                 WHERE id = ?";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return "Error al preparar: " . $this->conn->error;
        }

        $id_madre_seguro = !empty($id_madre) ? (int)$id_madre : null;
        $fecha           = date('Y-m-d');

        $stmt->bind_param("ssssiii",
            $nombre,
            $descripcion,
            $url_cat,
            $url_subcat,
            $id_madre_seguro,
            $fecha,
            $id
        );

        return $stmt->execute() ? true : "Error al actualizar: " . $stmt->error;
    }

    // devuelve una categoría por su id como array
    public function obtenerPorId($id) {
        $sql  = "SELECT * FROM {$this->tabla} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // devuelve TODAS las categorías — usada en categorias.php y en admin
    public static function getDatosIndexcat($conn) {
        $sql = "SELECT id, nombre_categoria, descripcion, url_catIcono, id_madre
                FROM categoria
                ORDER BY id ASC";

        $resultado = $conn->query($sql);
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    // devuelve las subcategorías de una categoría madre
    // se usa en subcategoria.php
    public static function getsubDatos($id_madre, $conn) {
        $sql  = "SELECT id, nombre_categoria, descripcion, url_catIcono, id_madre
                 FROM categoria
                 WHERE id_madre = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_madre);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $datos     = [];

        while ($fila = $resultado->fetch_assoc()) {
            $datos[] = $fila;
        }

        return $datos;
    }

    // devuelve todas las categorías principales (sin madre)
    // se usa en el select del formulario admin para elegir categoría madre
    public static function AllCAT($conn) {
        $sql       = "SELECT id, nombre_categoria FROM categoria WHERE id_madre IS NULL ORDER BY nombre_categoria ASC";
        $resultado = $conn->query($sql);
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    // devuelve todas las subcategorías (las que sí tienen madre)
    public static function AllSubCAT($conn) {
        $sql       = "SELECT c1.*, c2.nombre_categoria as nombre_madre 
                      FROM categoria c1
                      INNER JOIN categoria c2 ON c1.id_madre = c2.id
                      WHERE c1.id_madre IS NOT NULL 
                      ORDER BY c2.nombre_categoria ASC, c1.nombre_categoria ASC";
        $resultado = $conn->query($sql);
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    // devuelve TODAS las categorías con id_madre incluido
    // se usa en editar_categoria.php y modi_cat.php para el select de categoría madre
    // ── CAMBIO: ahora incluye id_madre para poder separar niveles en el select ──
    public static function AllCATforModi($conn) {
        $sql       = "SELECT id, nombre_categoria, id_madre FROM categoria ORDER BY nombre_categoria ASC";
        $resultado = $conn->query($sql);
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    // decide si una categoría tiene hijos y devuelve el enlace correcto
    // si tiene subcategorías va a subcategoria.php, si no a contenido.php
    public static function getEnlace($datos, $conn) {
        $id   = $datos['id'];
        $sql  = "SELECT COUNT(*) AS total FROM categoria WHERE id_madre = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $fila = $stmt->get_result()->fetch_assoc();

        if ($fila['total'] > 0) {
            return "subcategoria.php?id=" . $id;
        } else {
            return "contenido.php?id=" . $id;
        }
    }

    public static function FindCat($conn, $id) {
        try {
            $id = (int) $id;

            $sql = "SELECT * FROM categoria WHERE id = $id";
            $resultado = $conn->query($sql);

            if ($resultado->num_rows == 0) {
                return null;
            }

            $row = $resultado->fetch_assoc();

            $cat = new Categoria($conn);
            $cat->rellenarDatos(
                $row['nombre_categoria'],
                $row['descripcion'],
                $row['url_catIcono'],
                $row['id_madre'],
                $row['url_subcatIcono'] ?? null,
                $row['fecha_actualizacion']
            );

            return $cat;

        } catch (Exception $e) {
            return null;
        }
    }

    // ── ELIMINAR CATEGORÍA CON SUS SUBCATEGORÍAS Y BLOQUES ──
    public static function deleteWithSubcategories($conn, $id_cat) {
        try {
            $id_cat = (int) $id_cat;

            // 1. Encontrar todas las subcategorías
            $sql_subcats = "SELECT id FROM categoria WHERE id_madre = ?";
            $stmt = $conn->prepare($sql_subcats);
            $stmt->bind_param("i", $id_cat);
            $stmt->execute();
            $resultado = $stmt->get_result();

            $ids_subcategorias = [];
            while ($fila = $resultado->fetch_assoc()) {
                $ids_subcategorias[] = $fila['id'];
            }

            // 2. Eliminar bloques asociados a la categoría principal
            $sql_delete_bloques_main = "DELETE FROM bloque WHERE id_categoria = ?";
            $stmt = $conn->prepare($sql_delete_bloques_main);
            $stmt->bind_param("i", $id_cat);
            $stmt->execute();

            // 3. Eliminar bloques de las subcategorías
            if (!empty($ids_subcategorias)) {
                $ids_str = implode(',', $ids_subcategorias);
                $sql_delete_bloques_subs = "DELETE FROM bloque WHERE id_categoria IN ($ids_str)";
                $conn->query($sql_delete_bloques_subs);

                // 4. Eliminar las subcategorías
                $sql_delete_subcats = "DELETE FROM categoria WHERE id_madre = ?";
                $stmt = $conn->prepare($sql_delete_subcats);
                $stmt->bind_param("i", $id_cat);
                $stmt->execute();
            }

            // 5. Eliminar la categoría principal
            $sql_delete_cat = "DELETE FROM categoria WHERE id = ?";
            $stmt = $conn->prepare($sql_delete_cat);
            $stmt->bind_param("i", $id_cat);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }

        } catch (Exception $e) {
            return false;
        }
    }
}
