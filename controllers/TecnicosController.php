<?php
namespace Controllers;
use MVC\Router;
use Model\Tecnicos;
use Model\Festivos;
use Model\Servicios;

class TecnicosController{

    public static function crearTecnico(Router $router){

        $tecnico= new Tecnicos;
        $festivop= new Festivos;
        $mensaje=null;
        $errores=Tecnicos::getErrores();
        $errores=Festivos::getErrores();

        if($_SERVER['REQUEST_METHOD']==='POST'){
          
            $tipo=$_POST['tipo'];
              
             if(validarTipoContenido($tipo)){
       
                 if($tipo==='tecnico'){
                     $tecnico= new Tecnicos($_POST['tecnico']);
                    
                     //VALIDAR NO TENER CAMPOS VACIOS
                        $errores=$tecnico->validar();
             
                     if(empty($errores)){
                        $tecnico->guardar(null,'/admin?resultado=1');
                     }
             
                  }elseif($tipo==='festivo'){
                     
                    if($_FILES['festivo']['tmp_name']['dia']!=""){

                       //para los festivos
                        $carpetArchivos='../archivos/';
                      // $carpetArchivos='../archivos/';
                       if(!is_dir($carpetArchivos)){
                        mkdir($carpetArchivos);
                       }
 
                      $nombreFile= $_FILES['festivo'];
                     
                      //subimos el archivo al servidor (file origen, file destino)
                      move_uploaded_file($nombreFile['tmp_name']['dia'], $carpetArchivos . $nombreFile['name']['dia']);
                      $path=$carpetArchivos . $nombreFile['name']['dia'];
             
                       if(file_exists($path))
                        {                               
                             $lineas = file($path);  
                             
                             $db=conectarDB();
                             foreach ($lineas as $linea_num => $linea)
                            {
                               $datos = explode('\t',$linea);
                               $festivo = trim($datos[0]);
                                
                               $festivos=$db->escape_string($festivo);
                               if(!$festivop->existe('dia',$festivos)){
                                   $consulta = "INSERT INTO festivos(dia) VALUES('$festivos')";
                                   $db->query($consulta);
                               }
                                                          
                             }
                         }

                    }  else{$mensaje="Seleccione un archivo";}  
                     
                  }
             }
            
         }

         $router->render('tecnicos/crear', [
             'mensaje'=>$mensaje,
            'tecnico'=>$tecnico,
            'errores'=>$errores
            ]);

    }


    public static function actualizarTecnico(Router $router){
        $id=validarORedireccionar('/admin','idTecnicos');
        // obtener el vendedor bd
         $tecnico=Tecnicos::find($id);
         $errores= Tecnicos::getErrores();

         if($_SERVER['REQUEST_METHOD']==='POST'){
                
            //ASIGNAR LOS VALORES
             $args=$_POST['tecnico'];
    
             //SINCRONIZAR OBJETO EN MEMORIA CON LO QUE USUARIO ESCRIBE
             $tecnico->sincronizar($args);
             //VALIDAR
             $errores=$tecnico->validar();
            
            if(empty($errores)){
                $tecnico->guardar($id, '/admin?resultado=2');
            }
        }

        $router->render('tecnicos/actualizar', [
             'tecnico'=>$tecnico,
            'errores'=>$errores
            ]);

    }

    public static function eliminarTecnico(){

        if($_SERVER['REQUEST_METHOD']==='POST'){

            $id=$_POST['id'];
            $id=filter_var($id, FILTER_VALIDATE_INT);

            if(!Servicios::existe('idTecnicos',$id)){
             
               if($id){
                    $tipo=$_POST['tipo'];
          
                    if(validarTipoContenido($tipo)){
                       //compara lo que vamos a eliminar
                       $tecnico= Tecnicos::find($id);
                       $tecnico->eliminar($tecnico->idTecnicos, '/admin?resultado=3');
                    }  
               } 
  
            }else{  header('Location: /admin?resultado=5');}

         }

    }
}