<?php
// models/Categoria.php
require_once 'config/conexion.php';

class Categoria {
    private $db;

    public function __construct() {
        $this->db = Connection::connect();
    }

    /**
     * Obtiene todas las categorías principales (GASTO o INGRESO)
     */
    public function getAllPrincipales($tipo) {
        $categorias = [];
        // Usamos '?' para prevenir inyección SQL
        $stmt = $this->db->prepare("SELECT * FROM categorias WHERE tipo_categoria = ? ORDER BY nombre_categoria ASC");
        $stmt->bind_param("s", $tipo);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $categorias[] = $row;
            }
            $res->close();
        }
        $stmt->close();
        return $categorias;
    }

    /**
     * Obtiene las subcategorías de una categoría principal
     */
    public function getSubcategorias($id_categoria_padre) {
        $subcategorias = [];
        $stmt = $this->db->prepare("SELECT * FROM subcategorias WHERE id_categoria_subcategoria = ? ORDER BY nombre_subcategoria ASC");
        $stmt->bind_param("i", $id_categoria_padre);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $subcategorias[] = $row;
            }
            $res->close();
        }
        $stmt->close();
        return $subcategorias;
    }
    /**
     * Obtiene el ID de la PRIMERA subcategoría de una categoría padre
     * (Helper para el formulario de guardar)
     */
    public function getFirstSubcategoriaID($id_categoria_padre) {
        $stmt = $this->db->prepare("SELECT id_subcategoria FROM subcategorias WHERE id_categoria_subcategoria = ? LIMIT 1");
        $stmt->bind_param("i", $id_categoria_padre);
        $stmt->execute();
        $res = $stmt->get_result();
        
        if ($res && $res->num_rows === 1) {
            $row = $res->fetch_assoc();
            $stmt->close();
            return (int)$row['id_subcategoria'];
        }
        
        $stmt->close();
        // Fallback: si no encuentra una, usamos un ID genérico (o creamos uno)
        // Por ahora, asumimos que siempre encontrará una
        return 0; 
    }
}
?>