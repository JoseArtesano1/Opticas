<?php 

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\ServiciosController;
use Controllers\TecnicosController;
use Controllers\CitasController;
use Controllers\CitasFecha;
use Controllers\PaginasController;
use Controllers\LoginController;

$router= new Router();
//asignar a las rutas las funciones ROUTER + CONTROLADOR
//ZONA PRIVADA
$router->get('/admin', [ServiciosController::class, 'index']);   //en la pag tenemos clase con la funcion
$router->get('/servicios/crear', [ServiciosController::class, 'crear']);
$router->post('/servicios/crear', [ServiciosController::class, 'crear']);
$router->get('/servicios/actualizar', [ServiciosController::class, 'actualizar']);
$router->post('/servicios/actualizar', [ServiciosController::class, 'actualizar']);
$router->post('/servicios/eliminar', [ServiciosController::class, 'eliminar']);


$router->post('/paginas/eliminar', [PaginasController::class, 'eliminarUsuario']);


$router->get('/tecnicos/crear', [TecnicosController::class, 'crearTecnico']);
$router->post('/tecnicos/crear', [TecnicosController::class, 'crearTecnico']);
$router->get('/tecnicos/actualizar', [TecnicosController::class, 'actualizarTecnico']); 
$router->post('/tecnicos/actualizar', [TecnicosController::class, 'actualizarTecnico']);
$router->post('/tecnicos/eliminar', [TecnicosController::class, 'eliminarTecnico']);

$router->get('/cita', [CitasController::class, 'crearCita']);
$router->post('/cita', [CitasController::class, 'crearCita']);
$router->get('/resumen-citas', [CitasController::class, 'mostrar']);
$router->post('/resumen-citas', [CitasController::class, 'mostrar']);
$router->post('/cita/eliminarCita', [CitasController::class, 'eliminarCita']);
$router->get('/citasFechaaXz1Ha', [CitasFecha::class, 'citaPor']);  //para eventos de la fecha
$router->post('/citasFechaaXz1Ha', [CitasFecha::class, 'citaPor']); // enviamos los datos a esta url
$router->get('/servidor', [CitasFecha::class, 'citaServicio']);


//ZONA PUBLICA
$router->get('/',[PaginasController::class,'index']);
$router->get('/alta',[PaginasController::class,'alta']);
$router->post('/alta',[PaginasController::class,'alta']);
$router->get('/condicion',[PaginasController::class, 'informar']);
$router->get('/confirmar-cuenta',[PaginasController::class, 'confirmar']);
$router->get('/recuperar-cuenta',[LoginController::class, 'recuperar']);
$router->post('/recuperar-cuenta',[LoginController::class, 'recuperar']);


//LOGIN Y AUTENTICAR
$router->get('/login', [LoginController::class, 'login']);
$router->post('/login', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);
$router->get('/actualizarUser', [LoginController::class, 'modificarUsuario']);
$router->post('/actualizarUser', [LoginController::class, 'modificarUsuario']);



$router->comprobarRutas();