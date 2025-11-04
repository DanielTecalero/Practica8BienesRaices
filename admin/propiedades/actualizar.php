<?php 
    
    // --- 1. VALIDAR EL ID ---
    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT); // Validar que sea un entero

    if(!$id) {
        // Si no es un ID válido, redireccionar
        header('Location: /admin');
    }

    // --- 2. CONEXIÓN Y OBTENER DATOS ---
    
    //Base de datos
    require '../../includes/config/database.php';
    $db = conectarDB();

    //Consultar para obtener los datos de LA PROPIEDAD
    $consultaPropiedad = "SELECT * FROM propiedades WHERE id = {$id}";
    $resultadoPropiedad = mysqli_query($db, $consultaPropiedad);
    
    if(!$resultadoPropiedad->num_rows) {
        // Si no se encuentra la propiedad, redireccionar
        header('Location: /admin');
    }
    $propiedad = mysqli_fetch_assoc($resultadoPropiedad);


    //Consultar para obtener los VENDEDORES
    $consultaVendedores = "SELECT * FROM vendedores";
    $resultadoVendedores = mysqli_query($db, $consultaVendedores);

    //Arreglo con mensajes de errores
    $errores = [];

    //Variables con los datos de la BD (para rellenar el formulario)
    $titulo = $propiedad['titulo'];
    $precio = $propiedad['precio'];
    $descripcion = $propiedad['descripcion'];
    $habitaciones = $propiedad['habitaciones'];
    $wc = $propiedad['wc'];
    $estacionamiento = $propiedad['estacionamiento'];
    $vendedorId = $propiedad['vendedorId'];
    $imagenPropiedad = $propiedad['imagen']; // Guardamos el nombre de la imagen actual


    //--- 3. LÓGICA PARA RECIBIR EL FORMULARIO (UPDATE) ---

    //Ejecutar el código después de que el usuario envia el formulario
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        // Asignar los datos del formulario a variables (con saneamiento)
        $titulo = mysqli_real_escape_string( $db, $_POST['titulo'] );
        $precio = mysqli_real_escape_string( $db, $_POST['precio'] );
        $descripcion = mysqli_real_escape_string( $db, $_POST['descripcion'] );
        $habitaciones = mysqli_real_escape_string( $db, $_POST['habitaciones'] );
        $wc = mysqli_real_escape_string( $db, $_POST['wc'] );
        $estacionamiento = mysqli_real_escape_string( $db, $_POST['estacionamiento'] );
        $vendedorId = mysqli_real_escape_string( $db, $_POST['vendedorId'] );

        // --- MANEJO DE IMAGEN ---
        $imagen = $_FILES['imagen'];

        // Validar el formulario
        if(!$titulo) {
            $errores[] = "Debes añadir un titulo";
        }
        if(!$precio) {
            $errores[] = "El Precio es Obligatorio";
        }
        if( strlen($descripcion) < 50 ) {
            $errores[] = "La descripción es obligatoria y debe tener al menos 50 caracteres";
        }
        if(!$habitaciones) {
            $errores[] = "El número de habitaciones es obligatorio";
        }
        if(!$wc) {
            $errores[] = "El número de Baños es obligatorio";
        }
        if(!$estacionamiento) {
            $errores[] = "El número de lugares de Estacionamiento es obligatorio";
        }
        if(!$vendedorId) {
            $errores[] = "Elige un vendedor";
        }

        // Validar la imagen SI SE SUBE UNA NUEVA
        if($imagen['name']) {
            // Validar por tamaño (1mb máximo)
            $medida = 1000 * 1000; // 1 MB
            if($imagen['size'] > $medida ) {
                $errores[] = 'La Imagen es muy pesada (Máximo 1MB)';
            }
        }


        //Revisar que el array de errores este vacio
        if(empty($errores)) {

            /** --- 4. SUBIDA DE ARCHIVOS (SI HAY NUEVA IMAGEN) --- **/
            $carpetaImagenes = '../../imagenes/';
            $nombreImagen = '';

            if($imagen['name']) {
                // Hay una imagen nueva
                
                // 1. Eliminar la imagen anterior
                unlink($carpetaImagenes . $propiedad['imagen']);

                // 2. Generar un nombre único para la nueva imagen
                $nombreImagen = md5( uniqid( rand(), true ) ) . ".jpg";

                // 3. Subir la nueva imagen
                move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen );
            } else {
                // No hay imagen nueva, conservar la anterior
                $nombreImagen = $propiedad['imagen'];
            }
            

            //--- 5. ACTUALIZAR EN LA BASE DE DATOS ---
            $query = "UPDATE propiedades SET 
                        titulo = '{$titulo}', 
                        precio = '{$precio}', 
                        imagen = '{$nombreImagen}', 
                        descripcion = '{$descripcion}', 
                        habitaciones = {$habitaciones}, 
                        wc = {$wc}, 
                        estacionamiento = {$estacionamiento}, 
                        vendedorId = {$vendedorId} 
                      WHERE id = {$id}";


            $resultado = mysqli_query($db, $query);

            if($resultado) {
                // Si se actualiza correctamente, redireccionar al admin
                // Usamos ?resultado=2 para el mensaje de éxito
                header('Location: /admin?resultado=2');
            }
        }
    }

    //--- RENDERIZADO DEL HTML ---
    require '../../includes/funciones.php';
    incluirTemplate('header');
