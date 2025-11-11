<?php
// controllers/CuentaController.php
require_once 'models/cuenta.php'; 

class CuentaController {

    /**
     * Verifica si el usuario ha iniciado sesión.
     */
    private function checkLogin() {
        if (!isset($_SESSION['identity'])) {
            $_SESSION['msgerror'] = "Debe iniciar sesión para acceder a esta sección.";
            require_once 'views/error.php';
            exit(); 
        }
    }

    /** Acción Principal: Muestra el listado de cuentas.**/
    public function index() {
        $this->checkLogin(); 

        $cuenta = new Cuenta();
        $cuenta->setIdUsuario($_SESSION['identity']->id_usuario); 
        $cuentas = $cuenta->getAllByUserId(); 

        require_once 'views/cuentas/index.php'; 
    }

    /**Acción Crear: Muestra el formulario para crear una nueva cuenta.**/
    public function crear() {
        $this->checkLogin(); 
        require_once 'views/cuentas/crear.php';
    }

    /**
     * Acción Save: Recibe los datos del formulario (POST) y los guarda.
     */
    public function save() {
        $this->checkLogin(); 

        if (isset($_POST)) {        
            $nombre = $_POST['nombre'] ?? null;
            $tipo = $_POST['tipo'] ?? null;
            $saldo = $_POST['saldo'] ?? 0.00;
            $id_usuario = $_SESSION['identity']->id_usuario;

            if ($nombre && $tipo) {
                $cuenta = new Cuenta();
                $cuenta->setNombre($nombre);
                $cuenta->setTipo($tipo);
                $cuenta->setSaldoInicial($saldo);
                $cuenta->setIdUsuario($id_usuario);
                
                $save = $cuenta->save(); 

                if ($save) {
                    $_SESSION['msgsuccess'] = "Cuenta creada con éxito.";
                    ob_end_clean();
                    header("Location: " . base_url . "Cuenta/index");
                    exit();
                } else {
                    $_SESSION['msgerror'] = "Error al crear la cuenta. Verifique los datos.";
                    require_once 'views/error.php';
                }
            } else {
                $_SESSION['msgerror'] = "Faltan campos obligatorios (Nombre o Tipo).";
                require_once 'views/error.php';
            }
        } else {
            $_SESSION['msgerror'] = "No se recibieron datos del formulario.";
            require_once 'views/error.php';
        }
    }
}
