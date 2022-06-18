<?php  
namespace Model;

class Festivos extends ActiveRecord{

    //base de datos
    protected static $columnasDB=['idfestivos', 'dia'];
    
    protected static $tabla='festivos';
    protected static $iD= 'dia';
    protected static $idr= 'dia';

    //errores
    protected static $errores=[];

    public $idfestivos;
    public $dia;

    public function __construct()
    {
        $this->idfestivos=$args['idfestivos'] ?? null;
        $this->dia=$args['dia'] ?? '';
    }


    public function validar(){

        if(!$this->dia){
            self:: $errores[]="Debe seleccionar el archivo";
         }

         return self::$errores;
    }




   
}