<?php
class Pokemon {
    private $nombre;
    private $elemento;
    private $habilidad;
    private $nivel;
    private $vida;
    private $atq;
    private $def;
    private $aes;
    private $des;
    private $vel;

    public function __construct($nombre, $elemento, $habilidad, $nivel, $vida, $atq, $def, $aes, $des, $vel){

        $this->nombre = $nombre;
        $this->elemento = $elemento;
        $this->habilidad = $habilidad;
        $this->nivel = $nivel;
        $this->vida = $vida;
        $this->atq = $atq;
        $this->def = $def;
        $this->aes = $aes;
        $this->des = $des;
        $this->vel = $vel;
    }

    public function nomPkmn():string{
       return $this->nombre;
    }

    public function ElemPkmn():string{
        return $this->elemento;
    }

    public function HabPkmn():string{
        return $this->habilidad;
    }

    public function Nivel():int{
        return $this->nivel;
    }

    public function Vida():int{
        return $this->vida;
    }

    public function Ataque():int{
        return $this->atq;
    }

    public function Defensa():int{
        return $this->def;
    }
    
    public function atqEspecial():int{
        return $this->aes;
    }

    public function defEspecial():int{
        return $this->des;
    }

    public function Velocidad():int{
        return $this->vel;
    }

    public function MostrarInfo():string{
        return "<p>Nombre: {$this->nombre} Elemento: {$this->elemento} Habilidad: {$this->habilidad} Vida: {$this->vida} Ataque: {$this->atq} Defensa: {$this->def} Ataque Especial: {$this->aes} Defensa Especial: {$this->des} Velocidad: {$this->vel}</p>";
    }

}
?>