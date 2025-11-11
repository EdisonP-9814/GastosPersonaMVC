<?php
// models/cuenta.php
require_once 'config/conexion.php';

class Cuenta {
    private $id;
    private $id_usuario;
    private $nombre;
    private $tipo;
    private $saldo_inicial;
    private $db;

    public function __construct() {
        $this->db = Connection::connect();
    }

    /* ==================================================
     metodos Gettters 
    ==================================================
    */
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getTipo() { return $this->tipo; }
    public function getSaldoInicial() { return $this->saldo_inicial; }
    public function getIdUsuario() { return $this->id_usuario; }
    
    /* ==================================================
     metodos Setters 
    ==================================================
    */
    public function setId($id) { 
        $this->id = (int)$id; 
    }
    public function setNombre($nombre) { 
        $this->nombre = trim($nombre);
    }
    public function setTipo($tipo) { 
        $this->tipo = trim($tipo); 
    }
    public function setSaldoInicial($saldo_inicial) { 
        $this->saldo_inicial = (float)$saldo_inicial;
    }
    public function setIdUsuario($id_usuario) { 
        $this->id_usuario = (int)$id_usuario; 
    }

    /* ==================================================
     CRUD y Métodos de Lógica
    ==================================================
    */

    // Insertar una nueva cuenta
    public function save() {
        
        $sql = "INSERT INTO cuentas (nombre_cuenta, tipo_cuenta, saldo_inicial_cuenta, id_usuario_cuenta) VALUES (?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->bind_param("ssdi", 
            $this->nombre, 
            $this->tipo, 
            $this->saldo_inicial,
            $this->id_usuario
        );
        
        $save_ok = $stmt->execute();
        $stmt->close();
        
        return $save_ok;
    }

    // Obtener todas las cuentas de un usuario
    public function getAllByUserId() {
        $cuentas = [];
        if (empty($this->id_usuario)) {
            return $cuentas;
        }
        $stmt = $this->db->prepare("SELECT * FROM cuentas WHERE id_usuario_cuenta = ? ORDER BY nombre_cuenta ASC");
        $stmt->bind_param("i", $this->id_usuario);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res) {
            while ($row = $res->fetch_assoc()) {
                // Se agrega el saldo actual calculado
                $row['saldo_actual'] = $this->getCurrentBalance($row['id_cuenta']);
                $cuentas[] = $row;
            }
            $res->close();
        }
        $stmt->close();
        return $cuentas;
    }

    // Calcula el saldo actual de una cuenta (saldo inicial + transacciones)
    public function getCurrentBalance($cuenta_id) {
        $balance = 0.00;

        // 1. Obtener saldo inicial
        $stmt_init = $this->db->prepare("SELECT saldo_inicial_cuenta FROM cuentas WHERE id_cuenta = ? AND id_usuario_cuenta = ? LIMIT 1");
        $stmt_init->bind_param("ii", $cuenta_id, $this->id_usuario);
        $stmt_init->execute();
        $res_init = $stmt_init->get_result();
        if ($res_init && $res_init->num_rows === 1) {
            $balance = (float)$res_init->fetch_assoc()['saldo_inicial_cuenta'];
        }
        $stmt_init->close();

        // 2. Sumar transacciones de INGRESO y restar transacciones de GASTO
        // Nota: Asume que ya existe la tabla 'transacciones'
        $stmt_tx = $this->db->prepare("SELECT monto_transaccion, tipo_transaccion FROM transacciones WHERE id_cuenta_transaccion = ? AND id_usuario_transaccion = ?");
        $stmt_tx->bind_param("ii", $cuenta_id, $this->id_usuario);
        $stmt_tx->execute();
        $res_tx = $stmt_tx->get_result();

        if ($res_tx) {
            while ($row = $res_tx->fetch_assoc()) {
                $monto = (float)$row['monto_transaccion'];
                if ($row['tipo_transaccion'] === 'INGRESO') {
                    $balance += $monto;
                } elseif ($row['tipo_transaccion'] === 'GASTO') {
                    $balance -= $monto;
                }
            }
            $res_tx->close();
        }
        $stmt_tx->close();

        return $balance;
    }
    /**
     * Verifica si una cuenta tiene transacciones asociadas.
     * Devuelve true si SÍ tiene transacciones.
     */
    private function tieneTransacciones($id_cuenta) {
        $stmt = $this->db->prepare("SELECT id_transaccion FROM transacciones WHERE id_cuenta_transaccion = ? LIMIT 1");
        $stmt->bind_param("i", $id_cuenta);
        $stmt->execute();
        $stmt->store_result();
        $count = $stmt->num_rows;
        $stmt->close();
        return $count > 0;
    }

    /**
     * Elimina una cuenta de la base de datos.
     * Solo borra si el ID de usuario coincide Y no tiene transacciones.
     */
    public function delete() {
        // 1. Verificar si tiene transacciones
        if ($this->tieneTransacciones($this->id)) {
            return false; // No se puede borrar, tiene transacciones
        }

        // 2. Si no tiene, proceder a borrar
        $sql = "DELETE FROM cuentas WHERE id_cuenta = ? AND id_usuario_cuenta = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", 
            $this->id,
            $this->id_usuario
        );
        
        $delete_ok = $stmt->execute();
        $filas_afectadas = $this->db->affected_rows; 
        $stmt->close();
        
        return $delete_ok && $filas_afectadas > 0;
    }
    /**
     * Obtiene los datos de una cuenta específica por su ID
     * Solo la devuelve si pertenece al usuario (por seguridad)
     */
    public function getOneById() {
        $sql = "SELECT * FROM cuentas WHERE id_cuenta = ? AND id_usuario_cuenta = ? LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $this->id, $this->id_usuario);
        $stmt->execute();
        $res = $stmt->get_result();

        $cuenta = null;
        if ($res && $res->num_rows === 1) {
            $cuenta = $res->fetch_assoc();
        }
        
        $res->close();
        $stmt->close();
        return $cuenta;
    }

    /**
     * Actualiza una cuenta en la base de datos
     * Solo actualiza si el ID de usuario coincide (por seguridad)
     */
    public function update() {
        $sql = "UPDATE cuentas SET 
                    nombre_cuenta = ?, 
                    tipo_cuenta = ?
                WHERE 
                    id_cuenta = ? AND id_usuario_cuenta = ?";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->bind_param("ssii", 
            $this->nombre,
            $this->tipo,
            $this->id, // ID de la cuenta
            $this->id_usuario // ID del usuario (para seguridad)
        );
        
        $update_ok = $stmt->execute();
        $stmt->close();
        
        return $update_ok;
    }
}
?>