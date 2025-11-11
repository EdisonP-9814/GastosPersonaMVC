<?php
    ob_start();
    session_start();
    require_once 'config/parameters.php';
    require_once 'autoload.php';
    require_once 'helpers/utils.php';
    require_once 'views/header.php';
    require_once 'views/sidebar.php';

    function show_error(){
      $_SESSION['msgerror'] = 'Error: Página no encontrada (Controlador o Acción inválida).';
      require_once 'views/error.php';
    }

    // 1. Determinar el nombre del controlador
    if(isset($_GET['controller'])){
      
      // ESTA ES LA LÍNEA CORREGIDA
      $nombre_controlador = ucfirst($_GET['controller']).'Controller';

    } elseif(!isset($_GET['controller']) && !isset($_GET['action'])){
      $nombre_controlador = controller_default;
    } else {
      show_error();
      exit();
    }

    // 2. Comprobar si la clase existe
    if(class_exists($nombre_controlador)){
        
        // 3. Instanciar el controlador
        $controlador = new $nombre_controlador();

        // 4. Comprobar si la acción existe y llamarla
        if(isset($_GET['action']) && method_exists($controlador, $_GET['action'])){
            $action = $_GET['action'];
            $controlador->$action();
        } elseif(!isset($_GET['controller']) && !isset($_GET['action'])){
            // Cargar la acción por defecto
            $action_default = action_default;
            $controlador->$action_default();
        } else {
            show_error(); // La acción no existe
        }
    } else {
        show_error(); // La clase (controlador) no existe
    }
    require_once 'views/footer.php';
?>