<?php
    session_start();
    require_once 'config/parameters.php';
    require_once 'controllers/UserController.php';
    require_once 'views/header.php';
    require_once 'views/sidebar.php';

    function show_error(){
      $_SESSION['msgerror'] = 'Página no encontrada';
      require_once 'views/error.php';
    }

    if(isset($_GET['controller'])){
      $controller = $_GET['controller'].'Controller';
    } elseif(!isset($_GET['controller']) && !isset($_GET['action'])){
      $controller = controller_default;
    } else {
      show_error();
      exit();
    }

    if(class_exists($controller)){
      $controlador = new $controller();

      if(isset($_GET['action']) && method_exists($controlador, $_GET['action'])){
        $action = $_GET['action'];
        $controlador->$action();
      } elseif(!isset($_GET['controller']) && !isset($_GET['action'])){
        $action_default = action_default;
        $controlador->$action_default();
      } else {
        show_error();
      }
    } else {
      show_error();
    }
?>