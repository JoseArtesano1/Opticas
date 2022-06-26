<?php
namespace MVC;

class Router{

    public $rutasGET=[];
    public $rutasPOST=[];

    public function get ($url,$fn){
        $this->rutasGET[$url]=$fn;
    }


    public function post ($url,$fn){
        $this->rutasPOST[$url]=$fn;
    }


   public function comprobarRutas(){

      session_start();
      $auth=$_SESSION['login']?? null;
      $idU=$_SESSION['id'] ?? null;

      //RUTAS PROTEGIDAS
      $rutasProtegidas=['/admin','/servicios/crear','/servicios/actualizar','/servicios/eliminar',
      '/tecnicos/crear', '/tecnicos/actualizar','/tecnicos/eliminar','/cita','/resumen-citas',
    '/cita/eliminarCita','/citasFechaaXz1Ha','/servidor','/paginas/actualizarUser','/paginas/eliminar'];

      $rutasAdministrador=['/admin','/servicios/crear','/servicios/actualizar','/servicios/eliminar',
      '/tecnicos/crear', '/tecnicos/actualizar','/tecnicos/eliminar','/cita/eliminarCita','/servidor',
      '/paginas/actualizarUser','/paginas/eliminar'];



     // if (isset($_SERVER['PATH_INFO'])) {
     //   $urlActual = $_SERVER['PATH_INFO'] ?? '/';
     // } else {
      //  $urlActual = $_SERVER['REQUEST_URI'] === '' ? '/' : $_SERVER['REQUEST_URI'];
     // }


     $urlActual = $_SERVER['REQUEST_URI'] === '' ? '/' : $_SERVER['REQUEST_URI'];
     $urlActual =  explode('?',$urlActual);
     $urlActual =  array_shift($urlActual);
     //  $urlActual= $_SERVER['PATH_INFO'] ?? '/';
       $metodo= $_SERVER['REQUEST_METHOD'];

      if($metodo==='GET'){
         $fn=$this->rutasGET[$urlActual] ?? null;   //la funcion tiene array con el controlador y metodo
 
       }else{
       
        $fn=$this->rutasPOST[$urlActual] ?? null;  //this tiene todas las rutas
       }
       //proteger las rutas
      if(in_array($urlActual, $rutasProtegidas) && !$auth){  //busca la url actual en las protegidas
                                                             //no logeado
        header('Location: /');
        
       }else{ 
         if(in_array($urlActual, $rutasAdministrador) &&!$idU){ //logeado como usuario normal
          header('Location: /cita');
        }
       }
    
      if($fn){
         call_user_func($fn, $this);
      } else{
        echo "pagina no encontrada";
      }
    
   }


   public function render($view, $datos=[]){

      foreach($datos as $key=>$value){
        $$key=$value;  //variable de variable $$
      }
     ob_start();//inicia para guarda en memoria la vista

     include __DIR__ . "/views/$view.php";

     $contenido= ob_get_clean();  // limpia memoria

     include __DIR__ . "/views/layout.php";
   }

}