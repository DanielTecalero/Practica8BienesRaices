<?php 
    
// 1. Incluir conexión y funciones
require '../../includes/config/database.php';
$db = conectarDB();

// 2. Verificar que la petición sea POST
if($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 3. Obtener y validar el ID
    $id = $_POST['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if($id) {
        // 4. Obtener el nombre de la imagen
        $consulta = "SELECT imagen FROM propiedades WHERE id = {$id}";
        $resultado = mysqli_query($db, $consulta);
        $propiedad = mysqli_fetch_assoc($resultado);

        // 5. Eliminar el archivo de imagen (CON LA RUTA CORRECTA)
        // Estamos en admin/propiedades/, necesitamos subir dos niveles (../../)
        $carpetaImagenes = '../../imagenes/';
        if(file_exists($carpetaImagenes . $propiedad['imagen'])) {
            unlink($carpetaImagenes . $propiedad['imagen']);
        }

        // 6. Eliminar el registro de la base de datos
        $query = "DELETE FROM propiedades WHERE id = {$id}";
        $resultadoDelete = mysqli_query($db, $query);

        // 7. Redireccionar al admin con mensaje de éxito
        if($resultadoDelete) {
            header('Location: /admin?resultado=3');
        }
    }

} else {
    // Si alguien intenta acceder por GET, lo sacamos
    header('Location: /admin');
}

// 8. Cerrar la conexión
mysqli_close($db);

?>