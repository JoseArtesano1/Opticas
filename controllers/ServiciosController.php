<?php
namespace Controllers;
use MVC\Router;
use Model\Servicios; 
use Model\Tecnicos;
use Model\Citas;
use Model\Usuarios;
use Intervention\Image\ImageManagerStatic as Image;

class ServiciosController{

    public static function index(Router $router){

        $servicios= Servicios::all();
        $tecnicos= Tecnicos::all();
        $citas=Citas::findAllCitas();
        $usuarios=Usuarios::allFiltrado();
        //muestra mensaje condicional
        $resultado=$_GET['resultado'] ?? null;

        $router->render('servicios/admin', [
          
            'servicios'=>$servicios,
            'resultado'=>$resultado,
            'tecnicos'=>$tecnicos,
            'citas'=>$citas,
            'usuarios'=>$usuarios
        ]);
       
    }

    
    public static function crear(Router $router){
        $servicio= new Servicios;
        $tecnicos=Tecnicos::all();

        //arreglo errores
        $errores=Servicios::getErrores();

        if($_SERVER['REQUEST_METHOD']==='POST'){

               //CREAR NUEVA INSTANCIA  
              $servicio= new Servicios($_POST['servicio']);

              //SUBIDA ARCHIVOS
              //generar nombre especifico archivo
              $nombreImagen=md5(uniqid(rand(),true)) . ".png";

            //SETEAR IMAGEN
            //realizar resize a la imagen con intervention
            if($_FILES['servicio']['tmp_name']['imagen']){
             $image=Image::make($_FILES ['servicio']['tmp_name']['imagen'])->fit(800,600);
             $servicio->setImagen($nombreImagen);
             }
            
            //VALIDAR
            $errores= $servicio->validar();
 
           if(empty($errores)){
     
             //crear carpeta
             if(!is_dir(CARPETA_IMAGENES)){
               mkdir(CARPETA_IMAGENES);
              }

             // guardar la imagen en el servidor
              $image->save(CARPETA_IMAGENES . $nombreImagen);

             //GUARDAR EN BASE DE DATOS
              $servicio->guardar(null,'/admin?resultado=1');
     
        }
        
      }

       $router->render('servicios/crear', [
        'servicio'=>$servicio,
        'tecnicos'=>$tecnicos,
        'errores'=>$errores
        ]);

    }


    public static function actualizar(Router $router){
       $id=validarORedireccionar('/admin','idServicios');

       $servicio=Servicios::find($id);
       $tecnicos=Tecnicos::all();
       $errores=Servicios::getErrores();

       if($_SERVER['REQUEST_METHOD']==='POST'){
        
          //asignar atributos
          $args=$_POST['servicio'];
         
          $servicio->sincronizar($args);
          //VALIDACION
          $errores=$servicio->validar();
      
          //SUBIDA ARCHIVOS
            //generar nombre especifico archivo
              $nombreImagen=md5(uniqid(rand(),true)) . ".png";
            
           //SETEAR IMAGEN
           //realizar resize a la imagen con intervention
           if($_FILES ['servicio'] ['tmp_name'] ['imagen']){
               $image=Image::make($_FILES ['servicio']['tmp_name']['imagen'])->fit(800,600);
               $servicio->setImagen($nombreImagen);
            }
         
          if(empty($errores)){
  
              if($_FILES ['servicio'] ['tmp_name'] ['imagen']){
                //  ALMACENAR IMAGEN
                $image->save(CARPETA_IMAGENES . $nombreImagen);
              }
               $servicio->guardar($id, '/admin?resultado=2');
           }
      
      }

        $router->render('/servicios/actualizar', [
        'servicio'=>$servicio,
        'errores'=>$errores,
        'tecnicos'=>$tecnicos
       ]);


   }


   public static function eliminar(){

        if($_SERVER['REQUEST_METHOD']==='POST'){

          $id=$_POST['id'];
          $id=filter_var($id, FILTER_VALIDATE_INT);
             
          if(!Citas::existe('id_servicio',$id)){  //comprobar que no tenemos citas con servicios
          
             if($id){
                $tipo=$_POST['tipo'];
                
                 if(validarTipoContenido($tipo)){
                   //compara lo que vamos a eliminar
                    $servicio= Servicios::find($id);
                    $servicio->eliminar($servicio->idServicios, '/admin?resultado=3');
              
                 }
  
             } 
          } else{  header('Location: /admin?resultado=4');}

       }
   }
   
}