<?php  
namespace Model;

class Usuarios extends ActiveRecord{

     //base de datos
     protected static $columnasDB=['nombre', 'Apellidos', 'correo', 'telefono', 'acceso','condiciones','estado','token'];
    
     protected static $tabla='usuarios';
     protected static $iD= 'correo';
   // protected static $iD='idUsuarios';
     protected static $idr= 'idUsuarios';
 
     //errores y alertas
     protected static $errores=[];
     protected static $alertas=[];
      
     public $idUsuarios;
     public $nombre;
     public $Apellidos;
     public $correo;
     public $telefono;
     public $acceso;
     public $condiciones;
     public $estado;
     public $token;
 
  
     public function __construct($args=[])
     {
         $this->idUsuarios=$args['idUsuarios'] ?? null;
         $this->nombre=$args['nombre'] ?? '';
         $this->Apellidos=$args['Apellidos'] ?? '';
         $this->correo=$args['correo'] ?? '';
         $this->telefono=$args['telefono'] ?? '';
         $this->acceso=$args['acceso'] ?? '';
         $this->condiciones=$args['condiciones'] ?? '0';
         $this->estado=$args['estado'] ?? '0';
         $this->token=$args['token'] ?? '';
     }


    public function comprobarEmail($correo){
      $result = (false !== filter_var($correo, FILTER_VALIDATE_EMAIL));  // comprobar formato
      
        if ($result)
        {
          list($user, $domain) = preg_split('{@}', $correo);
          $result = checkdnsrr($domain, 'MX');  //comprobar las DNS
        }
     
       return $result;
    }

   

     public function validar(){
        if(!$this->nombre){
           self:: $errores[]="Debe añadir el nombre";
        }
    
          if(!$this->Apellidos){
            self:: $errores[]="Debe añadir los apellidos";
          }
    
          if(!$this->correo){
            self:: $errores[]="Debe añadir un correo";
          }else{
             if(!$this->comprobarEmail($this->correo)){
              self:: $errores[]="El correo es erroneo";
             }else{
                if($this->existe('correo',$this->correo)){
                  self:: $errores[]="El usuario ya Existe con este Email";
                }
             }
          }
  
          if(!$this->telefono){
            self::  $errores[]="Debe añadir un teléfono";
          }else{
             if(!preg_match('/^[0-9]{9,9}$/', $this->telefono)){
              self::  $errores[]="No es un telefono correcto";
             }
          }

           if(!$this->condiciones){
            self::  $errores[]="Debe aceptar las condiciones";
           }
    
           if(!$this->acceso){
            self::  $errores[]="Debe introducir su contraseña";
           }

           if(strlen($this->acceso)<6){
            self::  $errores[]="La contraseña debe tener al menos 6 caracteres";
           }

          return self::$errores;
    }


        public function validarLogin(){

          if(!$this->correo){
            self:: $errores[]="Introduzca su correo";
          }
          if(!$this->acceso){
            self::  $errores[]="Introduzca su contraseña";
          }
          return self::$errores;

        }


        public function validarEmail(){

          if(!$this->correo){
            self:: $alertas['error'] []="E-mail es obligatorio";
          }
         
          return self::$alertas;

        }

        public function validarPassWord(){

          if(!$this->acceso){
            self:: $errores[]="Introduzca su contraseña";
          }
          if(strlen($this->acceso)<6){
            self::  $errores[]="La contraseña debe tener al menos 6 caracteres";
           }
          return self::$errores;

        }

        public function validarActivacion(){

          if(!$this->estado){
            self:: $errores[]="Active el usuario";
          }
         
          return self::$errores;

        }




        
       public function existeUsuario(){
         $query="SELECT * FROM " . self::$tabla  . " WHERE correo= '" . $this->correo . "' LIMIT 1";
         $resultado=self::$db->query($query);
            if(!$resultado->num_rows){
            self::$errores[]="el usuario no existe";
            return;
           }

          return $resultado; 

       }


       public function comprobarPassword($resultado){  //se usa para logear
          $usuario=$resultado->fetch_object();
           $autenticado= password_verify($this->acceso ,$usuario->acceso);

           if(!$autenticado){
            self::$errores[]="contraseña incorrecta";
            
          }
             return $autenticado;

       }

       //otra para crear la contraseña

       public function autenticar(){
          session_start();
          //llenar el arreglo de sesion
          $_SESSION['usuario']=$this->correo;
          $_SESSION['login']=true;
          $miUsuario= $this->find($_SESSION['usuario']);
           
           if($miUsuario->idUsuarios==="1"){
               $_SESSION['id']=true;
               header('Location: /admin');
          
           }else{
               header('Location: /cita');
           }

       }

      
       public function hashPassword(){
        $this->acceso=password_hash($this->acceso, PASSWORD_BCRYPT);
       }


       public function crearToken(){
        $this->token=uniqid();
       }

       

}