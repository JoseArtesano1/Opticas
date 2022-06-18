<?php  
namespace Model;

class Servicios extends ActiveRecord{

    //base de datos
    protected static $columnasDB=['idServicios', 'denominacion','duracion','idTecnicos','imagen'];
    
    protected static $tabla='servicios';
    protected static $iD= 'idServicios';
    protected static $idr= 'idServicios';

    //errores
    protected static $errores=[];

    public $idServicios;
    public $denominacion;
    public $duracion;
    public $idTecnicos;
    public $imagen;

    

    public function __construct($args=[])
    {
        $this->idServicios=$args['idServicios'] ?? null;
        $this->denominacion=$args['denominacion'] ?? '';
        $this->duracion=$args['duracion'] ?? '';
        $this->idTecnicos=$args['idTecnicos'] ?? '';
        $this->imagen=$args['imagen'] ?? '';
        
    }


    public function validar(){
      if(!$this->denominacion){
         self:: $errores[]="Debe añadir el nombre del servicio";
      }
  
      if(!$this->duracion){
        self:: $errores[]="Debe añadir la duración en minutos";
  
      }
  
      if(!$this->imagen){
         self:: $errores[]="Debe seleccionar una imagen";
      }
  
      if(!$this->idTecnicos){
        self::  $errores[]="Debe seleccionar un técnico";
      }
  
      return self::$errores;
     }


}