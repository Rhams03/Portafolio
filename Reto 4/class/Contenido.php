<?php
class Contenido {
    private $conn;
    private $table = "contenido_bloque";
    

    private $id_bloque;
    private $url_externa;
    private $url_icono;
    private $url_extra;

    public function __construct($db){
        $this->conn = $db;
    }

    public function rellenarDatos($id_bloque, $url_externa, $url_icono, $url_extra){
        $this->id_bloque = $id_bloque;
        $this->url_externa = $url_externa;
        $this->url_icono = $url_icono;
        $this->url_extra = $url_extra;
    }
    

    public function inserta(){
        try{ 
            $sql = "INSERT INTO {$this->table} (id_bloque, url_externa, url_icono, url_extra) VALUES(?,?,?,?)";
            $stmt = $this->conn->prepare($sql);
            // i = integer, s = string
            $stmt->bind_param("isss", $this->id_bloque, $this->url_externa, $this->url_icono, $this->url_extra);
            if(!$stmt->execute()){
                throw new Exception("Inserta contenido failed: " . $stmt->error);
            }   
        }catch(Exception $e){
            echo "<script>alert('".$sql.",".$e->getMessage(). "')</script>";
        }
    }


    public function setId_Bloque($id_bloque, $id){
        try{
            $sql = "UPDATE {$this->table} SET id_bloque = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $id_bloque, $id);
            if(!$stmt->execute()){
                throw new Exception("Update id_bloque failed: " . $stmt->error);
            }
        }catch(Exception $e){
            echo "<script>alert('".$sql.",".$e->getMessage(). "')</script>";
        }
    }

    public function setUrl_Externa($url_externa, $id){
        try{
            $sql = "UPDATE {$this->table} SET url_externa = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("si", $url_externa, $id);
            if(!$stmt->execute()){
                throw new Exception("Update url_externa failed: " . $stmt->error);
            }
        }catch(Exception $e){
            echo "<script>alert('".$sql.",".$e->getMessage(). "')</script>";
        }
    }

    public function setUrl_Icono($url_icono, $id){
        try{
            $sql = "UPDATE {$this->table} SET url_icono = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("si", $url_icono, $id);
            if(!$stmt->execute()){
                throw new Exception("Update url_icono failed: " . $stmt->error);
            }
        }catch(Exception $e){
            echo "<script>alert('".$sql.",".$e->getMessage(). "')</script>";
        }
    }

    public function setUrl_Extra($url_extra, $id){
        try{
            $sql = "UPDATE {$this->table} SET url_extra = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("si", $url_extra, $id);
            if(!$stmt->execute()){
                throw new Exception("Update url_extra failed: " . $stmt->error);
            }
        }catch(Exception $e){
            echo "<script>alert('".$sql.",".$e->getMessage(). "')</script>";
        }
    }


    public function updateAll($id){
        try{
            $sql = "UPDATE {$this->table} SET id_bloque=?, url_externa=?, url_icono=?, url_extra=? WHERE id=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("isssi", $this->id_bloque, $this->url_externa, $this->url_icono, $this->url_extra, $id);
            if(!$stmt->execute()){
                throw new Exception("Update all failed: " . $stmt->error);
            }
        }catch(Exception $e){
            echo "<script>alert('".$sql.",".$e->getMessage(). "')</script>";
        }
    }

    public function DeleteContenido($id){
        try{
            $sql="DELETE FROM {$this->table} WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            if(!$stmt->execute()){
                throw new Exception("Error, no ha podido eliminar este dato: " . $stmt->error);
            }
        }catch(Exception $e){
            echo "<script>alert('" . $sql . "," . $e->getMessage() . "')</script>";
        }
    }


    public static function FindByBloqueId($conn, $id_bloque){
        try{
            $sql = "SELECT id, id_bloque, url_externa, url_icono, url_extra 
                    FROM contenido_bloque 
                    WHERE id_bloque = ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_bloque);
            
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


    public static function FindContenido($conn, $id){
        try{
            $sql = "SELECT id_bloque, url_externa, url_icono, url_extra
                    FROM contenido_bloque
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
                $contenido = new Contenido($conn);
                $contenido->rellenarDatos($row['id_bloque'], $row['url_externa'], $row['url_icono'], $row['url_extra']);
                return $contenido;
            }
        }catch(Exception $e){
            echo "<script>alert('".$sql.",".$e->getMessage(). "')</script>";
        }
    }
}
?>