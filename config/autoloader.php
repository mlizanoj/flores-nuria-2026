<?php
spl_autoload_register(function ($nombre_clase) {
    // Buscamos la clase en la carpeta 'clases'
    $archivo = __DIR__ . "/php/model/$nombre_clase.php";

    if (file_exists($archivo)) {
        require_once $archivo;
    }
});