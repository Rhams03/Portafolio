<?php
class Faq {
    private $conn;
    private $table = "faq";
    

    private $id_categoria;
    private $contenido; 
    private $fecha;
    private $respuesta;

    public function __construct($db){
        $this->conn = $db;
    }


    public function rellenarDatos($id_categoria, $contenido, $fecha, $respuesta){
        $this->id_categoria = $id_categoria;
        $this->contenido = $contenido;
        $this->fecha = $fecha;
        $this->respuesta = $respuesta;
    }
    

    public function inserta(){
        try{ 
            $sql = "INSERT INTO {$this->table} (id_categoria, contenido, fecha, respuesta) VALUES(?,?,?,?)";
            $stmt = $this->conn->prepare($sql);

            $stmt->bind_param("isss", $this->id_categoria, $this->contenido, $this->fecha, $this->respuesta);
            if(!$stmt->execute()){
                throw new Exception("Inserta FAQ failed: " . $stmt->error);
            }   
        }catch(Exception $e){
            echo "<script>alert('".$sql.",".$e->getMessage(). "')</script>";
        }
    }


    public function setId_Categoria($id_categoria, $id){
        try{
            $sql = "UPDATE {$this->table} SET id_categoria = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $id_categoria, $id);
            if(!$stmt->execute()){
                throw new Exception("Update id_categoria failed: " . $stmt->error);
            }
        }catch(Exception $e){
            echo "<script>alert('".$sql.",".$e->getMessage(). "')</script>";
        }
    }

    public function setContenido($contenido, $id){
        try{
            $sql = "UPDATE {$this->table} SET contenido = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("si", $contenido, $id);
            if(!$stmt->execute()){
                throw new Exception("Update contenido failed: " . $stmt->error);
            }
        }catch(Exception $e){
            echo "<script>alert('".$sql.",".$e->getMessage(). "')</script>";
        }
    }

    public function setFecha($fecha, $id){
        try{
            $sql = "UPDATE {$this->table} SET fecha = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("si", $fecha, $id);
            if(!$stmt->execute()){
                throw new Exception("Update fecha failed: " . $stmt->error);
            }
        }catch(Exception $e){
            echo "<script>alert('".$sql.",".$e->getMessage(). "')</script>";
        }
    }

    public function setRespuesta($respuesta, $id){
        try{
            $sql = "UPDATE {$this->table} SET respuesta = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("si", $respuesta, $id);
            if(!$stmt->execute()){
                throw new Exception("Update respuesta failed: " . $stmt->error);
            }
        }catch(Exception $e){
            echo "<script>alert('".$sql.",".$e->getMessage(). "')</script>";
        }
    }


    public function updateAll($id){
        try{
            $sql = "UPDATE {$this->table} SET id_categoria=?, contenido=?, fecha=?, respuesta=? WHERE id=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("isssi", $this->id_categoria, $this->contenido, $this->fecha, $this->respuesta, $id);
            if(!$stmt->execute()){
                throw new Exception("Update all failed: " . $stmt->error);
            }
        }catch(Exception $e){
            echo "<script>alert('".$sql.",".$e->getMessage(). "')</script>";
        }
    }


    public function DeleteFaq($id){
        try{
            $sql="DELETE FROM {$this->table} WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            if(!$stmt->execute()){
                throw new Exception("Error, no ha podido eliminar esta FAQ: " . $stmt->error);
            }
        }catch(Exception $e){
            echo "<script>alert('" . $sql . "," . $e->getMessage() . "')</script>";
        }
    }


    public static function FindByCategoriaId($conn, $id_categoria){
        try{
            $sql = "SELECT id, id_categoria, contenido, fecha, respuesta 
                    FROM faq 
                    WHERE id_categoria = ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_categoria);
            
            if(!$stmt->execute()){
                throw new Exception("Datos mostrar failed: " . $stmt->error);
            }
            
            $resultado = $stmt->get_result();
            $datos = [];
            
            while($row = $resultado->fetch_assoc()){
                $datos[] = $row;
            }
            
            return $datos;
            
        }catch(Exception $e){
            echo "<script>alert('" . $sql . "," . $e->getMessage() . "')</script>";
            return [];
        }
    }


    public static function FindFaq($conn, $id){
        try{
            $sql = "SELECT id_categoria, contenido, fecha, respuesta
                    FROM faq
                    WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            if(!$stmt->execute()){
                throw new Exception("Error, no ha podido coger datos de base de datos: " . $stmt->error);
            }
            $resultado = $stmt->get_result();
            if($resultado->num_rows == 0){
                return null;
            } else {
                $row = $resultado->fetch_assoc();
                $faq = new Faq($conn);
                $faq->rellenarDatos($row['id_categoria'], $row['contenido'], $row['fecha'], $row['respuesta']);
                return $faq;
            }
        }catch(Exception $e){
            echo "<script>alert('".$sql.",".$e->getMessage(). "')</script>";
        }
    }
}
?>