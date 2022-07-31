<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class loginController
{
    public static function login(Router $router)
    {
        $auth = new Usuario();
        $alertas = Usuario::getAlertas();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);

            $alertas = $auth->validarIniciarSession();

            if (empty($alertas)) {
                // Validar si el email existe
                $usuario = Usuario::where('email', $auth->email);

                if ($usuario) {
                    // Vericar Password
                    if ($usuario->comprobarPasswordAndVerificado($auth->password)) {
                        // Autenticar el Usuario
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Rediccionamiento
                        if ($usuario->admin === '1') {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('location: /admin');
                        } else {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('location: /cita');
                        }
                    }
                } else {
                    Usuario::setAlerta('error', 'El Email no esta Reguistrado');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/login', [
            'alertas' => $alertas,
            'auth' => $auth
        ]);
    }

    public static function logout()
    {
        session_start();
        $_SESSION = [];

        header('location: /');
    }

    public static function olvide(Router $router)
    {
        $alertas = Usuario::getAlertas();
        $auth = new Usuario();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)) {
                // Validar que el email exista
                $usuario = $auth::where('email', $auth->email);

                if($usuario && $usuario->confirmado === '1') {
                    // Generamos un token
                    $usuario->generarToken();
                    $usuario->guardar();

                    // Enviamos un email con para recuperar el password
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    Usuario::setAlerta('exito', 'Revisa tu email');
                } else {
                    Usuario::setAlerta('error', 'El Usuario no existe o no esta Confirmado');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide-password', [
            'alertas' => $alertas,
            'auth' => $auth
        ]);
    }

    public static function recuperar(Router $router)
    {
        $alertas = Usuario::getAlertas();
        $error = false;

        $token = s($_GET['token']);

        // Buscar usuario por su token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token no valido');
            $error = true;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Leer el nuevo password y guardarlo
            $password = new Usuario($_POST);

            // validacion
            $alertas = $password->validarPassword();

            if(empty($alertas)) {
                $usuario->password = null;

                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;

                $resultado = $usuario->guardar();
                if($resultado) {
                    header('location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router)
    {
        $usuario = new Usuario();
        $alertas = Usuario::getAlertas();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            if (empty($alertas)) {
                // Verificar que el usuario no este reguistrado
                $resultado = $usuario->existeUsuario();

                if ($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    // Hashear el Password
                    $usuario->hashPassword();

                    // Generar un Token unico
                    $usuario->generarToken();

                    // Enviar el email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion();

                    // Crear usuario
                    $resultado = $usuario->guardar();
                    if ($resultado) {
                        header('location: /mensaje');
                    }
                }
            }
        }

        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function confirmar(Router $router)
    {
        $alertas = Usuario::getAlertas();

        $token = s($_GET['token']);

        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            // Mostrar mensaje de error
            Usuario::setAlerta('error', "Token es Incorrecto");
        } else {
            // Modificar a usuario confirmado
            $usuario->confirmado = "1";
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta('exito', "Cuenta Comprobada Correctamente");
        }

        // Obtener Alertas
        $alertas = Usuario::getAlertas();

        // Renderizar la Vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router)
    {
        $router->render('auth/mensaje');
    }
}
