<?php
// models/transaccion.php
require_once 'config/conexion.php';

class Transaccion {
    private $id;
    private $monto;
    private $tipo;
    private $descripcion;
    private $fecha;
    private $id_usuario;
    private $id_cuenta;
    private $id_subcategoria;
    private $id_metodo;
    private $db;

    public function __construct() {
        $this->db = Connection::connect();
    }

    // --- Getters y Setters ---
    public function setId($id) { $this->id = $id; }
    public function setMonto($monto) { $this->monto = (float)$monto; }
    public function setTipo($tipo) { $this->tipo = $tipo; }
    public function setDescripcion($desc) { $this->descripcion = trim($desc); }
    public function setFecha($fecha) { $this->fecha = $fecha; }
    public function setIdUsuario($id) { $this->id_usuario = (int)$id; }
    public function setIdCuenta($id) { $this->id_cuenta = (int)$id; }
    public function setIdSubcategoria($id) { $this->id_subcategoria = (int)$id; }
    public function setIdMetodo($id) { $this->id_metodo = (int)$id; }

    
    /**
     * Guarda la transacción en la base de datos
     */
    public function save() {
        
        $sql = "INSERT INTO transacciones (monto_transaccion, tipo_transaccion, descripcion_transaccion, fecha_transaccion, id_usuario_transaccion, id_cuenta_transaccion, id_subcategoria_transaccion, id_metodo_transaccion) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        
        // El método de pago puede ser nulo (para Ingresos)
        if (empty($this->id_metodo)) {
            $this->id_metodo = null;
        }

        $stmt->bind_param("dsssiiii", 
            $this->monto,
            $this->tipo,
            $this->descripcion,
            $this->fecha,
            $this->id_usuario,
            $this->id_cuenta,
            $this->id_subcategoria,
            $this->id_metodo
        );
        
        $save_ok = $stmt->execute();
        $stmt->close();
        
        return $save_ok;
    }

