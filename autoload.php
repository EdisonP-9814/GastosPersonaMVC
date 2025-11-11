<?php
  function controller_autoload($classname){
    
    $file_path = 'controllers/'.$classname.'.php';
    
    // DEBUG: Verificamos si el archivo existe antes de incluirlo
    if (file_exists($file_path)) {
        include $file_path;
    } else {
        // Si no existe, detenemos todo y mostramos el error
        die("ERROR DE AUTOLOAD: No se pudo encontrar el archivo en la ruta: " . $file_path);
    }

  }
  spl_autoload_register('controller_autoload');
?>