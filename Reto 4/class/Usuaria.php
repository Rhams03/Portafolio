<?php

class Usuaria {
    private $conn;
    private $id;
    private $nombre;
    private $correo;
    private $password;
    private $id_rol;

    // Constructor recibe la conexión para poder usarla en los métodos de objeto
    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para rellenar los datos del objeto (usado en gestión de usuarias)
    public function rellenarDatos($nombre, $correo, $password, $id_rol) {
        $this->nombre   = $nombre;
        $this->correo   = $correo;
        $this->password = $password;
        $this->id_rol   = $id_rol;
    }

    /**
     * Busca una usuaria por su nombre.
     * Adaptado a la BD: columnas 'id', 'nombre', 'correo', 'id_rol' y 'url_foto'.
     */
    public static function FindByNombre($conn, $nombre) {
        // Ahora incluimos la columna url_foto en la consulta
        $sql  = "SELECT id, nombre, correo, id_rol, url_foto FROM usuaria WHERE nombre = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($fila = $res->fetch_assoc()) {
            // Mantenemos el alias url_foto para no romper las vistas que ya lo usan
            $fila['url_foto'] = $fila['url_foto'] ?? ""; 
            return $fila;
        }
        return null;
    }

    /**
     * Busca por ID. Alias de FindById para compatibilidad con editar_usuaria.php
     */
    public static function FindUsuaria($conn, $id) {
        return self::FindById($conn, $id);
    }

    /**
     * Busca por ID incluyendo la foto.
     */
    public static function FindById($conn, $id) {
        $sql  = "SELECT id, nombre, correo, id_rol, url_foto FROM usuaria WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($fila = $res->fetch_assoc()) {
            $fila['url_foto'] = $fila['url_foto'] ?? "";
            return $fila;
        }
        return null;
    }

    /**
     * Obtiene todas las usuarias con su nombre de rol.
     */
    public static function GetAllUsuarias($conn) {
        $sql = "SELECT u.*, r.tipo 
                FROM usuaria u 
                JOIN rol r ON u.id_rol = r.id";
        $res = $conn->query($sql);
        $usuarias = [];
        while ($fila = $res->fetch_assoc()) {
            $fila['url_foto'] = $fila['url_foto'] ?? "";
            $usuarias[] = $fila;
        }
        return $usuarias;
    }

    /**
     * Actualiza los datos básicos del perfil (estático).
     */
    public static function UpdatePerfil($conn, $id, $nombre, $password = null) {
        if (!empty($password)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql  = "UPDATE usuaria SET nombre = ?, password = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $nombre, $hash, $id);
        } else {
            $sql  = "UPDATE usuaria SET nombre = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $nombre, $id);
        }
        return $stmt->execute();
    }

    /**
     * Actualiza todos los datos (método de objeto).
     * Alias para compatibilidad con editar_usuaria.php
     */
    public function updateAll($id) {
        if (!empty($this->password)) {
            $hash = password_hash($this->password, PASSWORD_DEFAULT);
            $sql  = "UPDATE usuaria SET nombre = ?, correo = ?, password = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sssi", $this->nombre, $this->correo, $hash, $id);
        } else {
            $sql  = "UPDATE usuaria SET nombre = ?, correo = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssi", $this->nombre, $this->correo, $id);
        }
        return $stmt->execute();
    }

    /**
     * Inserta una nueva usuaria (método de objeto).
     */
    public function inserta() {
        $hash = password_hash($this->password, PASSWORD_DEFAULT);
        $sql  = "INSERT INTO usuaria (nombre, correo, password, id_rol) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssi", $this->nombre, $this->correo, $hash, $this->id_rol);
        return $stmt->execute();
    }

    /**
     * Elimina una usuaria (método de objeto).
     */
    public function DeleteUsuaria($id) {
        $sql  = "DELETE FROM usuaria WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
     * Actualiza la foto de perfil (estático).
     * Ahora sí guarda en la columna url_foto.
     */
    public static function updateFoto($conn, $id, $url_foto) {
        $sql  = "UPDATE usuaria SET url_foto = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $url_foto, $id);
        return $stmt->execute();
    }
}
?>
