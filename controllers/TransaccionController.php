<?php
// controllers/TransaccionController.php
require_once 'models/cuenta.php';
require_once 'models/categoria.php'; 
require_once 'models/metodoPago.php'; 
require_once 'models/transaccion.php';

class TransaccionController {

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
    /**
     * Acción Principal: Muestra el listado de TODAS las transacciones.
     */
    public function index() {
        $this->checkLogin(); 

        $transaccion_model = new Transaccion();
        $id_usuario = $_SESSION['identity']->id_usuario;
        $transacciones = $transaccion_model->getAllByUserId($id_usuario); 

        // Cargar la vista del listado
        require_once 'views/transaccion/index.php'; 
    }

    /**
     * Acción: Muestra el formulario para crear un GASTO
     */
    public function crear() {
        $this->checkLogin();
        
        // Variable para que la vista sepa qué título mostrar
        $tipo_tx = 'GASTO'; 

        // 1. Cargar las cuentas del usuario (para el <select>)
        $cuenta_model = new Cuenta();
        $cuenta_model->setIdUsuario($_SESSION['identity']->id_usuario);
        $cuentas = $cuenta_model->getAllByUserId();

        // 2. Cargar las categorías (de tipo GASTO)
        $categoria_model = new Categoria();
        $categorias = $categoria_model->getAllPrincipales('GASTO');

        // 3. Cargar los métodos de pago
        $metodo_model = new MetodoPago();
        $metodos = $metodo_model->getAll();

        // Cargar la vista del formulario
        require_once 'views/transaccion/crear.php';
    }

    /**
     * Acción: Muestra el formulario para crear un INGRESO
     */
    public function crearIngreso() {
        $this->checkLogin();
        
        $tipo_tx = 'INGRESO';

        // 1. Cargar las cuentas del usuario
        $cuenta_model = new Cuenta();
        $cuenta_model->setIdUsuario($_SESSION['identity']->id_usuario);
        $cuentas = $cuenta_model->getAllByUserId();

        // 2. Cargar las categorías (de tipo INGRESO)
        $categoria_model = new Categoria();
        $categorias = $categoria_model->getAllPrincipales('INGRESO');
        
        // 3. Cargar los métodos de pago (son los mismos)
        $metodo_model = new MetodoPago();
        $metodos = $metodo_model->getAll();

        // Cargar la vista del formulario
        require_once 'views/transaccion/crear.php';
    }
    
