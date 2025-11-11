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
    /**
     * Acción: Elimina una cuenta por ID
     */
    public function eliminar() {
        $this->checkLogin();

        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $id_usuario = $_SESSION['identity']->id_usuario;

            $cuenta = new Cuenta();
            $cuenta->setId($id);
            $cuenta->setIdUsuario($id_usuario); // Para seguridad

            $delete = $cuenta->delete();

            if ($delete) {
                $_SESSION['msgsuccess'] = "Cuenta eliminada correctamente.";
            } else {
                // El error puede ser por 2 motivos: no le pertenece o (más probable) tiene transacciones
                $_SESSION['msgerror'] = "Error: No se pudo eliminar la cuenta. Asegúrate de que no tenga transacciones asociadas.";
            }

            ob_end_clean();
            header("Location: ".base_url."Cuenta/index");
            exit();

        } else {
            $_SESSION['msgerror'] = "Error: No se especificó ninguna cuenta para eliminar.";
            ob_end_clean();
            header("Location: ".base_url."Cuenta/index");
            exit();
        }
    }
    /**
     * Acción: Muestra el formulario para EDITAR una cuenta
     */
    public function editar() {
        $this->checkLogin();

        // 1. Validar que nos pasen el ID
        if (!isset($_GET['id'])) {
            header("Location: ".base_url."Cuenta/index");
            exit();
        }

        $id_cuenta = (int)$_GET['id'];
        $id_usuario = $_SESSION['identity']->id_usuario;

        // 2. Cargar los datos de la cuenta
        $cuenta_model = new Cuenta();
        $cuenta_model->setId($id_cuenta);
        $cuenta_model->setIdUsuario($id_usuario);
        $cuenta_data = $cuenta_model->getOneById(); // Usamos la nueva función

        // Si no existe o no pertenece al usuario, redirigir
        if (!$cuenta_data) {
            $_SESSION['msgerror'] = "Error: La cuenta no existe.";
            ob_end_clean();
            header("Location: ".base_url."Cuenta/index");
            exit();
        }

        // 3. Cargar la vista de edición (que crearemos)
        require_once 'views/cuentas/editar.php';
    }
    /**
     * Acción: Recibe los datos (POST) del formulario de edición y actualiza la BD
     */
    public function update() {
        $this->checkLogin();

        if (isset($_GET['id']) && isset($_POST)) {
            
            // 1. Recoger datos
            $id_cuenta = (int)$_GET['id'];
            $id_usuario = $_SESSION['identity']->id_usuario;
            $nombre = $_POST['nombre'] ?? null;
            $tipo = $_POST['tipo'] ?? null;

            // 2. Validar
            if (!$nombre || !$tipo) {
                $_SESSION['msgerror'] = "Faltan datos obligatorios.";
                require_once 'views/error.php';
                exit();
            }

            // 3. Actualizar
            $cuenta = new Cuenta();
            $cuenta->setId($id_cuenta);
            $cuenta->setIdUsuario($id_usuario);
            $cuenta->setNombre($nombre);
            $cuenta->setTipo($tipo);
            
            $update = $cuenta->update();

            // 4. Redirigir
            if ($update) {
                $_SESSION['msgsuccess'] = "Cuenta actualizada con éxito.";
            } else {
                $_SESSION['msgerror'] = "Error al actualizar la cuenta.";
            }
            
            ob_end_clean();
            header("Location: ".base_url."Cuenta/index");
            exit();

        } else {
            $_SESSION['msgerror'] = "Error: Solicitud de actualización inválida.";
            ob_end_clean();
            header("Location: ".base_url."Cuenta/index");
            exit();
        }
    }
}
