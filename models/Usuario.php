<?php

namespace Model;

class Usuario extends ActiveRecord
{
    // Base de Datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'telefono', 'password', 'email', 'admin', 'confirmado', 'token'];

    public $id;
    public $nombre;
    public $apellido;
    public $telefono;
    public $password;
    public $email;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? null;
    }

    // Mensajes de validación para la creación de una cuenta
    public function validarNuevaCuenta()
    {
        if (!$this->nombre) {
            self::$alertas['error'][] = "El Nombre del Cliente es Obligatorio";
        }
        if (!$this->apellido) {
            self::$alertas['error'][] = "El Apellido del Cliente es Obligatorio";
        }
        if (!$this->telefono) {
            self::$alertas['error'][] = "El Telefono del Cliente es Obligatorio";
        }
        if (!$this->password) {
            self::$alertas['error'][] = "El Password es Obligatorio";
        }
        if (!$this->email) {
            self::$alertas['error'][] = "El E-mail del Cliente es Obligatorio";
        }

        return self::$alertas;
    }

    // Mensajes de validacion para iniciar sesion
    public function validarIniciarSession()
    {
        if (!$this->email) {
            self::$alertas['error'][] = "El E-mail es Obligatorio";
        }
        if (!$this->password) {
            self::$alertas['error'][] = "El Password es Obligatorio";
        }

        return self::$alertas;
    }

    // Validacion para recuperar el password
    public function validarEmail()
    {
        if (!$this->email) {
            self::$alertas['error'][] = "El E-mail es obligatorio";
        }

        return self::$alertas;
    }

    // Valida que el campo password
    public function validarPassword()
    {
        if (!$this->password) {
            self::$alertas['error'][] = "El Password es Obligatorio";
        }
        if ($this->password < 6) {
            self::$alertas['error'][] = "El Password debe tener almenos 6 caracteres";
        }

        return self::$alertas;
    }

    // Valida que el usuario no exista 
    public function existeUsuario()
    {
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";
        $resultado = self::$db->query($query);

        if ($resultado->num_rows) {
            self::$alertas['error'][] = "El E-mail ya esta Reguistrado";
        }

        return $resultado;
    }

    // Hashea el password
    public function hashPassword()
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    // Genera el token
    public function generarToken()
    {
        $this->token = uniqid();
    }

    // Verifica que el password sea correcto y este verificado
    public function comprobarPasswordAndVerificado($password)
    {
        $resultado = password_verify($password, $this->password);

        if (!$resultado || !$this->confirmado) {
            self::$alertas['error'][] = 'Password Incorrecto o tu Cuenta no ha sido Confirmada';
        } else {
            return true;
        }
    }
}
