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
                 
                $pass=chr(rand(ord('a'),ord('z')));//generamos una letra
                $pass1=chr(rand(ord('A'),ord('Z')));//generamos otra letra
                $passNumero=rand(10000,50000);// generado número
                $password=$pass.$passNumero.$pass1;// contraseña final se envia al usuario por email
                           
                      // código para procesar los campos y enviar el form
        
                      if(correoAlta($mail,$usuario->correo,$password,true)){
                        $usuario->acceso=password_hash($password, PASSWORD_DEFAULT); //se guarda en la bd
                        $usuario->guardar(null,'/login?resultado=6');
                       }
              }
                    
            }
                  
        }
        

        $router->render('paginas/alta',[
            'errores'=>$errores,
            'usuario'=>$usuario
        ]); 

    }


    public static function modificarUsuario(Router $router){
       
        $id=validarORedireccionar('/admin','idUsuarios');
        $usuario=Usuarios::findAdm($id);
        $errores= Usuarios::getErrores();
 
        if($_SERVER['REQUEST_METHOD']==='POST'){
             
                //asignar atributos
              
                  $args=$_POST['usuario']?? []; //para que si no se marca damos por defecto array vacio
                  $usuario->sincronizar($args); 
                  $errores=$usuario->validarActivacion();

              if(empty($errores)){
                  $usuario->guardar($id, '/admin?resultado=2');
              }
             
       }
 
         $router->render('/paginas/actualizarUser', [
         'usuario'=>$usuario,
         'errores'=>$errores
          
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

}