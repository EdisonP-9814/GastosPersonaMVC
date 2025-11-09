<?php
// models/usuario.php
require_once 'config/conexion.php';

class Usuario {
    private $id;
    private $cedula;
    private $nombre;
    private $email;
    private $password; // raw password while creating; DB stores hashed
    private $telefono;
    private $direccion;
    private $rol;
    private $db; // mysqli connection

    public function __construct() {
        $this->db = Connection::connect(); // debe devolver mysqli
    }

    /* ---------------- Getters ---------------- */
    public function getId()        { return $this->id; }
    public function getCedula()   { return $this->cedula; }
    public function getNombre()   { return $this->nombre; }
    public function getEmail()    { return $this->email; }
    // NO devolvemos la contraseña hasheada por un getter público
    public function getTelefono() { return $this->telefono; }
    public function getDireccion(){ return $this->direccion; }
    public function getRol()      { return $this->rol; }

    /* ---------------- Setters ---------------- */
    public function setId($id) {
        $this->id = (int)$id;
    }

    public function setCedula($cedula) {
        $this->cedula = trim($cedula);
    }

    public function setNombre($nombre) {
        $this->nombre = trim($nombre);
    }

    public function setEmail($email) {
        $this->email = filter_var(trim($email), FILTER_VALIDATE_EMAIL) ? trim($email) : null;
    }

    public function setPassword($password) {
        // Guardamos el password en crudo aquí; lo hashearemos en save()
        $this->password = $password;
    }

    public function setTelefono($telefono) {
        $this->telefono = trim($telefono);
    }

    public function setDireccion($direccion) {
        $this->direccion = trim($direccion);
    }

    public function setRol($rol) {
        $this->rol = (int)$rol;
    }

    /* ---------------- Helpers (existencia) ---------------- */

    // Devuelve true si ya existe usuario con ese email
    public function existsByEmail() {
        $stmt = $this->db->prepare("SELECT id_usuario FROM usuarios WHERE email_usuario = ? LIMIT 1");
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    // Devuelve true si ya existe usuario con esa cédula
    public function existsByCedula() {
        $stmt = $this->db->prepare("SELECT id_usuario FROM usuarios WHERE cedula_usuario = ? LIMIT 1");
        $stmt->bind_param("s", $this->cedula);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    /* ---------------- CRUD y autenticación ---------------- */

    // Inserta un usuario nuevo. Retorna true|false
    public function save() {
        // Validaciones básicas
        if (empty($this->nombre) || empty($this->email) || empty($this->password) || empty($this->cedula)) {
            return false;
        }

        // Verificar unicidad
        if ($this->existsByEmail() || $this->existsByCedula()) {
            return false;
        }

        // Hash de la contraseña
        $hashed = password_hash($this->password, PASSWORD_DEFAULT);

        // rol por defecto si no fue establecido (ajusta según tus roles)
        $role = $this->rol ? $this->rol : 2;

        $stmt = $this->db->prepare("INSERT INTO usuarios (cedula_usuario, nombre_usuario, email_usuario, telefono_usuario, direccion_usuario, clave_usuario, id_rol_usuario) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi",
            $this->cedula,
            $this->nombre,
            $this->email,
            $this->telefono,
            $this->direccion,
            $hashed,
            $role
        );

        $ok = $stmt->execute();
        if ($ok) {
            $this->id = $this->db->insert_id;
        }
        $stmt->close();
        return $ok;
    }

    // Login: asume que setEmail() y setPassword() ya fueron invocados
    // Retorna objeto usuario (sin password) o false
    public function login() {
        if (empty($this->email) || empty($this->password)) {
            return false;
        }

        $stmt = $this->db->prepare("SELECT id_usuario, cedula_usuario, nombre_usuario, email_usuario, telefono_usuario, direccion_usuario, clave_usuario, id_rol_usuario FROM usuarios WHERE email_usuario = ? LIMIT 1");
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows === 1) {
            $row = $res->fetch_assoc();
            // Verificamos la contraseña
            if (password_verify($this->password, $row['clave_usuario'])) {
                // Preparamos un objeto seguro para devolver (sin clave)
                $user = new stdClass();
                $user->id_usuario = (int)$row['id_usuario'];
                $user->cedula_usuario = $row['cedula_usuario'];
                $user->nombre_usuario = $row['nombre_usuario'];
                $user->email_usuario = $row['email_usuario'];
                $user->telefono_usuario = $row['telefono_usuario'];
                $user->direccion_usuario = $row['direccion_usuario'];
                $user->id_rol_usuario = (int)$row['id_rol_usuario'];

                $stmt->close();
                return $user;
            }
        }
        $stmt->close();
        return false;
    }

    // Obtener todos los usuarios
    public function getAll() {
        $users = [];
        $sql = "SELECT id_usuario, cedula_usuario, nombre_usuario, email_usuario, telefono_usuario, direccion_usuario, id_rol_usuario, fecha_registro_usuario FROM usuarios ORDER BY fecha_registro_usuario DESC";
        $res = $this->db->query($sql);
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $users[] = $row;
            }
            $res->close();
        }
        return $users;
    }

    // Obtener por id
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT id_usuario, cedula_usuario, nombre_usuario, email_usuario, telefono_usuario, direccion_usuario, id_rol_usuario, fecha_registro_usuario FROM usuarios WHERE id_usuario = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res && $res->num_rows === 1 ? $res->fetch_assoc() : null;
        $stmt->close();
        return $user;
    }
}