?>
    <main class="contenedor seccion">
        <h1>Actualizar Propiedad</h1>

        <a href="/admin" class="boton boton-verde">Volver</a>

        <?php foreach($errores as $error): ?>
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?>

        <form class="formulario" method="POST" enctype="multipart/form-data">
            <fieldset>
                <legend>Informacion General</legend>

                <label for="titulo">Titulo:</label>
                <input type="text" id="titulo" name="titulo" placeholder="Titulo Propiedad" value="<?php echo $titulo; ?>">

                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" placeholder="Precio Propiedad" value="<?php echo $precio; ?>">

                <label for="imagen">Imagen Actual:</label>
                <img src="/imagenes/<?php echo $imagenPropiedad; ?>" alt="Imagen de la propiedad" class="imagen-small">
                
                <label for="imagen">Nueva Imagen (opcional):</label>
                <input type="file" id="imagen" name="imagen" accept="image/jpeg, image/png">

                <label for="descripcion">Descripcion:</label>
                <textarea id="descripcion" name="descripcion"><?php echo $descripcion; ?></textarea>
            </fieldset>

            <fieldset>
                <legend>Informacion Propiedad</legend>

                <label for="habitaciones">Habitaciones:</label>
                <input type="number" id="habitaciones" name="habitaciones" placeholder="Ej: 3" min="1" max="9" value="<?php echo $habitaciones; ?>">

                <label for="wc">Baños:</label>
                <input type="number" id="wc" name="wc" placeholder="Ej: 3" min="1" max="9" value="<?php echo $wc; ?>">

                <label for="estacionamiento">Estacionamiento:</label>
                <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Ej: 3" min="1" max="9" value="<?php echo $estacionamiento; ?>">
            </fieldset>

            <fieldset>
                <legend>Vendedor</legend>

                <select name="vendedorId" id="vendedorId">
                    <option value="" disabled selected>-- Seleccione un Vendedor --</option>
                    <?php while( $vendedor = mysqli_fetch_assoc($resultadoVendedores) ) : ?>
                        <option 
                            <?php 
                                // Esta lógica pre-selecciona el vendedor actual
                                echo $vendedorId === $vendedor['id'] ? 'selected' : ''; 
                            ?> 
                            value="<?php echo $vendedor['id']; ?>"
                        >
                            <?php echo $vendedor['nombre'] . " " . $vendedor['apellido']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </fieldset>

            <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">
        </form>
    </main>

<?php 
    incluirTemplate('footer');
?>