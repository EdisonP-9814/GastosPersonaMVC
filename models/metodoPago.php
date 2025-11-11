<?php
// models/MetodoPago.php
require_once 'config/conexion.php';

class MetodoPago {
    private $db;

    public function __construct() {
        $this->db = Connection::connect();
    }

    /**
     * Obtiene todos los métodos de pago
     */
    public function getAll() {
        $metodos = [];
        $sql = "SELECT * FROM metodos_pago ORDER BY nombre_metodo ASC";
        $res = $this->db->query($sql);

        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $metodos[] = $row;
            }
            $res->close();
        }
        return $metodos;
    }
}
?>