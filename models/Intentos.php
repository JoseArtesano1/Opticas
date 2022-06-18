<?php  
namespace Model;

class Intentos extends ActiveRecord{

    protected static $columnasDB=['idintentos', 'reloj','Id_user'];

    protected static $tabla='intentos';
    protected static $idr= 'Id_user';
    protected static $iD= 'idintentos';

    protected static $errores=[];

    public $idintentos;
    public $reloj;
    public $Id_user;

    public function __construct($args=[])
    {
        $this->idintentos=$args['idintentos'] ?? null;
        $this->reloj=$args['reloj'] ?? '';
        $this->Id_user=$args['Id_user'] ?? '';
                
    }


    public function numeroIntentos($id){

        $query="SELECT count(*) as cantidad FROM intentos WHERE Id_user = '" . self::$db->escape_string($id) . "' LIMIT 1";
        $resultado= self::$db->query($query);
        $registro=$resultado->fetch_assoc();
        $resultado->free();
        return $registro['cantidad'];
    }


    public function maxHora($id){

        $query="SELECT * FROM intentos ";
        $query .=" WHERE timestampdiff(MINUTE, reloj, now())<= 10 and Id_user = '" . self::$db->escape_string($id) . "' ";
        $query .= " ORDER BY reloj DESC LIMIT 1";
        $resultado= self::$db->query($query);
       
        if($resultado->num_rows){
            return true;
          }
          return false;
    }


}