    /**
     * Acción: Guarda la transacción (Gasto o Ingreso)
     */
    public function save() {
        $this->checkLogin();

        if (isset($_POST)) {
            // 1. Recoger datos básicos
            $tipo = $_POST['tipo'] ?? null;
            $monto = $_POST['monto'] ?? null;
            $descripcion = $_POST['descripcion'] ?? null;
            $fecha = $_POST['fecha'] ?? null;
            $id_cuenta = $_POST['cuenta'] ?? null;
            $id_categoria_principal = $_POST['categoria'] ?? null;
            $id_metodo = $_POST['metodo'] ?? null; // Será null si es Ingreso
            $id_usuario = $_SESSION['identity']->id_usuario;

            // Validar que los campos principales existan
            if (!$tipo || !$monto || !$descripcion || !$fecha || !$id_cuenta || !$id_categoria_principal) {
                $_SESSION['msgerror'] = "Faltan datos obligatorios en el formulario.";
                require_once 'views/error.php';
                exit();
            }

            // 2. TRUCO: Convertir ID de Categoría a ID de Subcategoría
            $categoria_model = new Categoria();
            $id_subcategoria = $categoria_model->getFirstSubcategoriaID($id_categoria_principal);

            if ($id_subcategoria === 0) {
                $_SESSION['msgerror'] = "Error: La categoría seleccionada no tiene subcategorías.";
                require_once 'views/error.php';
                exit();
            }

            // 3. Crear y guardar el objeto Transaccion
            $transaccion = new Transaccion();
            $transaccion->setMonto($monto);
            $transaccion->setTipo($tipo);
            $transaccion->setDescripcion($descripcion);
            $transaccion->setFecha($fecha);
            $transaccion->setIdUsuario($id_usuario);
            $transaccion->setIdCuenta($id_cuenta);
            $transaccion->setIdSubcategoria($id_subcategoria); // <-- Guardamos la subcategoría
            $transaccion->setIdMetodo($id_metodo); // Será null si es Ingreso

            $save = $transaccion->save();

            // 4. Redirigir
            if ($save) {
                $_SESSION['msgsuccess'] = "Transacción guardada con éxito.";
                ob_end_clean();
                header("Location: ".base_url); // Redirigir al Dashboard
                exit();
            } else {
                $_SESSION['msgerror'] = "Error al guardar la transacción en la base de datos.";
                require_once 'views/error.php';
                exit();
            }

        } else {
            $_SESSION['msgerror'] = "No se recibieron datos del formulario.";
            require_once 'views/error.php';
        }
    }
    /**
     * Acción: Elimina una transacción por ID
     */
    public function eliminar() {
        $this->checkLogin();

        // 1. Validar que nos pasen el ID por la URL
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $id_usuario = $_SESSION['identity']->id_usuario;

            // 2. Cargar el modelo
            // (El 'require_once' ya debería estar en la parte superior)
            $transaccion = new Transaccion();
            $transaccion->setId($id);
            $transaccion->setIdUsuario($id_usuario); // Para seguridad

            // 3. Llamar al método de borrado (que crearemos en el Paso 4)
            $delete = $transaccion->delete();

            if ($delete) {
                $_SESSION['msgsuccess'] = "Transacción eliminada correctamente.";
            } else {
                $_SESSION['msgerror'] = "Error: No se pudo eliminar la transacción (quizás no te pertenece).";
            }

            // 4. Redirigir de vuelta al historial
            ob_end_clean();
            header("Location: ".base_url."Transaccion/index");
            exit();

        } else {
            $_SESSION['msgerror'] = "Error: No se especificó ninguna transacción para eliminar.";
            ob_end_clean();
            header("Location: ".base_url."Transaccion/index");
            exit();
        }
    }
    /**
     * Acción: Muestra el formulario para EDITAR una transacción
     */
    public function editar() {
        $this->checkLogin();

        // 1. Validar que nos pasen el ID
        if (!isset($_GET['id'])) {
            header("Location: ".base_url."Transaccion/index");
            exit();
        }

        $id_transaccion = (int)$_GET['id'];
        $id_usuario = $_SESSION['identity']->id_usuario;

        // 2. Cargar los datos de la transacción
        $transaccion_model = new Transaccion();
        $tx = $transaccion_model->getOneById($id_transaccion, $id_usuario);

        // Si no existe o no pertenece al usuario, redirigir
        if (!$tx) {
            $_SESSION['msgerror'] = "Error: La transacción no existe.";
            ob_end_clean();
            header("Location: ".base_url."Transaccion/index");
            exit();
        }

        // 3. Cargar los datos para los <select> (igual que en crear/crearIngreso)
        $tipo_tx = $tx['tipo_transaccion']; // 'GASTO' o 'INGRESO'
        
        $cuenta_model = new Cuenta();
        $cuenta_model->setIdUsuario($id_usuario);
        $cuentas = $cuenta_model->getAllByUserId();

        $categoria_model = new Categoria();
        $categorias = $categoria_model->getAllPrincipales($tipo_tx);

        $metodo_model = new MetodoPago();
        $metodos = $metodo_model->getAll();

        // 4. Cargar la vista de edición (crearemos esta vista)
        require_once 'views/transaccion/editar.php';
    }
    /**
     * Acción: Recibe los datos (POST) del formulario de edición y actualiza la BD
     */
    public function update() {
        $this->checkLogin();

        // 1. Validar que tengamos un ID de transacción y datos POST
        if (isset($_GET['id']) && isset($_POST)) {
            
            // 2. Recoger datos básicos
            $id_transaccion = (int)$_GET['id'];
            $tipo = $_POST['tipo'] ?? null;
            $monto = $_POST['monto'] ?? null;
            $descripcion = $_POST['descripcion'] ?? null;
            $fecha = $_POST['fecha'] ?? null;
            $id_cuenta = $_POST['cuenta'] ?? null;
            $id_categoria_principal = $_POST['categoria'] ?? null;
            $id_metodo = $_POST['metodo'] ?? null; // Será null si es Ingreso
            $id_usuario = $_SESSION['identity']->id_usuario;

            // 3. Validar campos
            if (!$tipo || !$monto || !$descripcion || !$fecha || !$id_cuenta || !$id_categoria_principal) {
                $_SESSION['msgerror'] = "Faltan datos obligatorios en el formulario.";
                require_once 'views/error.php';
                exit();
            }

            // 4. Convertir ID de Categoría a ID de Subcategoría (el mismo truco)
            // (El 'require_once' de 'categoria.php' ya está arriba)
            $categoria_model = new Categoria();
            $id_subcategoria = $categoria_model->getFirstSubcategoriaID($id_categoria_principal);

            if ($id_subcategoria === 0) {
                $_SESSION['msgerror'] = "Error: La categoría seleccionada no tiene subcategorías.";
                require_once 'views/error.php';
                exit();
            }

            // 5. Crear y actualizar el objeto Transaccion
            $transaccion = new Transaccion();
            $transaccion->setId($id_transaccion); // <-- El ID de la transacción a EDITAR
            $transaccion->setMonto($monto);
            $transaccion->setTipo($tipo);
            $transaccion->setDescripcion($descripcion);
            $transaccion->setFecha($fecha);
            $transaccion->setIdUsuario($id_usuario); // <-- Para seguridad
            $transaccion->setIdCuenta($id_cuenta);
            $transaccion->setIdSubcategoria($id_subcategoria);
            $transaccion->setIdMetodo($id_metodo);

            // 6. Llamar al método de actualización (que crearemos en el Paso 6)
            $update = $transaccion->update();

            // 7. Redirigir
            if ($update) {
                $_SESSION['msgsuccess'] = "Transacción actualizada con éxito.";
            } else {
                $_SESSION['msgerror'] = "Error al actualizar la transacción.";
            }
            
            ob_end_clean();
            header("Location: ".base_url."Transaccion/index"); // Redirigir al historial
            exit();

        } else {
            $_SESSION['msgerror'] = "Error: Solicitud de actualización inválida.";
            ob_end_clean();
            header("Location: ".base_url."Transaccion/index");
            exit();
        }
    }

}
?>