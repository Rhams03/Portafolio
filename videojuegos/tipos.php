<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tipos Videojuegos</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Esto carga una hoja de estilo desde el internet -->
</head>
<body>
    <header>
        <h1 class="producto">Producto</h1>
        
        <nav>
            <h2>Menú</h2>
            <div class="menu-links">
                <a href="videojuego.php">Añadir Videojuego</a>
                <a href="dlc.php">Añadir DLC</a>
                <a href="tipos.php">Lista de Videojuegos</a>
            </div>
        </nav>
    </header>
    
<main>
    <h1>Lista de Videojuegos</h1>

    <div class="contenedor-catalogo">
    <section class="lista">
        <table>
            <th class="th1">Nombre</th>   
            <th class="th2">Genero</th>
            <th class="th3">Año Desarrollo</th>
            <tr>
                <td onclick="mostrarDescripcion('mhr')">Monster Hunter: Rise</td>
                <td>Action JRPG</td>
                <td>2019</td>
            </tr>
            <tr>
                <td onclick="mostrarDescripcion('pokemon')">Pokémon BW2</td>
                <td>JRPG</td>
                <td>2012</td>
            </tr>
            <tr>
                <td onclick="mostrarDescripcion('godeater')">God Eater 3</td>
                <td>JRPG</td>
                <td>2018</td>
            </tr>
            <tr>
                <td onclick="mostrarDescripcion('genshin')">Genshin Impact</td>
                <td>Action JRPG</td>
                <td>2020</td>
            </tr>
        </table>

    </section>
            <div id="descripcion">
                <h2>Descripción</h2>
                <p>
                    Haz clic en un videojuego para ver su descripción
                </p>
            </div>
            
</main>
    <footer>
        <p>
            <strong>Creador de Juegos y DLCs</strong>
        </p>
        <p>
            <a href="https://x.com/Rhams93180821" target="_blank"><i class="fa-brands fa-twitter"></i>Twitter</a> <!-- <i> es una etiqueta de icono -->
        </p>
        <p>
            <a href="https://github.com/Rhams03/Portafolio.git" target="_blank"><i class="fa-brands fa-github"></i>Mi GitHub</a>
        </p>
    </footer>
    <script>
        //const = Es una variable cuyo valor no puede cambiarse despues
        const descripciones = { // Esto es un objeto que almacena las descripciones de todos los videojuegos
            // Esto es una descripción asociada al videojuego Monster Hunter: Rise
            mhr: ` 
                Monster Hunter: Rise es un juego que va de cazar monstruos más grandes que tu,
                donde con los materiales que obtienes de las cazas podras crear armaduras y armas
                para poder fortalecerte.
            `,
            // Esto es una descripción asociada al videojuego Pokémon BW2
            pokemon: `
                Pokémon Black and White 2 son la secuela directa de Pokémon Blanco y Negro, estos
                juegos van de capturar criaturas, fortalecerlas, coleccionarlas y luchar con ellas.
            `,
            // Esto es una descripción asociada al videojuego God Eater 3
            godeater: `
                God Eater 3 es la tercera entrega de la saga God Eater, y va de matar Aragamis
                (un poco parecido a Monster Hunter) utilizando un arma intercambiable llamada God Arc.
            `,
            // Esto es una descripción asociada al videojuego Genshin Impact
            genshin: `
                Genshin Impact es un gacha de aventura donde te embarcas en un mundo gigante con los 
                personajes que vas consiguiendo a lo largo de la aventura.
            `
        };

        function mostrarDescripcion(juego){ //Esto es una función que muestra la descripción del videojuego seleccionado
            document.getElementById("descripcion").innerText = descripciones[juego];
            // document.getElementById("descripcion") = Esto busca el elemento HTML con id "descripcion"
            // innerText = Esto cambia el texto visible del elemento encontrado
            // descripciones[juego] = Esto obtiene la descripción asociada al videojuego que se ha pulsado
        }
    </script>
</body>
</html>