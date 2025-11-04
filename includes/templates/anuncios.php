<?php
    // --- 1. CONEXIÓN A LA BD (Con ruta corregida) ---
    // __DIR__ es /includes/templates, subimos un nivel a /includes/
    require __DIR__ . '/../config/database.php';
    $db = conectarDB();

    // --- 2. CONSTRUIR EL QUERY ---
    $query = "SELECT * FROM propiedades LIMIT 3";

    // --- 3. CONSULTAR LA BD ---
    $resultado = mysqli_query($db, $query);
?>

<div class="contenedor-anuncios">
    <?php while($propiedad = mysqli_fetch_assoc($resultado)) : ?>

    <div class="anuncio">
        <!-- 
            HTML Dinámico: 
            Usamos la variable $propiedad para mostrar los datos.
            Las imágenes de propiedades están en /imagenes/, no en /build/img/.
        -->
        <img loading="lazy" src="/imagenes/<?php echo $propiedad['imagen']; ?>" alt="Anuncio">

        <div class="contenido-anuncio">
            <h3><?php echo $propiedad['titulo']; ?></h3>
            <p><?php echo $propiedad['descripcion']; ?></p>
            <p class="precio">$<?php echo $propiedad['precio']; ?></p>

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

            <!-- El enlace ahora pasa el ID a la página de anuncio.php -->
            <a href="anuncio.php?id=<?php echo $propiedad['id']; ?>" class="boton-amarillo-block">
                Ver Propiedad
            </a>

        </div><!--.contenido-anuncio-->
    </div><!--.anuncio-->

    <?php endwhile; ?>
</div><!--.contenedor-anuncios-->


<?php 
    //--- 4. CERRAR LA CONEXIÓN ---
    mysqli_close($db); 
?>