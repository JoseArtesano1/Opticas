<?php
namespace Controllers;

use MVC\Router;
use Model\Usuarios;
use Model\Intentos;

class LoginController{

  public static function login(Router $router){
    $resultado=$_GET['resultado'] ?? null; //mensaje al usuario al guardar con exito
    $errores=[];
    $miUsuario=null;
    $mensajes=null;
    $intento=new Intentos;
    $db=conectarDB();
    if($_SERVER ['REQUEST_METHOD']==='POST'){
        $usuario= new Usuarios($_POST);
        $errores=$usuario->validarLogin();
       
        if(empty($errores)){
            //EXISTE USUARIO
            $resultados= $usuario->existeUsuario();
           
             if(!$resultados){
                 $errores=Usuarios::getErrores();  //imprimir errores
             } else{
                //COMPROBAR CONTRASEÑA
                $elusuario=$usuario->find($usuario->correo);
               
                if($intento->numeroIntentos($elusuario->idUsuarios)<3){

                  $autenticado=$usuario->comprobarPassword($resultados);
                 
                    if($autenticado){
                      //AUTENTICAR USUARIO
                        $intento->eliminarAll($elusuario->idUsuarios,null);
                        $miUsuario=Usuarios::existedosCondiciones('estado',1,'correo',$usuario->correo);
                       
                        if($miUsuario){
                          $usuario->autenticar();
                        }else{
                         $errores=Usuarios::getErrores();  //imprimir errores
                        }
                       
                    }else{
                     $errores=Usuarios::getErrores();  //imprimir errores
                     $id=$elusuario->idUsuarios;
                     $consulta = "INSERT INTO intentos(reloj,Id_user) VALUES(NOW(),'$id')";
                     $db->query($consulta);
                    }
                }else{
                                   
                    if(!$intento->maxHora($elusuario->idUsuarios)){  //comprueba que el último intento supera el tiempo
                     $intento->eliminarAll($elusuario->idUsuarios,'/login');
                   
                    }else{
                     $mensajes="Ha superado el número de intentos, espere unos minutos";
                    }
                }
    
             }
        }
    }

    $router->render('auth/login', [
        'errores'=>$errores,
        'miUsuario'=>$miUsuario,
        'mensajes'=>$mensajes,
        'resultado'=>$resultado  //el mensaje al usuario con exito
    ]);
  }



  public static function logout(){
    session_start();
    $_SESSION =[];
    header('Location: /');
  }

}