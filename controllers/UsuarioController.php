<?php
require_once 'models/usuario.php';
require_once 'models/transaccion.php';

class UsuarioController {

    /* ----------- Vista principal (Dashboard o Home Público) ----------- */
    public function index() {
        if(isset($_SESSION['identity'])){
            // Si está logueado, muestra el dashboard
            
            // 1. Cargar las transacciones recientes
            $transaccion_model = new Transaccion();
            $id_usuario = $_SESSION['identity']->id_usuario;
            $transacciones = $transaccion_model->getRecentByUserId($id_usuario);
            
            // 2. Cargar la vista del dashboard (y pasarle los datos)
            require_once 'views/dashboard/index.php';
        } else {
            // Si NO está logueado, muestra el formulario de registro
            require_once 'views/usuarios/usuarios.php';
        }
    }

    /* ----------- Mostrar formulario de registro ----------- */
    public function registro() {
        require_once 'views/usuarios/usuarios.php'; 
    }

/* ----------- Guardar un nuevo usuario ----------- */
    public function save() {
        if (isset($_POST)) {
            $usuario = new Usuario();
            $usuario->setCedula($_POST['id']);
            $usuario->setNombre($_POST['nombre']);
            $usuario->setEmail($_POST['email']);
            $usuario->setTelefono($_POST['telefono']);
            $usuario->setDireccion($_POST['direccion']);
            $usuario->setPassword($_POST['password']);

            if ($_POST['password'] === $_POST['password2']) {
                $save = $usuario->save();
                if ($save) {
                    $_SESSION['msgsuccess'] = "Registro completado con éxito! Inicia sesión.";
                    ob_end_clean(); // <-- Limpiamos el búfer
                    header("Location: ".base_url);
                    exit();
                } else {
                    $_SESSION['msgerror'] = "Error en el registro. Verifica si ya existe la cédula o el correo.";
                    require_once 'views/error.php';
                }
            } else {
                $_SESSION['msgerror'] = "Las contraseñas no coinciden";
                require_once 'views/error.php';
            }
        } else {
            $_SESSION['msgerror'] = "No se recibieron datos del formulario";
            require_once 'views/error.php';
        }
    }

    /* ----------- Login con CÉDULA ----------- */
    public function login() {
        if (isset($_POST['cedula']) && isset($_POST['clave'])) {
            $usuario = new Usuario();
            $usuario->setCedula($_POST['cedula']);
            $usuario->setPassword($_POST['clave']);

            $datos = $usuario->login();

            if ($datos && is_object($datos)) {
                $_SESSION['identity'] = $datos; 
                $_SESSION['msgsuccess'] = "Bienvenido, {$datos->nombre_usuario}";
                
                ob_end_clean(); // <-- Limpiamos el búfer
                header("Location: ".base_url);
                exit();
            } else {
                $_SESSION['msgerror'] = "Cédula o contraseña incorrecta";
                require_once 'views/error.php';
            }
        } else {
            $_SESSION['msgerror'] = "Debe ingresar cédula y contraseña";
            require_once 'views/error.php';
        }
    }

    /* ----------- Logout ----------- */
    public function logout() {
        if (isset($_SESSION['identity'])) {
            unset($_SESSION['identity']);
        }
        
        session_destroy();
        session_start();
        $_SESSION['msgsuccess'] = "Sesión cerrada correctamente";
        
        ob_end_clean();
        header("Location: ".base_url);
        exit(); 
    }
}
?>
