<?php
namespace Controllers;
use MVC\Router;

use Model\Citas;
use Model\Servicios;

class CitasFecha{

    public static function citaPor(Router $router){

        $cita=Citas:: obtenerCitas();
        echo json_encode($cita, JSON_UNESCAPED_UNICODE);
    }

    public static function citaServicio(Router $router){
        
        $citaxServ=Citas::obtenerCitasxServicio();
        echo json_encode($citaxServ, JSON_UNESCAPED_UNICODE); 
    }

}
