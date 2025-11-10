<?php
require_once 'models/usuario.php';

class UsuarioController {

    /* ----------- Vista principal (Dashboard o Home Público) ----------- */
    public function index() {
        if(isset($_SESSION['identity'])){
            // Si está logueado, muestra el dashboard
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
            $usuario->setCedula($_POST['id']);   // Cédula
            $usuario->setNombre($_POST['nombre']);
            $usuario->setEmail($_POST['email']);
            $usuario->setTelefono($_POST['telefono']);
            $usuario->setDireccion($_POST['direccion']);
            $usuario->setPassword($_POST['password']);

            // Validar contraseñas iguales
            if ($_POST['password'] === $_POST['password2']) {
                $save = $usuario->save();
                if ($save) {
                    // MODIFICADO: Usamos 'msgsuccess' para ser consistentes
                    $_SESSION['msgsuccess'] = "Registro completado con éxito! Inicia sesión.";
                    // MODIFICADO: Redirigimos al home (donde verá el login)
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
            $usuario->setCedula($_POST['cedula']); // usamos cédula
            $usuario->setPassword($_POST['clave']); // clave sin hash

            $datos = $usuario->login();

            if ($datos && is_object($datos)) {
                $_SESSION['identity'] = $datos; // guardamos datos de sesión
                // MODIFICADO: Usamos 'msgsuccess' para ser consistentes
                $_SESSION['msgsuccess'] = "Bienvenido, {$datos->nombre_usuario}";
                
                // MODIFICADO: Redirigimos al home (que ahora será el dashboard)
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
        $_SESSION['msgok'] = "Sesión cerrada correctamente";
        require_once 'views/success.php';
    }
}
?>
