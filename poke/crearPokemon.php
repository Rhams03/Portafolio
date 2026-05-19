<?php
include_once "pokemon.php";
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Pokémon</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form action="" method="POST">
        <p>
            <label for="nombre">Nombre del Pokémon</label>
            <input type="text" name="nombre" id="nombre" required>
        </p>
        <p>
            <label for="elemento">Elige un elemento</label>
            <select name="elemento" id="elemento">
                <option value="normal">Normal</option>
                <option value="planta">Planta</option>
                <option value="fuego">Fuego</option>
                <option value="agua">Agua</option>
                <option value="electrico">Electrico</option>
                <option value="volador">Volador</option>
                <option value="bicho">Bicho</option>
                <option value="roca">Roca</option>
                <option value="tierra">Tierra</option>
                <option value="acero">Acero</option>
                <option value="psiquico">Psiquico</option>
                <option value="siniestro">Siniestro</option>
                <option value="fantasma">Fantasma</option>
                <option value="veneno">Veneno</option>
                <option value="lucha">Lucha</option>
                <option value="hielo">Hielo</option>
                <option value="dragon">Dragón</option>
                <option value="hada">Hada</option>
            </select>
        </p>
        <p>
            <label for="habilidad">Elige una habilidad</label>
            <select name="habilidad" id="habilidad">
                <option value="atqmas">Ataque+</option>
                <option value="defmas">Defensa+</option>
                <option value="aesmas">Ataque Especial+</option>
                <option value="desmas">Defensa Especial+</option>
                <option value="velmas">Velocidad+</option>
            </select>
        </p>
        <p>
            <label for="nivel">Nivel</label>
            <input type="number" name="nivel" id="nivel" min="1" max="100" placeholder="1 a 100" required>
        </p>
        <p>
            <label for="vida">Vida</label>
            <input type="number" name="vida" id="vida" min="1" max="255" placeholder="1 a 255" required>
        </p>
        <p>
            <label for="ataque">Ataque</label>
            <input type="number" name="ataque" id="ataque" min="1" max="255" placeholder="1 a 255" required>
        </p>
                <p>
            <label for="defensa">Defensa</label>
            <input type="number" name="defensa" id="defensa" min="1" max="255" placeholder="1 a 255" required>
        </p>
                <p>
            <label for="atqspecial">Ataque Especial</label>
            <input type="number" name="atqspecial" id="atqspecial" min="1" max="255" placeholder="1 a 255" required>
        </p>
                <p>
            <label for="defspecial">Defensa Especial</label>
            <input type="number" name="defspecial" id="defspecial" min="1" max="255" placeholder="1 a 255" required>
        </p>
        <p>
            <label for="velocidad">Velocidad</label>
            <input type="number" name="velocidad" id="velocidad" min="1" max="255" placeholder="1 a 255" required>
        </p>
        <p>
            <input type="submit" value="Crear">
        </p>
    </form>
    <?php
    if(isset($_POST['nombre'], $_POST['elemento'], $_POST['habilidad'], $_POST['nivel'], $_POST['vida'], $_POST['ataque'], $_POST['defensa'], $_POST['atqspecial'], $_POST['defspecial'], $_POST['velocidad'])){
        $nombre = $_POST['nombre'];
        $elemento = $_POST['elemento'];
        $habilidad = $_POST['habilidad'];
        $nivel = (int)$_POST['nivel'];
        $vida = (int)$_POST['vida'];
        $ataque = (int)$_POST['ataque'];
        $defensa = (int)$_POST['defensa'];
        $atqspecial = (int)$_POST['atqspecial'];
        $defspecial = (int)$_POST['defspecial'];
        $velocidad = (int)$_POST['velocidad'];

        if($vida < 1 || $vida > 255 || $ataque < 1 || $ataque > 255 || $defensa < 1 || $defensa > 255 || $atqspecial < 1 || $atqspecial > 255 || $defspecial < 1 || $defspecial > 255 || $velocidad < 1 || $velocidad > 255){
            echo "Error: Los valores no concuerdan con los limites: ";
            echo "<p>Vida debe ser entre 1 a 255</p>";
            echo "<p>Ataque debe ser entre 1 a 255</p>";
            echo "<p>Defensa debe ser entre 1 a 255</p>";
            echo "<p>Ataque Especial debe ser entre 1 a 255</p>";
            echo "<p>Defensa Especial debe ser entre 1 a 255</p>";
            echo "<p>Velocidad debe ser entre 1 a 255</p>";
        }else{
            $miPokemon = new Pokemon($nombre, $elemento, $habilidad, $nivel, $vida, $ataque, $defensa, $atqspecial, $defspecial, $velocidad);

            $_SESSION['miPokemon'] = $miPokemon;
        
        echo "<h3>Pokémon Registrado</h3>";
        echo "<p>Nombre: $nombre</p>";
        echo "<p>Elemento: $elemento</p>";
        echo "<p>Habilidad: $habilidad</p>";
        echo "<ul>";
            echo "<li>Nivel: $nivel</li>";
            echo "<li>Vida: $vida</li>";
            echo "<li>Ataque: $ataque</li>";
            echo "<li>Defensa: $defensa</li>";
            echo "<li>Ataque Especial: $atqspecial</li>";
            echo "<li>Defensa Especial: $defspecial</li>";
            echo "<li>Velocidad: $velocidad</li>";
        echo "</ul>";

        }
    }
    
    ?>
    <audio src="sonido/fondomusica.mp3" autoplay loop>
        musica
    </audio>
</script>
</body>
</html>