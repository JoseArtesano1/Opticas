<?php
namespace Controllers;

use MVC\Router;
use Model\Usuarios;
use Model\Intentos;
use PHPMailer\PHPMailer\PHPMailer;

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



  public static function modificarUsuario(Router $router){
   
    $mail= new PHPMailer(); 
    $errores = [];
    $alertas=[];

    if($_SERVER ['REQUEST_METHOD']==='POST'){
           //asignar atributos
             $auth= new Usuarios($_POST);
             
             $errores=$auth->validarEmail();
            
              
          if(empty($errores)){

            $usuario=Usuarios::where('correo', $auth->correo);
           
            
              if($usuario && $usuario->estado==="1"){
                if($usuario->idUsuarios!="1"){
                  $usuario->crearToken();
                  $usuario->actualizarUser($usuario->idUsuarios);
                  enviarInstrucciones($mail,$usuario->correo, $usuario->token);
                  Usuarios::setErrores('exito', 'Revisa tu E-mail');
               //   header('Location: /login?resultado=7');

                }else{
                  Usuarios::setErrores('error', 'El usuario no existe');
                }
                
              }else{
                Usuarios::setErrores('error', 'El usuario no existe o sin confirmar');
               
              }
   
          }else{
            Usuarios::setErrores('error', 'Introduce un Email');
          }
         
   }

   $errores=Usuarios::getErrores();
 
     $router->render('auth/actualizarUser', [
            'mistake'=>$errores
         
      ]);
  }




  public static function recuperar(Router $router){
     $errores=[];
    $token=s($_GET['token']);
    $usuario=Usuarios ::where('token',$token);
    $error=false;

  
 //   $_SESSION['token']=$token;

    if(empty($usuario)){
      Usuarios::setErrores('error','Token no válido');
      $error=true;
     // $_SESSION['token']=true;
    }

    if($_SERVER['REQUEST_METHOD']==='POST'){

      $newPass=new Usuarios($_POST);
      $errores= $newPass->validarPassWord();

      if(empty($errores)){
        $usuario->acceso=null;
       
        $usuario->acceso=$newPass->acceso;
         $usuario->hashPassword();
        $usuario->token=null;

       $resultado= $usuario->actualizarUser($usuario->idUsuarios);
      // debuguear($resultado);
        if( is_null($resultado) ){ header('Location: /login');}
        // else{header('Location: /login');}
      } 
    }

    $errores=Usuarios::getErrores();

    $router->render('auth/recuperar-cuenta',[
      'alertas'=>$errores,
      'error'=>$error
    ]);

  }



}