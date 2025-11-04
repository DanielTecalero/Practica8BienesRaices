<?php 
    require 'includes/funciones.php';
    
    incluirTemplate('header');
?>
    <main class="contenedor seccion">
        <h2>Casas y Depas en Ventas</h2>
        <?php include'includes/templates/anuncios.php'; ?>

    </main>

<?php 
    incluirTemplate('footer');
?>