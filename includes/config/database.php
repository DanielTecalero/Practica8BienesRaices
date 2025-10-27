<?php

function conectarDB() : mysqli {
    $db = mysqli_connect('localhost','root','','bienesraices_CRUD');

    if(!$db){
        echo "Error No se pudo conectar";
    }
    return $db;
}