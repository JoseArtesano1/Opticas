<?php
namespace Controllers;
use MVC\Router;
use Model\Servicios; 
use Model\Citas;
use Model\Usuarios;
use PHPMailer\PHPMailer\PHPMailer;


class CitasController{
    

    public static function mostrar(Router $router){

         if(!isset($_SESSION)){
           session_start();
         }
           $emailPasado=$_SESSION['usuario'];
           $usuario=Usuarios::find($emailPasado);
           $horario=Citas::findAll($usuario->idUsuarios,'id_usuario',true);
           //crear una instancia phpMailer
           $mail= new PHPMailer();
           $mensaje=null;
           $resultado=$_GET['resultado'] ?? null; //mensaje de crear cita al guardar con exito

        if($_SERVER['REQUEST_METHOD']==='POST'){
          $id=$_POST['idCita'];
          $id=filter_var($id, FILTER_VALIDATE_INT);
          
           if($id){
             $cita=Citas::find($id);
 
             if($cita->fecha>=date("Y-m-d")){
              
              //convertir formato 
                $valor=  date("d/m/Y", strtotime($cita->fecha)); 
                $hora=date("H:i:s", strtotime($cita->hora)); 
                $servicio= Servicios::find($cita->id_servicio);
                $hora_finalCliente=SumaHoras($hora,$servicio->duracion);
                
               $mensaje=  correo($mail,$usuario->nombre, $usuario->telefono,$usuario->correo,$valor,$hora, $hora_finalCliente, $servicio->denominacion,false,true);
                $cita->eliminar($cita->idcitas, '/cita');
             } else{
                $cita->eliminar($cita->idcitas, '/cita');
             }
              
           }
                
        }

        $router->render('citas/resumen-citas', [
              'mensaje'=>$mensaje,
              'resultado'=>$resultado,
             'usuario'=>$usuario,
             'horario'=>$horario
        ]);
    } 

 
    public static function crearCita(Router $router){
 
            if(!isset($_SESSION)){
              session_start();
            }
                  
           $emailPasado=$_SESSION['usuario'];
           $fecha_actual = date("d-m-Y");

           $usuario=Usuarios::find($emailPasado);
           $cita= new Citas;
           $errores=Citas::getErrores();
           $servicios=Servicios::all();
           //crear una instancia phpMailer
           $mail= new PHPMailer();

           $existe=true;
           $mensajes=null;
          $mensaje=null;

        if($_SERVER['REQUEST_METHOD']==='POST'){
  
             $cita= new Citas($_POST['cita']);
             $cita->id_usuario=$usuario->idUsuarios;
            
             //para intervalos
             $hora=date("H:i:s", strtotime($cita->hora)); 
            //convertir formato para comprobar su existencia en festivos
             $valor=  date("d/m/Y", strtotime($cita->fecha)) ; 

            $existeServicio=$cita->existeCondiciones('id_usuario',$usuario->idUsuarios,
            'id_servicio',$cita->id_servicio,'fecha',$cita->fecha);
           
          if(!$existeServicio){
                  
            // $horario=$cita->findAll($cita->fecha,'fecha',false);
             $horario=$cita->findAll2($cita->id_servicio,$cita->fecha,false); //busca las citas para un dia y servicio determinado
         
             //intervalo cliente
             $servicio= Servicios::find($cita->id_servicio);
             $hora_finalCliente=SumaHoras($hora,$servicio->duracion);
 
           if($horario->num_rows){  //si exiten citas
               $filas=$horario->num_rows;
            
               $intervalosCliente=intervaloHora($hora,$hora_finalCliente,1);
               if(in_array("13:59:00",$intervalosCliente) || in_array("19:59:00",$intervalosCliente) ){
                  $existe=false;
               }

               $i=1;
              while($i<=$filas && $existe ){
                $horas=$horario->fetch_assoc();
                //intervalo existente en bd
                $hora_finalBD=SumaHoras($horas['hora'],$horas['duracion']);
      
                 foreach(intervaloHora($horas['hora'], $hora_finalBD, 1) as $intervaloBD){

                  if(in_array($intervaloBD,$intervalosCliente)){
                      $existe=false;
                      break; 
                   }else{
                     $existe=true;
                   }
                 }
        
                 $i++;
              }
               $errores=$cita->validarCita($valor, $existe);
               if(empty($errores)){ 
                 $mensaje=  correo($mail,$usuario->nombre, $usuario->telefono,$usuario->correo,$valor,$hora,$hora_finalCliente,$servicio->denominacion,true,true);
                 $cita->guardar(null,'/resumen-citas?resultado=1');
              }
  
           } else{
              $errores=$cita->validarCita($valor, $existe);
              if(empty($errores)){ 
                $mensaje=  correo($mail,$usuario->nombre, $usuario->telefono,$usuario->correo,$valor,$hora, $hora_finalCliente, $servicio->denominacion,true,true);
                $cita->guardar(null,'/resumen-citas?resultado=1');
                
              }
             
           }

          }else{
              $mensajes="Tienes cita con este servicio";
          }

         } 

         $router->render('citas/cita', [
          'errores'=>$errores,
          'fecha_actual'=>$fecha_actual,
           'servicios'=>$servicios,
          'mensajes'=>$mensajes,
          'cita'=>$cita
          
         ]);

    }

   

    public static function eliminarCita(){

      $mail= new PHPMailer();
      $mensaje=null;
      if($_SERVER['REQUEST_METHOD']==='POST'){

          $id=$_POST['id'];
          $id=filter_var($id, FILTER_VALIDATE_INT);
     
         if($id){
           $tipo=$_POST['tipo'];
        
            if(validarTipoContenido($tipo)){
             //compara lo que vamos a eliminar
             $cita= Citas::find($id);
             if($cita->fecha>=date("Y-m-d")){
              
              //convertir formato 
                $valor=  date("d/m/Y", strtotime($cita->fecha)); 
                $hora=date("H:i:s", strtotime($cita->hora)); 
                $servicio= Servicios::find($cita->id_servicio);
                $usuario=Usuarios::findAdm($cita->id_usuario);
                $hora_finalCliente=SumaHoras($hora,$servicio->duracion);
                $mensaje=  correo($mail,$usuario->nombre, $usuario->telefono,$usuario->correo,$valor,$hora, $hora_finalCliente, $servicio->denominacion,false,false);
                $cita->eliminar($cita->idcitas, '/admin?resultado=3');
             } else{

                $cita->eliminar($cita->idcitas, '/admin?resultado=3');
             }
              
            }
  
          } 

       }

  }

}
