<?php  
namespace Model;

class Citas extends ActiveRecord{

     //base de datos
     protected static $columnasDB=['idcitas', 'fecha','hora','id_servicio','id_usuario'];
    
     protected static $tabla='citas';
     protected static $iD= 'idcitas';
     protected static $idr='idcitas';
     //errores
     protected static $errores=[];

     public $idcitas;
     public $fecha;
     public $hora;
     public $id_servicio;
     public $id_usuario;

     public function __construct($args=[]){

          $this->idcitas=$args['idcitas'] ?? null;
          $this->fecha=$args['fecha'] ?? '';
          $this->hora=$args['hora'] ?? '';
          $this->id_servicio=$args['id_servicio'] ?? '';
          $this->id_usuario=$args['id_usuario'] ?? '';
     }
     

 
     public function existeFestivo($valor){
          //revisar si existe un festivo en la bd igual al introducido
          $existe=false;
          $query="SELECT dia FROM festivos WHERE dia='${valor}'";
    
          $resultado= self::$db->query($query);
    
          if($resultado->num_rows){
            return $existe;
          }
        
          return !$existe;
      }


      public static function findAll($valor,$referencia, $opcion){
                
         $query="SELECT * FROM citas, servicios WHERE id_servicio= idServicios and " . $referencia . "='${valor}'";
         $resultado= self::$db->query($query);
         // $registro= $resultado->fetch_assoc();
         $array=[];
            if (!$opcion){
                return $resultado;  
            }
               while($registro=$resultado->fetch_assoc()){
                      $array[]= $registro;
                  }
                 //liberar memoria
                 $resultado->free();
                //retornar resultados
                 return $array;
      
       }

       
       public static function findAll2($valor, $valor1,$opcion){
       
         $query="SELECT * FROM citas, servicios WHERE
          id_servicio= idServicios and id_servicio='$valor' and fecha='$valor1'";
         $resultado= self::$db->query($query);
         $array=[];
         if (!$opcion){
          return $resultado;  
          }
         while($registro=$resultado->fetch_assoc()){
    
          $array[]= $registro;
         }
         //liberar memoria
         $resultado->free();
  
         //retornar resultados
         return $array;

      }
 
      

      public static function findAllCitas(){
       
         $query="SELECT idcitas, nombre, correo, telefono, fecha, hora, duracion, imagen, denominacion FROM citas, servicios, usuarios WHERE
          id_servicio= idServicios and idUsuarios= id_usuario";
         $resultado= self::$db->query($query);
         $array=[];
       
         while($registro=$resultado->fetch_assoc()){
    
          $array[]= $registro;
         }
         //liberar memoria
         $resultado->free();
  
         //retornar resultados
         return $array;

      }

      


     public function validarCita($valor, $existe){
          if(!$this->fecha){
             self:: $errores[]="Debe añadir la fecha de la cita";
          }

          if($this->hora && !$this->fecha){
            self:: $errores[]="Debe añadir primero la fecha de la cita";
          }
    
          if(!$this->hora){
             self::   $errores[]="Debe añadir la hora de la cita";
            } 
         
          if(!$this->id_servicio){
             self::  $errores[]="Debe seleccionar un servicio";
              }

          if(!$this->existeFestivo($valor)){
             self::  $errores[]= "Es festivo";
          }

          if(!$existe){
            self::  $errores[]= "hora ocupada o fuera de Límite";
          }
         
  
          return self::$errores;


      }

      


     public static function obtenerCitas(): array{
         try{
          
             $query="SELECT idcitas, fecha, hora, duracion, imagen, denominacion, id_servicio FROM citas, servicios WHERE id_servicio= idServicios";
           
               $resultado=self::$db->query($query);
             $i=0;
             $cita=[];
             while ($row = $resultado->fetch_assoc()){
               $cita[$i]['id']=$row['idcitas'];
               $cita[$i]['fechas']=$row['fecha'];
               $cita[$i]['inicio']=$row['hora'];
               $cita[$i]['fin']=SumaHoras($row['hora'],$row['duracion']);
               $cita[$i]['servicio']=$row['imagen'];
               $cita[$i]['nombre']=$row['denominacion'];
               $cita[$i]['idServicio']=$row['id_servicio'];
               // $cita[$i]['intervalo']=intervaloHora($row['hora'],SumaHoras($row['hora'],$row['duracion']),1);
                $i++;
             }
             
             return $cita;
     
         } catch (\Throwable $th){
           var_dump($th);
         }
     }


     public static function obtenerCitasxServicio(): array{
       try{
       
          $query="SELECT idcitas, nombre, telefono, correo, fecha, hora, duracion, imagen, denominacion, id_servicio FROM citas, servicios, usuarios
           WHERE id_servicio= idServicios and id_usuario=idUsuarios";
        
            $resultado=self::$db->query($query);
          $i=0;
          $cita=[];
          while ($row = $resultado->fetch_assoc()){
            $cita[$i]['id']=$row['idcitas'];
            $cita[$i]['nombre']=$row['nombre'];
            $cita[$i]['telefono']=$row['telefono'];
            $cita[$i]['correo']=$row['correo'];
            $cita[$i]['fechas']=$row['fecha'];
            $cita[$i]['inicio']=$row['hora'];
            $cita[$i]['fin']=SumaHoras($row['hora'],$row['duracion']);
            $cita[$i]['servicio']=$row['imagen'];
            $cita[$i]['denominacion']=$row['denominacion'];
            $cita[$i]['idservicio']=$row['id_servicio'];
             $i++;
          }
          
          return $cita;
  
       } catch (\Throwable $th){
        var_dump($th);
       }
     }
     
}