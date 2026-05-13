<?php
class Bloque{
    private $conn;
    private $table = "bloque";
    private $titulo;
    private $descripcion;
    private $id_categoria;
    private $contenido;
    private $url_oficial;

    public function __construct($db){
        $this->conn = $db;
    }

    public function rellenarDatos($titulo, $descripcion, $id_categoria, $contenido, $url_oficial = null){
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->id_categoria = $id_categoria;
        $this->contenido = $contenido;
        $this->url_oficial = $url_oficial;
    }

    public function inserta(){
        try{
            $sql = "INSERT INTO {$this->table}(titulo, descripcion, id_categoria, contenido, url_oficial) VALUES(?,?,?,?,?)";
            $stmt = $this->conn->prepare($sql);
            if(!$stmt){
                throw new Exception("Error al preparar insert: " . $this->conn->error);
            }
            $stmt->bind_param("ssiss", $this->titulo, $this->descripcion, $this->id_categoria, $this->contenido, $this->url_oficial);
            if(!$stmt->execute()){
                throw new Exception("Error al ejecutar insert: " . $stmt->error);
            }
            return true;
        }catch(Exception $e){
            echo "<script>alert('".$e->getMessage()."')</script>";
            return false;
        }
    }

    public function updateBloque($id, $titulo = null, $descripcion = null, $contenido = null, $url_oficial = null){
        try{
            $sql = "UPDATE {$this->table} SET titulo = ?, descripcion = ?, contenido = ?, url_oficial = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            if(!$stmt){
                throw new Exception("Error al preparar update: " . $this->conn->error);
            }
            $stmt->bind_param("ssssi", $titulo, $descripcion, $contenido, $url_oficial, $id);
            return $stmt->execute();
        }catch(Exception $e){
            echo "<script>alert('".$e->getMessage()."')</script>";
            return false;
        }
    }

    public static function FindblocDeModi($conn, $id){
        try{
            $sql = "SELECT * FROM bloque WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $resultado = $stmt->get_result();
            return $resultado->fetch_assoc();
        }catch(Exception $e){
            return null;
        }
    }

    public static function FindblocDeCat($conn, $id_cat){
        try{
            $sql = "SELECT * FROM bloque WHERE id_categoria = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_cat);
            $stmt->execute();
            $resultado = $stmt->get_result();
            return $resultado->fetch_assoc();
        }catch(Exception $e){
            return null;
        }
    }

    public static function deleteBloquePorId($conn, $id){
        $sql = "DELETE FROM bloque WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
