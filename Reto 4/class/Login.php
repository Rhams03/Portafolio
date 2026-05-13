<?php
class Login {

    // $conn es la conexión a la base de datos, privada para que solo la use esta clase
    private $conn;

    // nombres de las tablas que vamos a consultar
    private $usertable = "usuaria";
    private $roltable  = "rol";

    // constructor: recibe la conexión y la guarda
    public function __construct($db) {
        $this->conn = $db;
    }

    // comprueba si el usuario y contraseña son correctos
    // devuelve los datos de la usuaria si todo está bien, o false si algo falla
    public function login($username, $password) {

        // En la BD actual las columnas son 'id', 'nombre', 'password' e 'id_rol'
        // Seleccionamos también u.id para poder guardarlo en sesión
        $sql = "SELECT u.id, u.nombre, u.password, r.id AS rol_id, r.tipo AS rol_nombre
                FROM {$this->usertable} u
                INNER JOIN {$this->roltable} r ON u.id_rol = r.id
                WHERE u.nombre = ?";

        // preparamos la consulta con el ? para evitar inyección SQL
        $stmt = $this->conn->prepare($sql);

        // enlazamos el nombre de usuaria con el ?
        $stmt->bind_param("s", $username);

        // ejecutamos la búsqueda en la base de datos
        $stmt->execute();

        // recogemos el resultado
        $result = $stmt->get_result();

        // si existe exactamente una usuaria con ese nombre
        if ($result->num_rows === 1) {

            // convertimos la fila a array: ['id' => 3, 'nombre' => 'admin', ...]
            $row = $result->fetch_assoc();

            // Primero intentamos validar como contraseña moderna con hash.
            // Si la base de datos todavía tiene contraseñas antiguas en texto plano,
            // también permitimos la comparación directa para no romper el acceso.
            if (password_verify($password, $row['password']) || $password === $row['password']) {
                // devolvemos todos los datos para guardarlos en sesión
                return $row;
            }
        }

        // si el nombre no existe o la contraseña es incorrecta devolvemos false
        return false;
    }
}
?>
