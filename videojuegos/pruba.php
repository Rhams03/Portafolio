<?php
Class Perro { // Esta es la plantilla de la clase perro
    // public = cualquier citio
    // private = solo desde la propia clase
    // protected = solo desde la propia clase(padre) y las clases hijas
    private $nombre; 
                        // Esta son las propiedades + su encapsulación(lo que controla) de las caracteristicas del objeto
    private $color;

    public function __Construct($nombre, $color){ // Esto es el constructor + lo que hace inicializar(ejecutar) las propiedades de la clase
        $this->nombre = $nombre; // Esto almacena el valor de la propiedad nombre del objeto
        $this->color = $color;
    }

    // ejemplo de static
    // public static function getNombre(){
    //  return "Beagle";
    //}

    public function Nombre(){ // Esto es un metodo que realiza una accion 
        echo "El nombre del perro es: " . $this->nombre;
    }

    public function Color(){
        echo "El color del perro es: " . $this->color;
    }

    public function getNombre(){ // Esto es un metodo para cuando usemos encapsulación private
        return $this->nombre = $nombre;
    }

    public function setNombre(){
        return $this->nombre = $nombre;
    }

    public function getColor(){
        return $this->color = $color;
    }

    public function setColor(){
        return $this->color = $color;
    }
}
// Esto es una instancia(Creacion) de un objeto en base a una clase
$beagle = new Perro("Juan", "blanco"); 
echo $beagle->getNombre(); // como las propiedades son privadas hay que usar los getters y setters
echo $beagle->getColor();

// ejemplo si la encapsulación fuera public
$beagle = new Perro("Juan", "blanco");
$beagle->Nombre();
$beagle->Color();

//ejemplo si el metodo es estatico(se puede llamar si construct ya que pertenece a la clase)
$beagle = Perro::getNombre();
echo $beagle;
?>