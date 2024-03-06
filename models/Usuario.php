<?php

namespace Model;

class Usuario extends ActiveRecord {
    //Base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? ''; 
        $this->admin = $args['admin'] ?? 0; 
        $this->confirmado = $args['confirmado'] ?? 0; 
        $this->token = $args['token'] ?? ''; 
    }

    public function validarNuevaCuenta() {
        if (!$this->nombre) {
            self::$alertas['error'][] = "El Nombre es Obligatorio";
        }

        if (!$this->apellido) {
            self::$alertas['error'][] = "El Apellido es Obligatorio";
        }

        if (!$this->email) {
            self::$alertas['error'][] = "El E-Mail es Obligatorio";
        }else if (!preg_match('/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/', $this->email)) {
            self::$alertas['error'][] = "Formato de E-Mail no Válido";
        }

        if (!$this->password) {
            self::$alertas['error'][] = "El Password es Obligatorio";
        }

        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = "El Password debe de contener al menos 6 caracteres";
        }

        if (!$this->telefono) {
            self::$alertas['error'][] = "El Teléfono es Obligatorio";
        }else if (!preg_match('/[2|7]{1}[0-9]{3}-[0-9]{4}/', $this->telefono)) {
            self::$alertas['error'][] = "Formato de Teléfono no Válido";
        }

        return self::$alertas;
    }

    public function validarLogin() {
        if (!$this->email) {
            self::$alertas['error'][] = "El E-Mail es Obligatorio";
        }else if (!preg_match('/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/', $this->email)) {
            self::$alertas['error'][] = "Formato de E-Mail no Válido";
        }

        if(!$this->password) {
            self::$alertas['error'][] = 'El Password es Obligarotio';
        }

        return self::$alertas;
    }

    public function validarEmail() {
        if (!$this->email) {
            self::$alertas['error'][] = "El E-Mail es Obligatorio";
        }else if (!preg_match('/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/', $this->email)) {
            self::$alertas['error'][] = "Formato de E-Mail no Válido";
        }

        return self::$alertas;
    }

    public function validarPassword() {
        if (!$this->password) {
            self::$alertas['error'][] = "El Password es Obligatorio";
        }

        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = "El Password debe de contener al menos 6 caracteres";
        }

        return self::$alertas;
    }

    //Revisa si el usuario ya existe
    public function existeUsuario() {
        $query = " SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1 ";

        $resultado = self::$db->query($query);

        if($resultado->num_rows) {
            self::$alertas['error'][] = "El Usuario ya existe";
        }

       return $resultado;
    }

    //Hashear password
    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken() {
        $this->token = uniqid();
    }

    public function comprobrarPasswordAndVerificado($password) {
        $resultado = password_verify($password, $this->password);

        if(!$resultado || !$this->confirmado) {
            self::$alertas['error'][] = 'Password Incorrecto o tu cuenta no ha sido confirmada';
        } else {
            return true;
        }
    }
}