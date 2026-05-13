<?php

class Database {
    private $hostname;
    private $username;
    private $password;
    private $database;

    function __construct(){
        $this->hostname = "sql206.infinityfree.com";
        $this->username = "if0_41361648";
        $this->password = "8Z1GyK3xEtm4";
        $this->database = "if0_41361648_medicos_mundo";
    }

    public function connect() {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            $conn = new mysqli($this->hostname, $this->username, $this->password, $this->database);
            mysqli_set_charset($conn, "utf8mb4");

            return $conn;
        } catch (mysqli_sql_exception $e) {
            echo "<script> alert(\"Error en la conexión de BD: " . $e->getMessage() . "\")</script>";
            die();
        }
    }
}
?>
