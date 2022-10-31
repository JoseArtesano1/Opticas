<?php  
namespace Model;

class ActiveRecord{

      //base de datos
      protected static $db;
      protected static $columnasDB=[];
      protected static $tabla='';
      protected static $tablaDos='';
      protected static $iD='';
      protected static $idr='';

      //errores y alertas
      protected static $errores=[];
      protected static $alertas=[];
       //definir la conexion a la bd
       public static function setDB($database){
            self::$db=$database;
       }
      
  

      public static function existe($valor,$condicion){
        //revisar si existe  en la bd igual al introducido
        $query="SELECT * FROM " . static::$tabla . " WHERE " . $valor . "= '" . self::$db->escape_string($condicion) . "' LIMIT 1";
        $resultado= self::$db->query($query);
      
        if($resultado->num_rows){
          return true;
        }
        return false;
      }


    public static function existeCondiciones($valor, $condicion, $valor1, $condicion1, $valor2,$condicion2){
        //revisar si existe  en la bd igual al introducido
        $query="SELECT * FROM " . static::$tabla . " WHERE " . $valor . "= " . self::$db->escape_string($condicion) . " and ";
        $query .= $valor1 . "= " . self::$db->escape_string($condicion1) . " and ";
        $query .= $valor2 . "= '" . self::$db->escape_string($condicion2). "'";
        $resultado= self::$db->query($query);
     
       if($resultado->num_rows){
        return true;
       }
    
       return false;
     }


    public static function existedosCondiciones($valor, $condicion,  $valor2,$condicion2){
     //revisar si existe  en la bd igual al introducido
     $query="SELECT * FROM " . static::$tabla . " WHERE " . $valor . "= " . self::$db->escape_string($condicion) . " and ";
     $query .= $valor2 . "= '" . self::$db->escape_string($condicion2). "'";
     $resultado= self::$db->query($query);
   
     if($resultado->num_rows){
          return true;
     }
  
      return false;
    }


      public function guardar($id=null, $pag=null){
        if(!is_null($id)){
          //actualizar
          $this->actualizar($id);
        }else{
          //crear
          $this->crear($pag);
        }
   
      }
  
      public function crear($pag){
            //sanitizar datos
          $atributos=$this->sanitizarDatos();
          
          $query="INSERT INTO " . static::$tabla . " ( ";
          $query .= join(', ', array_keys($atributos));  // para tener una array con los keys
          $query .= " )VALUES (' ";
          $query .= join("', '", array_values($atributos));
          $query .= " ') ";
         
          $resultado=self::$db->query($query);
         
          if($resultado){
            header('Location: ' . $pag); 
         }
          
      }
  

      public function actualizar($id){
          //sanitizar datos
          $atributos=$this->sanitizarDatos();
          $valores=[];
        foreach($atributos as $key=>$value){
          $valores[]="{$key}='{$value}'";
        }
        //    $query .= " WHERE " . static::$iD . "= '" . self::$db->escape_string($this->idServicios) . "' ";
        $query="UPDATE " . static::$tabla . " SET ";
        $query .= join(', ', $valores);
        $query .= " WHERE " . static::$idr . "= '" . self::$db->escape_string($id) . "' ";
        $query .= " LIMIT 1 ";
         
        $resultado= self::$db->query($query);
       
        if($resultado){
          header('Location: /admin?resultado=2');
        }
      }
      

     
      public function actualizarUser($id){
        //sanitizar datos
        $atributos=$this->sanitizarDatos();
        $valores=[];
      foreach($atributos as $key=>$value){
        $valores[]="{$key}='{$value}'";
      }
     
      $query="UPDATE " . static::$tabla . " SET ";
      $query .= join(', ', $valores);
      $query .= " WHERE " . static::$idr . "= '" . self::$db->escape_string($id) . "' ";
      $query .= " LIMIT 1 ";
       
       self::$db->query($query);
       
    }


  //eliminar registro
  
  public function eliminar($id, $pag){
     $query="DELETE FROM " . static::$tabla . " WHERE " . static::$idr . "= " . self::$db->escape_string($id) . " LIMIT 1";
        $resultado=self::$db->query($query);
     if($resultado){
      $this->borrarImagen();
      header('Location: ' . $pag);
     }
  }
  

