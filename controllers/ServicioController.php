<?php

namespace Controllers;

use Model\Servicio;
use MVC\Router;

class ServicioController
{
    public static function index(Router $router)
    {
        isAdmin();

        $servicios = Servicio::all();

        $router->render('servicios/index', [
            'nombre' => $_SESSION['nombre'],
            'servicios' => $servicios
        ]);
    }

    public static function crear(Router $router)
    {
        isAdmin();

        $servicio = new Servicio();
        $alertas = Servicio::getAlertas();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Instaciar clase
            $servicio->sincronizar($_POST);

            // Validacion
            $alertas = $servicio->validar();

            if (empty($alertas)) {
                $resultado = $servicio->guardar();

                if ($resultado) {
                    header('location: /servicios');
                }
            }
        }

        $router->render('servicios/crear', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function actualizar(Router $router)
    {
        isAdmin();

        if(!is_numeric($_GET['id'])) {
            header('location: /servicios');
        }
        $servicio = Servicio::find($_GET['id']);
        $alertas = Servicio::getAlertas();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST);
            // Validacion
            $alertas = $servicio->validar();

            if (empty($alertas)) {
                $resultado = $servicio->guardar();

                if ($resultado) {
                    header('location: /servicios');
                }
            }
        }

        $router->render('servicios/actualizar', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function eliminar()
    {
        isAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio = Servicio::find($_POST['id']);
            $resultado = $servicio->eliminar();

            if($resultado) {
                header('location: /servicios');
            }
        }
    }
}
