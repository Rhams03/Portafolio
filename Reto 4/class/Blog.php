<?php

// clase que gestiona las noticias del blog
class Blog {

    private $conn;
    private $table = "blog";
    private $titulo;
    private $descripcion;
    private $contenido;
    private $url_icono;
    private $url_extra;
    private $fecha_modificacion;

    // constructor: recibe la conexión y la guarda
    public function __construct($db) {
        $this->conn = $db;
    }

    // ── GETTERS ─────────────────────────────────────────────
    // devuelven el valor de cada propiedad privada

    public function getTitulo() {
        return $this->titulo;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getContenido() {
        return $this->contenido;
    }

    public function getUrlIcono() {
        return $this->url_icono;
    }

    public function getUrlExtra() {
        return $this->url_extra;
    }

    public function getFecha() {
        return $this->fecha_modificacion;
    }

    // ── SETTER ──────────────────────────────────────────────
    // rellena todas las propiedades de una vez antes de insertar o actualizar

    public function rellenarDatos($titulo, $descripcion, $contenido, $url_icono, $url_extra, $fecha_modificacion) {
        $this->titulo             = $titulo;
        $this->descripcion        = $descripcion;
        $this->contenido          = $contenido;
        $this->url_icono          = $url_icono;
        $this->url_extra          = $url_extra;
        $this->fecha_modificacion = $fecha_modificacion;
    }

    // ── INSERTAR ─────────────────────────────────────────────
    // guarda una nueva noticia en la base de datos
    // usamos prepare() y bind_param() en vez de meter los datos directamente
    // en el SQL — esto es más sencillo y más seguro

    public function inserta() {

        // los ? son huecos que luego rellena bind_param con los valores reales
        $sql  = "INSERT INTO {$this->table}
                 (titulo, descripcion, contenido, url_icono, url_extra, fecha_modificacion)
                 VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        // "ssssss" significa que los 6 valores son texto (s = string)
        $stmt->bind_param(
            "ssssss",
            $this->titulo,
            $this->descripcion,
            $this->contenido,
            $this->url_icono,
            $this->url_extra,
            $this->fecha_modificacion
        );

        return $stmt->execute();
    }

    // ── ACTUALIZAR ───────────────────────────────────────────
    // actualiza una noticia existente buscándola por su id

    public function updateAll($id) {

        $sql  = "UPDATE {$this->table}
                 SET titulo = ?, descripcion = ?, contenido = ?,
                     url_icono = ?, url_extra = ?, fecha_modificacion = ?
                 WHERE id = ?";

        $stmt = $this->conn->prepare($sql);

        // el último valor es el id y es un número entero — por eso "i" al final
        $stmt->bind_param(
            "ssssssi",
            $this->titulo,
            $this->descripcion,
            $this->contenido,
            $this->url_icono,
            $this->url_extra,
            $this->fecha_modificacion,
            $id
        );

        return $stmt->execute();
    }

    // ── BORRAR ───────────────────────────────────────────────
    // borra una noticia por su id

    public function DeleteBlog($id) {

        $sql  = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    // ── OBTENER TODAS ────────────────────────────────────────
    // devuelve todas las noticias de la más nueva a la más antigua
    // es static porque no necesita crear un objeto para llamarla

    public static function GetAllBlogs($conn) {

        $sql       = "SELECT * FROM blog ORDER BY id DESC";
        $resultado = $conn->query($sql);

        $datos = [];
        while ($fila = $resultado->fetch_assoc()) {
            $datos[] = $fila;
        }

        return $datos;
    }

    // ── BUSCAR UNA ───────────────────────────────────────────
    // busca una noticia por su id y devuelve un objeto Blog con sus datos
    // si no la encuentra devuelve null

    public static function FindBlog($conn, $id) {

        $sql  = "SELECT * FROM blog WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $resultado = $stmt->get_result();

        // si no hay ninguna fila devolvemos null
        if ($resultado->num_rows == 0) {
            return null;
        }

        // creamos un objeto Blog y lo rellenamos con los datos de la BD
        $fila = $resultado->fetch_assoc();
        $blog = new Blog($conn);
        $blog->rellenarDatos(
            $fila['titulo'],
            $fila['descripcion'],
            $fila['contenido'],
            $fila['url_icono'],
            $fila['url_extra'],
            $fila['fecha_modificacion']
        );

        return $blog;
    }
}
