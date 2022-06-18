<?php  
namespace Model;

class Tecnicos extends ActiveRecord{


     //base de datos
    
     protected static $columnasDB=['idTecnicos', 'nombre'];
     protected static $tabla='tecnicos';
     protected static $iD= 'idTecnicos';
     protected static $idr='idTecnicos';
     //errores
     protected static $errores=[];

     public $idTecnicos;
     public $nombre;


    

    public function __construct($args=[]){
      
        $this->idTecnicos=$args['idTecnicos'] ?? null;
        $this->nombre=$args['nombre'] ?? '';

    }
    
        
    public function validar(){
        if(!$this->nombre){
           self:: $errores[]="Debe añadir el nombre del técnico";
        }

        return self::$errores;
    }

    
}