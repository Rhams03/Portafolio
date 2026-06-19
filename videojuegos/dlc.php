<!DOCTYPE html> <!-- Esto es para indicar que es un documento HTML5(Versión más reciente) -->
<html lang="es"> <!-- Esto es un documento HTML con un indicador(lang="es) de que el idioma del documento es español -->
<head> <!-- Esto es el encabezado(head) del documento -->
    <meta charset="UTF-8"> <!-- Esto es para indicar que el conjunto de carcateres son UTF-8(codifica todos los caracteres de todos los idiomas del mundo) -->
    <title>Nuevo DLC</title> <!-- Esto es un título -->
    <link rel="stylesheet" href="estilos.css"> <!-- Esto es para enlazar el archivo de estilos CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Esto carga una hoja de estilo desde el internet -->
</head> <!-- Esto cierra el encabezado(head) -->
<body> <!-- Esto es el cuerpo(body) de la página -->
    <header> <!-- Esto es el encabezado(header) del cuerpo(body) -->
        <h1 class="producto">Producto</h1> <!-- Esto es un encabezado de nivel 1(el mas grande) -->
        <nav> <!-- Esto es para indicar que es un menú de navegación(donde poner enlaces) -->
            <h2>Menú</h2> <!-- Esto es un encabezado de nivel 2(el segundo mas grande) -->
            <div class="menu-links"> <!-- Esto es un contenedor(div) para los links-->
                <a href="videojuego.php">Añadir Videojuego</a> <!--Esto es un link -->
                <a href="dlc.php">Añadir DLC</a> <!--Esto es un link -->
                <a href="tipos.php">Lista de Videojuegos</a> <!--Esto es un link -->
            </div> <!-- Esto cierra el div -->
        </nav> <!-- Esto cierra el menú de navegación -->
    </header> <!-- Esto cierra el encabezado(header) -->
    <main> <!-- Esto es para el contenido central de la página -->
        <h3>Registrar Nuevo DLC</h3> <!-- Esto es un encabezado de nivel 3(el tercero mas grande) -->       
        <form id="formulario"> <!-- Esto es un formulario(form) -->
            <section> <!-- Esto es una sección(section) de la página -->
            <p> <!-- Esto es un párrafo (tambien sirve para poner elementos uno arriba de otro) -->
                <label for="titulo">Título del juego:</label> <!-- Esto sirve para indicar que cosa hay que poner en el input --> 
                <input type="text" id="titulo" name="titulo" placeholder="Iceborne Expansion" required> <!-- Esto es un campo de texto(text) para escribir texto, con un indicador fantasma(placeholder) y una restricción(required) para que sea obligatorio llenar-->
            </p> <!-- Esto cierra el párrafo -->
            <p><!-- Esto es un párrafo (tambien sirve para poner elementos uno arriba de otro) -->
                <label for="codigo">ID Juego Base:</label> <!-- Esto sirve para indicar que cosa hay que poner en el input -->
                <input type="number" id="codigo" name="codigo" min="1" max="999999999" placeholder="1" required> <!-- Esto es un campo númerico(number) para poner números, con un indicador fantasma(placeholder), un indicador de numero minimo(min) y uno de maximo(999999999) una restricción(required) para que sea obligatorio llenar -->
            </p> <!-- Esto cierra el párrafo -->
            <label for="descripcion">Descripción:</label> <!-- Esto sirve para indicar que cosa hay que poner en el input --> 
            <p> <!-- Esto es un párrafo (tambien sirve para poner elementos uno arriba de otro) -->
                <textarea name="descripcion" id="descripcion" rows="4" cols="40" placeholder="ejemplo" required></textarea> <!-- Esto es un campo de texto grande(textarea) para escribir texto, con un indicador fantasma(placeholder), una restricción(required) para que sea obligatorio llenar, y con un tamaño de 4 filas(rows) y 40 columnas(cols) -->
            </p> <!-- Esto cierra el párrafo -->
            <p> <!-- Esto es un párrafo (tambien sirve para poner elementos uno arriba de otro) -->
                <label for="precio">Precio:</label> <!-- Esto sirve para indicar que cosa hay que poner en el input -->
                <input type="number" id="precio" name="precio" min="0" step="0.01" placeholder="0.00" required> <!-- Esto es un campo númerico(number) para poner números, con un indicador fantasma(placeholder), una restricción(required) para que sea obligatorio llenar, y con un valor mínimo(min) de 0 y un paso(step) de 0.01 -->
            </p> <!-- Esto cierra el párrafo -->
            <p> <!-- Esto es un párrafo (tambien sirve para poner elementos uno arriba de otro) -->
                <label for="stock">Stock:</label> <!-- Esto sirve para indicar que cosa hay que poner en el input -->
                <input type="number" id="stock" name="stock" min="0" placeholder="0" required> <!-- Esto es un campo númerico(number) para poner números, con un indicador fantasma(placeholder), una restricción(required) para que sea obligatorio llenar, y con un valor mínimo(min) de 0 -->
            </p> <!-- Esto cierra el párrafo -->
            </section> <!--Esto cierra la sección -->
            <p> <!-- Esto es un párrafo (tambien sirve para poner elementos uno arriba de otro) -->
                <input type="submit" value="Guardar DLC"> <!-- Esto es un campo de envió(submit) para enviar el formulario con un valor de "Guardar DLC" -->
            </p> <!-- Esto cierra el párrafo -->
            <hr> <!-- Esto hace una linea en el documento(es temporal) -->
        </form> <!-- Esto cierra el formulario -->
        <h3>Lista de DLCs</h3> <!-- Esto es un encabezado de nivel 3(el tercero mas grande) -->
        <div id="listaDLC"></div> <!-- Esto es un contenedor(div) para la lista de DLCs -->
    </main> <!-- Esto cierra el contenido principal -->
    <footer> <!-- Esto es un pie de página -->
        <p> <!-- Esto es un párrafo (tambien sirve para poner elementos uno arriba de otro) -->
            <strong>Creador de Juegos y DLCs</strong> <!-- Esto pone en negrita el texto seleccionado -->
        </p> <!-- Esto cierra el párrafo -->
        <p> <!-- Esto es un párrafo (tambien sirve para poner elementos uno arriba de otro) -->
            <a href="https://x.com/Rhams93180821" target="_blank"><i class="fa-brands fa-twitter"></i>Twitter</a> <!--Esto es un link -->
        </p> <!-- Esto cierra el párrafo -->
        <p> <!-- Esto es un párrafo (tambien sirve para poner elementos uno arriba de otro) -->
            <a href="https://github.com/Rhams03/Portafolio.git" target="_blank"><i class="fa-brands fa-github"></i>Mi GitHub</a> <!--Esto es un link -->
        </p> <!-- Esto cierra el párrafo -->
    </footer> <!-- Esto cierra el pie de página -->
    <script> 
    //Nota: JSON: Es un formato de texto utilizado para guardar y transportar datos de forma organizada.
        let dlcs = JSON.parse(localStorage.getItem("dlcs")) || []; // Esto es un array que obtiene los dlcs del localStorage y si no hay ninguno, crea un array vacío
        let indiceEditar = -1; // Esto es una variable que indica el índice del dlc que se va a editar, si es -1 significa que no se está editando ningún dlc 
        document.getElementById("formulario").addEventListener("submit", guardarDLC); // Esto es un evento que se ejecuta cuando se envía el formulario y llama a la función guardarDLC

        function guardarDLC(event){ // Esto es una función que guarda un dlc en el array y en el localStorage (Nota: event es un objeto que contiene información sobre el evento que se ha producido)

            event.preventDefault(); // Esto evita que se recargue la página al enviar el formulario

            let dlc = { // Esto es un objeto que contiene los datos del dlc
                titulo: document.getElementById("titulo").value, // Esto obtiene el valor del input con id "titulo"
                codigo: document.getElementById("codigo").value, // Esto obtiene el valor del input con id "codigo"
                descripcion: document.getElementById("descripcion").value, // Esto obtiene el valor del input con id "descripción"
                precio: document.getElementById("precio").value, // Esto obtiene el valor del input con id "precio"
                stock: document.getElementById("stock").value // Esto obtiene el valor del input con id "stock"
            };

            if(indiceEditar == -1){ // Esto significa que no se esta editando ningun dlc, por lo que se agrega uno nuevo al array

                dlcs.push(dlc); // Esto agrega el dlc al array de dlcs

            }else{ // Esto significa que se esta editando un dlc, por lo que se reemplaza el dlc en el array

                dlcs[indiceEditar] = dlc; // Esto reemplaza el dlc en el array de dlcs en el índice que se esta editando

                indiceEditar = -1; // Esto reinicia el índice de edición a -1 para indicar que no se está editando ningún dlc.
            }

            localStorage.setItem("dlcs", JSON.stringify(dlcs)); // Esto guarda el array de dlcs en el localStorage como una cadena JSON

            document.getElementById("formulario").reset(); // Esto reinicia el formulario para que los campos queden vacíos

            mostrarDLCs(); // Esto llama a la función mostrarDLCs para actualizar la lista de dlcs en la página 
        }

        //-----------------------------------------------------------//

        function mostrarDLCs(){ // Esto es una función que muestra los dlcs en la página

            let html = "";

            dlcs.forEach((dlc, indice)=>{ // Esto es un bucle que recorre el array de dlcs y genera una tarjeta HTML para cada uno, mas un botón de editar y otro de eliminar

                html += // Nota: html += es para añadir contenido a una variable en lugar de reemplazarlo.
                    `
                    <div class="tarjeta">
                        <p>Título: ${dlc.titulo}</p>
                    
                        <p>Codigo: #${dlc.codigo}</p>
                        
                        <p class="descripcion">Descripción: ${dlc.descripcion}</p>
                        
                        <p>Precio: ${dlc.precio}€</p>
                        
                        <p>Stock: ${dlc.stock}</p>

                        <p><button onclick="editarDLC(${indice})">
                            Editar
                            </button>
                        </p>

                        <p><button onclick="eliminarDLC(${indice})">
                            Eliminar
                            </button>
                        </p>
                    </div>
                `;

            }); // Esto añade una tarjeta HTML con la información y los botones de cada DLC a la variable html

            document.getElementById("listaDLC").innerHTML = html; // Esto inserta todas las tarjetas generadas dentro del contenedor listaDLC
        }

        mostrarDLCs(); // Esto ejecuta la función al cargar la página para mostrar los DLCs guardados

        //-----------------------------------------------------------------//

        function eliminarDLC(indice){ // Esto es una función que elimina un DLC según su índice

            dlcs.splice(indice, 1); // Esto elimina un elemento del array en la posición indicada

            localStorage.setItem("dlcs", JSON.stringify(dlcs)); // Esto actualiza los datos guardados en localStorage

            mostrarDLCs(); // Esto vuelve a mostrar la lista actualizada
        }

        //---------------------------------------------------------------//

        function editarDLC(indice){ // Esto es una función que carga los datos de un DLC en el formulario para modificarlos

            let dlc = dlcs[indice]; // Esto obtiene el DLC correspondiente al índice seleccionado

            document.getElementById("titulo").value = dlc.titulo; // Esto coloca el título del DLC en el campo título
            document.getElementById("codigo").value = dlc.codigo; // Esto coloca el código del DLC en el campo código
            document.getElementById("descripcion").value = dlc.descripcion; // Esto coloca el descripción del DLC en el campo descripción
            document.getElementById("precio").value = dlc.precio; // Esto coloca el precio del DLC en el campo precio
            document.getElementById("stock").value = dlc.stock; // Esto coloca el stock del DLC en el campo get

            indiceEditar = indice; // Esto guarda el índice del DLC para que al guardar se actualice en lugar de crear uno nuevo
        }
    </script>
</body> <!-- Esto cierra el cuerpo de la página -->
</html> <!-- Esto cierra el documento HTML -->