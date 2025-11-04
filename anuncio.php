<?php 
    // --- 1. OBTENER Y VALIDAR EL ID ---
    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT); // Validar que sea un entero

    if(!$id) {
        // Si no es un ID válido, redireccionar al inicio
        header('Location: /');
        exit;
    }

    // --- 2. CONECTAR A LA BD ---
    require 'includes/config/database.php';
    $db = conectarDB();

    // --- 3. CONSULTAR LA PROPIEDAD ---
    $query = "SELECT * FROM propiedades WHERE id = {$id}";
    $resultado = mysqli_query($db, $query);

    if(!$resultado->num_rows) {
        // Si no se encuentra la propiedad (ID no existe), redireccionar
        header('Location: /');
        exit;
    }

    $propiedad = mysqli_fetch_assoc($resultado);


    // --- 4. INCLUIR TEMPLATES ---
    require 'includes/funciones.php';
    incluirTemplate('header');
?>
    <main class="contenedor seccion contenido-centrado">
        <h1><?php echo $propiedad['titulo']; ?></h1>

        <img loading="lazy" src="/imagenes/<?php echo $propiedad['imagen']; ?>" alt="imagen de la propiedad">

        <div class="resumen-propiedad">
            <p class="precio">$<?php echo number_format($propiedad['precio']); ?></p>
            
            <ul class="iconos-caracteristicas">
                <li>
                    <img class="icono" loading="lazy" src="build/img/icono_wc.svg" alt="Icono WC">
                    <p><?php echo $propiedad['wc']; ?></p>
                </li>
                <li>
                    <img class="icono" loading="lazy" src="build/img/icono_estacionamiento.svg" alt="Icono Estacionamiento">
                    <p><?php echo $propiedad['estacionamiento']; ?></p>
                </li>
                <li>
                    <img class="icono" loading="lazy" src="build/img/icono_dormitorio.svg" alt="Icono Habitaciones">
                    <p><?php echo $propiedad['habitaciones']; ?></p>
                </li>
            </ul>

            <p><?php echo nl2br($propiedad['descripcion']); ?></p>

        </div>
    </main>

<?php 
    // --- 5. CERRAR LA CONEXIÓN ---
    mysqli_close($db);
    
    incluirTemplate('footer');
?>