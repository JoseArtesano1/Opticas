<?php
namespace Controllers;

use Model\Captcha;
use MVC\Router;
use Model\Usuarios;
use Model\Intentos;
use PHPMailer\PHPMailer\PHPMailer;

class PaginasController{
 
    public static function index(Router $router){
        $inicio=true;
        $router->render('paginas/index',[
            'inicio'=>$inicio
        ]);
    }


    public static function informar(Router $router){
        $router->render('paginas/condicion',[]);
    }

    
    
    public static function alta(Router $router){
        $mail= new PHPMailer();   
        $usuario=new Usuarios;
        $errores=Usuarios::getErrores();
        $ObjCaptcha= new Captcha();
     
        if($_SERVER['REQUEST_METHOD']==='POST'){
   
            $usuario= new Usuarios($_POST['usuario']);
            
            $retorno=$ObjCaptcha->getCaptcha($_POST['g-recaptcha-response']); //obtener el objeto del html
         
          if($retorno->success ==true && $retorno->score >0.7){  //variar el indice para mas o menos control
               $errores=  $usuario->validar();
              
              if(empty($errores)){
                              
                      // código para procesar los campos y enviar el form
                      $usuario->hashPassword();
                      $usuario->crearToken();
                      correoAlta($mail,$usuario->correo,$usuario->token,true);
                      $usuario->guardar(null,'/login?resultado=6');
              }
                    
            }
                  
        }
        

        $router->render('paginas/alta',[
            'errores'=>$errores,
            'usuario'=>$usuario
        ]); 

    }


  


    public static function eliminarUsuario(){

        $intento=new Intentos;
        if($_SERVER['REQUEST_METHOD']==='POST'){
      
          $id=$_POST['id'];
          $id=filter_var($id, FILTER_VALIDATE_INT);
       
             if($id){

                $tipo=$_POST['tipo'];
                
                 if(validarTipoContenido($tipo)){
                   //compara lo que vamos a eliminar
                    $usuario= Usuarios::findAdm($id);
                    $intento->eliminarAll($usuario->idUsuarios,null);
                    $usuario->eliminar($usuario->idUsuarios, '/admin?resultado=3');
              
                 }
  
            } 

       }
        
    }




    public static function confirmar(Router $router){
        $errores = [];
        $token=s($_GET['token']);
        $usuario1=Usuarios ::where('token',$token);
       
        if(empty($usuario1)){

            Usuarios::setErrores('error','Token no válido');
          
        }else{

            $usuario1->estado="1";
            $usuario1->token=null;
            $usuario1->actualizarUser($usuario1->idUsuarios);
            Usuarios::setErrores('exito','Cuenta Confirmada, Se puede logear');
           
        }

        $errores=Usuarios::getErrores();
        
        $router->render('paginas/confirmar-cuenta',
        ['alertas'=>$errores]);
    }


   



}