<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Videojuego</title>
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
        <h3>Registrar Nuevo Juego</h3>
       
        <form id="formulario"> 
            <section>
            <p>
                <label for="titulo">Título del juego:</label>
                <input type="text" id="titulo" name="titulo" placeholder="Monster Hunter" required>
            </p>
            <p>
                <label for="dev">Desarrolladora:</label>
                <input type="text" id="dev" name="dev" placeholder="Capcom" required>
            </p>
            <p>
                <label for="genero">Género:</label>
                <select name="genero" id="genero">
                    <option value="RPG">RPG</option>
                    <option value="Accion">Acción</option>
                    <option value="Party">Party</option>
                    <option value="Sandbox">Sandbox</option>
                </select>
            </p>
            <p>
                <label for="plataforma">Plataforma:</label>
                <select name="plataforma" id="plataforma">
                    <option value="PC">PC</option>
                    <option value="Switch">Switch</option>
                    <option value="PS5">PlayStation 5</option>
                    <option value="Xbox">Xbox 360</option>
                </select>
            </p>
            <p>
                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" min="0" step="0.01" placeholder="0.00" required>
            </p>
            <p>
                <label for="stock">Stock:</label>
                <input type="number" id="stock" name="stock" min="0" placeholder="0" required>
            </p>
            </section>
            <p>
                <input type="submit" value="Guardar Videojuego">
            </p>
        </form>
        <hr>
        <h3>Lista de Videojuegos</h3> 
        <div id="listaVideojuego"></div>
    </main>
    <footer>
        <p>
            <strong>Creador de Juegos y DLCs</strong>
        </p>
        <p>
            <a href="https://x.com/Rhams93180821" target="_blank"><i class="fa-brands fa-twitter"></i>Twitter</a>
        </p>
        <p>
            <a href="https://github.com/Rhams03/Portafolio.git" target="_blank"><i class="fa-brands fa-github"></i>Mi GitHub</a>
        </p>
    </footer>
    <script>
        let videojuegos = JSON.parse(localStorage.getItem("videojuegos")) || [];
        let indiceEditar = -1;
        document.getElementById("formulario").addEventListener("submit", guardarVideojuego);

        function guardarVideojuego(event){

            event.preventDefault();

            let videojuego = {
                titulo: document.getElementById("titulo").value,
                dev: document.getElementById("dev").value,
                genero: document.getElementById("genero").value,
                plataforma: document.getElementById("plataforma").value,
                precio: document.getElementById("precio").value,
                stock: document.getElementById("stock").value

            };

            if(indiceEditar == -1){

                videojuegos.push(videojuego);

            }else{

                videojuegos[indiceEditar] = videojuego;

                indiceEditar = -1;
            }

            localStorage.setItem("videojuegos", JSON.stringify(videojuegos));

            document.getElementById("formulario").reset();

            mostrarVideojuegos();
        }

        //------------------------------------------------------------------------//

        function mostrarVideojuegos(){

            let html = "";

            videojuegos.forEach((videojuego, indice)=>{

                html += 
                    `
                    <div class="tarjeta">
                        <p>Título: ${videojuego.titulo}</p>

                        <p>Desarrolladora: ${videojuego.dev}</p>

                        <p>Género: ${videojuego.genero}</p>

                        <p>Plataforma: ${videojuego.plataforma}</p>

                        <p>Precio: ${videojuego.precio}€</p>

                        <p>Stock: ${videojuego.stock}</p>

                        <p><button onclick="editarVideojuego(${indice})">
                            Editar
                            </button>
                        </p>

                        <p><button onclick="eliminarVideojuego(${indice})">
                            Eliminar
                            </button>
                        </p>
                    </div>
                    `;
            });

            document.getElementById("listaVideojuego").innerHTML = html;
        }
        
        mostrarVideojuegos();

        //--------------------------------------------------------------//

        function eliminarVideojuego(indice){

            videojuegos.splice(indice, 1);

            localStorage.setItem("videojuegos", JSON.stringify(videojuegos));

            mostrarVideojuegos();
        }

        //--------------------------------------------------------------//

        function editarVideojuego(indice){

            let videojuego = videojuegos[indice];

            document.getElementById("titulo").value = videojuego.titulo;
            document.getElementById("dev").value = videojuego.dev;
            document.getElementById("genero").value = videojuego.genero;
            document.getElementById("plataforma").value = videojuego.plataforma;
            document.getElementById("precio").value = videojuego.precio;
            document.getElementById("stock").value = videojuego.stock;

            indiceEditar = indice;
        }
    </script>
</body>
</html>