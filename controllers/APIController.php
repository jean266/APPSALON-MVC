<?php

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use MVC\Router;
use Model\Servicio;

class APIController
{
    public static function index(Router $router)
    {
        $servicios = Servicio::all();
        echo json_encode($servicios);
    }

    public static function guardar(Router $router)
    {

        // // Almacena la cita y devuelve el ID
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();
        $id = $resultado['id'];

        // Almacena la cita y el servicio
        $idServicios = explode(',', $_POST['servicios']);
        foreach ($idServicios as $idServicio) {
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }
        echo json_encode(["resultado" => $resultado]);
    }

    public static function eliminar()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cita = Cita::find($_POST['id']);
            $cita->eliminar();
            header('location: ' . $_SERVER['HTTP_REFERER']);
        }
    }
}