    /**
     * Obtiene las transacciones recientes de un usuario, uniendo tablas
     */
    public function getRecentByUserId($id_usuario, $limit = 10) {
        $transacciones = [];
        
        $sql = "SELECT
                    t.id_transaccion,
                    t.monto_transaccion, 
                    t.tipo_transaccion, 
                    t.descripcion_transaccion, 
                    t.fecha_transaccion,
                    c.nombre_cuenta,
                    sc.nombre_subcategoria,
                    cat.nombre_categoria
                FROM transacciones t
                JOIN cuentas c ON t.id_cuenta_transaccion = c.id_cuenta
                JOIN subcategorias sc ON t.id_subcategoria_transaccion = sc.id_subcategoria
                JOIN categorias cat ON sc.id_categoria_subcategoria = cat.id_categoria
                WHERE t.id_usuario_transaccion = ?
                ORDER BY t.fecha_transaccion DESC, t.id_transaccion DESC
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $id_usuario, $limit);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $transacciones[] = $row;
            }
            $res->close();
        }
        $stmt->close();
        return $transacciones;
    }

    /**
     * Obtiene TODAS las transacciones de un usuario, uniendo tablas
     */
    public function getAllByUserId($id_usuario) {
        $transacciones = [];
        
        $sql = "SELECT
                    t.id_transaccion,
                    t.monto_transaccion, 
                    t.tipo_transaccion, 
                    t.descripcion_transaccion, 
                    t.fecha_transaccion,
                    c.nombre_cuenta,
                    sc.nombre_subcategoria,
                    cat.nombre_categoria
                FROM transacciones t
                JOIN cuentas c ON t.id_cuenta_transaccion = c.id_cuenta
                JOIN subcategorias sc ON t.id_subcategoria_transaccion = sc.id_subcategoria
                JOIN categorias cat ON sc.id_categoria_subcategoria = cat.id_categoria
                WHERE t.id_usuario_transaccion = ?
                ORDER BY t.fecha_transaccion DESC, t.id_transaccion DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $transacciones[] = $row;
            }
            $res->close();
        }
        $stmt->close();
        return $transacciones;
    }
    /**
     * Elimina una transacción de la base de datos
     * Solo borra si el ID de usuario coincide (por seguridad)
     */
    public function delete() {
        // Obtenemos el ID de la transacción y el ID de usuario (setteados desde el controlador)
        $id_transaccion = $this->id;
        $id_usuario_check = $this->id_usuario;

        $sql = "DELETE FROM transacciones WHERE id_transaccion = ? AND id_usuario_transaccion = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", 
            $id_transaccion,
            $id_usuario_check
        );
        
        $delete_ok = $stmt->execute();
        
        // affected_rows nos asegura que SÍ borró algo (que el ID existía y pertenecía al usuario)
        $filas_afectadas = $this->db->affected_rows; 
        
        $stmt->close();
        
        return $delete_ok && $filas_afectadas > 0;
    }
    /**
     * Obtiene una transacción específica por su ID
     * Solo la devuelve si pertenece al usuario (por seguridad)
     */
    public function getOneById($id_transaccion, $id_usuario) {
        $sql = "SELECT 
                    t.*, 
                    sc.id_categoria_subcategoria AS id_categoria_principal
                FROM transacciones t
                JOIN subcategorias sc ON t.id_subcategoria_transaccion = sc.id_subcategoria
                WHERE t.id_transaccion = ? AND t.id_usuario_transaccion = ?
                LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $id_transaccion, $id_usuario);
        $stmt->execute();
        $res = $stmt->get_result();

        $transaccion = null;
        if ($res && $res->num_rows === 1) {
            $transaccion = $res->fetch_assoc();
        }
        
        $res->close();
        $stmt->close();
        return $transaccion;
    }
   /**
     * Actualiza una transacción en la base de datos
     * Solo actualiza si el ID de usuario coincide (por seguridad)
     */
    public function update() {
        
        $sql = "UPDATE transacciones SET 
                    monto_transaccion = ?, 
                    tipo_transaccion = ?, 
                    descripcion_transaccion = ?, 
                    fecha_transaccion = ?, 
                    id_cuenta_transaccion = ?, 
                    id_subcategoria_transaccion = ?, 
                    id_metodo_transaccion = ?
                WHERE 
                    id_transaccion = ? AND id_usuario_transaccion = ?";
        
        $stmt = $this->db->prepare($sql);
        
        // El método de pago puede ser nulo (para Ingresos)
        if (empty($this->id_metodo)) {
            $this->id_metodo = null;
        }
        $stmt->bind_param("dsssiiiii", 
            $this->monto,
            $this->tipo,
            $this->descripcion,
            $this->fecha,
            $this->id_cuenta,
            $this->id_subcategoria,
            $this->id_metodo,
            $this->id,
            $this->id_usuario // ID del usuario (para seguridad)
        );
        
        $update_ok = $stmt->execute();
        $stmt->close();
        
        return $update_ok;
    }
    /**
     * Obtiene la suma total de gastos por categoría para un usuario.
     */
    public function getGastosPorCategoria($id_usuario) {
        $reporte = [];
        
        $sql = "SELECT 
                    cat.nombre_categoria,
                    SUM(t.monto_transaccion) AS total_gastado
                FROM transacciones t
                JOIN subcategorias sc ON t.id_subcategoria_transaccion = sc.id_subcategoria
                JOIN categorias cat ON sc.id_categoria_subcategoria = cat.id_categoria
                WHERE 
                    t.id_usuario_transaccion = ? 
                    AND t.tipo_transaccion = 'GASTO'
                GROUP BY 
                    cat.id_categoria, cat.nombre_categoria
                ORDER BY 
                    total_gastado DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $reporte[] = $row;
            }
            $res->close();
        }
        $stmt->close();
        return $reporte;
    }
}
?>