  public function eliminarAll($id, $pag){
    $query="DELETE FROM " . static::$tabla . " WHERE " . static::$idr . "= " . self::$db->escape_string($id);
       $resultado=self::$db->query($query);
    if($resultado){
     header('Location: ' . $pag);
    }
 }


  //identifica  y une los atributos bd  mapeo objeto con columnas base
      public function atributos(){
            $atributos=[];
          foreach(static::$columnasDB as $columna){
              if($columna===static::$idr) continue; //lo ignora
              $atributos[$columna]=$this->$columna;
          }
          return $atributos;
      }
  
  
     public function sanitizarDatos(){
          $atributos=$this->atributos();
      
        $sanitizado=[];
        foreach($atributos as $key=>$value){
           $sanitizado[$key]=self::$db->escape_string($value);
        }
  
        return $sanitizado;
     }
     

     //subida archivos
     public function setImagen($imagen){
         //ELimina la imagen previa
        if(!is_null($this->idServicios)){
        $this->borrarImagen();
        }
  
       //asignamos al atributo de la imagen el nombre del la imagen
       if($imagen){
        $this->imagen=$imagen;
       }
     }
  
     //eliminar archivo
  
     public function borrarImagen(){
        $existeArchivo=file_exists(CARPETA_IMAGENES . $this->imagen);
        if($existeArchivo){
          unlink(CARPETA_IMAGENES . $this->imagen);
        }
  
     }
  
     //validacion errores
     
     public static function getErrores(){
        return static::$errores;
     }

     public static function setErrores($tipo,$mensaje){
        static::$errores[$tipo] []=$mensaje;
     }
  
     public function validar(){
       static::$errores =[];
       return static::$errores;
     } 
  
     //todos los registros
  
     public static function all(){
      $query="SELECT * FROM " . static::$tabla;
      $resultado= self::consultarSQL($query);
     
      return $resultado;

    }

    public static function allFiltrado(){
      $query="SELECT * FROM " . static::$tabla . " WHERE estado = '1' and Apellidos != 'Optica'";
      $resultado= self::consultarSQL($query);
     
      return $resultado;
     
    }
  
  
    //buscar un registro por id como correo
    public static function find($id){  
      
      $idSto=self::$db->escape_string($id);
      $query="SELECT * FROM " . static::$tabla . " WHERE " . static::$iD . "= '${idSto}'";
      $resultado=self::consultarSQL($query);  //obtener el objeto
      
      return array_shift($resultado);  //devuelve el primer elemento del array
    }
  


    public static function where($columna, $valor){  
     
      $query="SELECT * FROM " . static::$tabla . " WHERE ${columna} = '${valor}'";
      $resultado=self::consultarSQL($query);  //obtener el objeto
      
      return array_shift($resultado);  //devuelve el primer elemento del array
    }



    public static function findAdm($id){  
      //$query="SELECT * FROM " . static::$tabla . " WHERE id= ${id}";
      $idSto=self::$db->escape_string($id);
      $query="SELECT * FROM " . static::$tabla . " WHERE " . static::$idr . "= '${idSto}'";
      $resultado=self::consultarSQL($query);  //obtener el objeto
      
      return array_shift($resultado);  //devuelve el primer elemento del array
    }


    public static function consultarSQL($query){
        //consultar bd
      $resultado= self::$db->query($query);
      //iterar resultado
       $array=[];
       
       while($registro=$resultado->fetch_assoc()){
        $array[]= static::crearObjeto($registro);
       }
     
      //liberar memoria
       $resultado->free();
  
      //retornar resultados
      return $array;
    }
  
  
    protected static function crearObjeto($registro){
      $objeto= new static;    //crear nuevos registros clase principal o nueva instancia dentro de la clase
      foreach($registro as $key => $value){   //comparamos el objeto con lo que tenemos en el array
          
        if(property_exists($objeto, $key))   {  //objeto cuyo contenido por defecto esta vacio como la propiedades de arriba
         $objeto->$key=$value;  //mapeo
        }
      }
      return $objeto;
   }
    
  
    //sincroniza el objeto en memoria con los cambios introducidos por el usuario
    public function sincronizar($args=[]){  
      foreach($args as $key=>$value){                     //si no es null asignamos
        if(property_exists($this, $key) && !is_null($value)){   //comprueba que la propiedad existe comparamos el resultado de find con el post
                                          //this el lio del constructor que sale del post--- compara keys---
        $this->$key=$value;  //key es variable por $  mapeo
        }
      }
    }

}