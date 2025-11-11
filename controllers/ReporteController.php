<?php
require_once 'models/transaccion.php';

class ReporteController {

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
     * Acción Principal: Muestra el reporte de gastos por categoría
     */
    public function index() {
        $this->checkLogin();
        
        // 1. Cargar el modelo
        $transaccion_model = new Transaccion();
        $id_usuario = $_SESSION['identity']->id_usuario;

        // 2. Llamar a la nueva función de reporte
        $datos_reporte = $transaccion_model->getGastosPorCategoria($id_usuario);

        // 3. Cargar la vista y pasarle los datos
        require_once 'views/reporte/index.php';
    }

}
?>