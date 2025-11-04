<?php 


    //Importar la conexion
    require '../includes/config/database.php';
    $db = conectarDB();

    //Escribir query
    $query = "SELECT * FROM propiedades LIMIT 10";
    //Consultar la BD
    $resultadoConsulta = mysqli_query( $db, $query );
    
    $resultado = $_GET['resultado'] ?? null;   


    require '../includes/funciones.php';  
    incluirTemplate('header');
?>
    <main class="contenedor seccion">
        <h1>Administrador de Bienes Raices</h1>
        <a href="/admin/propiedades/crear.php" class="boton boton-verde">Nueva Propiedad</a>

        <?php if( intval( $resultado ) === 1 ): ?>
            <p class="alerta exito">Anuncio Creado Correctamente</p>
        <?php elseif( intval( $resultado ) === 2 ): ?>
            <p class="alerta exito">Anuncio Actualizado Correctamente</p>
        <?php elseif( intval( $resultado ) === 3 ): ?>
            <p class="alerta exito">Anuncio Eliminado Correctamente</p>
        <?php endif; ?>

    <table class="propiedades">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titulo</th>
                <th>Imagen</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php while( $propiedad = mysqli_fetch_assoc( $resultadoConsulta ) ) : ?>

            <tr>
                <td><?php echo $propiedad['id']; ?></td>
                <td><?php echo $propiedad['titulo']; ?></td>
                <td><img src="/imagenes/<?php echo $propiedad['imagen']; ?>" alt="imagen propiedad" class="imagen-tabla"></td>
                <td>$<?php echo $propiedad['precio']; ?></td>
                <td>
                    <a href="/admin/propiedades/actualizar.php<?php echo '?id=' . $propiedad['id']; ?>" class="boton boton-amarillo-block">Actualizar</a>
                    <form method="POST" class="w-100" action="/admin/propiedades/borrar.php">
                        <input type="hidden" name="id" value="<?php echo $propiedad['id']; ?>">
                        <input type="submit" class="boton boton-rojo-block" value="Eliminar">
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    </main>

<?php 

    //Cerrar la conexion
    mysqli_close( $db );

    incluirTemplate('footer');
?>

