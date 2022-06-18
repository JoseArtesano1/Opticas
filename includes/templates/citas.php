<?php 

if(!isset($_SESSION)){
  session_start();
}

$emailPasado=$_SESSION['usuario'];

use App\Servicios;
use App\Citas;
use App\Festivos;
use App\Usuarios;
use Intervention\Image\ImageManagerStatic as Image;


//obtener el usuario


//require __DIR__ . '/../config/database.php';

//$db=conectarDB();

$fecha_actual = date("d-m-Y");

$usuario=Usuarios::find($emailPasado);

/* $queryUsuario="SELECT * FROM usuarios WHERE correo='${emailPasado}'";
$resultadoUsuario=mysqli_query($db,$queryUsuario);
$idUsuario=mysqli_fetch_assoc($resultadoUsuario);
 */
$cita= new Citas;
$errores=Citas::getErrores();

$servicios=Servicios::all();

$festivo= new Festivos;

/* $query="SELECT * FROM servicios";
$servicios=mysqli_query($db,$query); */

//arreglo de errores
//$errores=[];

// datos para tabla


//funciones


$existe=true;

//propiedades
/* $fecha='';
$h='';
$servicio=''; 
$id=''; */

 if($_SERVER['REQUEST_METHOD']==='POST'){
  
  $cita= new Citas($_POST['cita']);
  $cita->id_usuario=$usuario->idUsuarios;
 
 //para intervalos
  $hora=date("H:i:s", strtotime($cita->hora)); 
  //convertir formato para comprobar su existencia en festivos
  $valor=  date("d/m/Y", strtotime($cita->fecha)) ; 


  // $errores=$cita->validarCita($valor);
 



 
  

  /*  $fecha=mysqli_real_escape_string($db, $_POST['fecha']);
 
  $h= mysqli_real_escape_string($db, $_POST['duracion']);
  $hora=date("H:i:s", strtotime($h)); //para intervalos
   $servicio=mysqli_real_escape_string($db, $_POST['idservicios']);
   $id=$idUsuario['idUsuarios'];

  
   //convertir formato para comprobar su existencia en festivos
    $valor=  date("d/m/Y", strtotime($fecha)) ; 
 
   // obtener festivos
    $consulta="SELECT dia FROM festivos WHERE dia='${valor}'";
    $dias=mysqli_query($db,$consulta);

     
    if($dias->num_rows){   //para saber si tiene registros
    $errores[]= "Es festivo";
    } 


  
   if(!$fecha){
    $errores[]="Debe añadir la fecha de la cita";
   }

   if(!$h){
 
    $errores[]="Debe añadir la hora de la cita";
 
   } 
      

   if(!$servicio){
    $errores[]="Debe seleccionar un servicio";
   }
 */


  /* $consultaHora="SELECT hora, duracion, imagen FROM citas, servicios WHERE id_servicio= idServicios and fecha='${fecha}'";
  $horario=mysqli_query($db,$consultaHora); */
   $horario=$cita->findAll($cita->fecha,'fecha',false);
 
  if($horario->num_rows){
   
      $filas=$horario->num_rows;

    //intervalo cliente
    $servicio= Servicios::find($cita->id_servicio);
    
      /* $consultarServicio="SELECT * FROM servicios WHERE idServicios= '${servicio}'";
      $resultadoServicio=mysqli_query($db, $consultarServicio);
      $servicioCliente=mysqli_fetch_assoc($resultadoServicio); */
      //obtener hora fin
     /*  if($servicioCliente['duracion']=null){
        $errores[]="primero fecha";
      } */

    /*   if($servicio->duracion =null){
        $errores="primero fecha";
      } */

       $hora_finalCliente=SumaHoras($hora,$servicio->duracion);
       $intervalosCliente=intervaloHora($hora,$hora_finalCliente,1);

       if(in_array("13:59:00",$intervalosCliente) || in_array("19:59:00",$intervalosCliente) ){
        
      //  $errores[]="hora ocupada o fuera de Límite";

        $existe=false;
        
      }

     $i=1;
    while($i<=$filas && $existe ){
    
      $horas=$horario->fetch_assoc();
      // $horas=$cita->findAll($cita->fecha,'fecha',true);
      //intervalo existente en bd
        $hora_finalBD=SumaHoras($horas['hora'],$horas['duracion']);
      
        foreach(intervaloHora($horas['hora'], $hora_finalBD, 1) as $intervaloBD){

            if(in_array($intervaloBD,$intervalosCliente)){
                                
               $existe=false;
                
                break; 
            }else{
   
               $existe=true;
             }
        }
        
        $i++;

    }



    
   
    

  //  if(!$existe){
  //   $errores[]="hora ocupada o fuera de Límite";
  //   }
  $errores=$cita->validarCita($valor, $existe);
 
    if(empty($errores)){
       $cita->guardar();
    /*   $query="INSERT INTO citas (fecha, hora, id_servicio, id_usuario) VALUES ('$fecha',
      '$hora', '$servicio', '$id')"; */
     //  $carga=mysqli_query($db, $query); 
    }

 } else{

  $errores=$cita->validarCita($valor, $existe);
  //debuguear($cita->atributos());
  //debuguear($errores);
     if(empty($errores)){
      $cita->guardar();
      /*  $query="INSERT INTO citas (fecha, hora, id_servicio, id_usuario) VALUES ('$fecha',
       '$hora', '$servicio', '$id')"; */
      //   $carga=mysqli_query($db, $query); 
     }

 }

 
 /*  echo "<pre>";
var_dump($errores);
  echo "</pre>"; 
 */


 
} 


?>
   
  

    <form class="formulario" method="POST" action="cita.php">
    <?php  foreach($errores as $error): ?>
            <div class="alerta error">
            <?php echo $error; ?>
            </div>
    <?php   endforeach?>
    <fieldset>
                <legend>Organiza Tú Cita</legend>
               
                <label  for="fecha">Fecha</label>
                        <input 
                            type="date"  value=""
                            id="fecha"  step="1"
                            min="<?php echo date("Y-m-d",strtotime($fecha_actual."+ 2 days"))?>" 
                            name="cita[fecha]">
    
                <label for="hora">Tiempo</label>
                         <input 
                         type="time" value=""
                         id="hora" 
                         name="cita[hora]">

                <label >Servicios</label>
            <div class="listado-servicios">
                <select name="cita[id_servicio]"  id="id_servicio">
                     
                  <option value="">--Seleccione Servicio--</option>                  
                  <?php foreach($servicios as $servicio): ?>
                  
                  <div class="servicio">
                     
                       <option <?php echo $cita->id_servicio===$servicio->idServicios? 'selected': ''; ?>
                       value="<?php echo $servicio->idServicios;?>">
                        
                        <?php echo $servicio->denominacion; ?>
                       </option>
                       
                   </div>
               
                 <?php endforeach; ?>
               </select>
              
           </div>    
             
   </fieldset>
          <input type="submit" class="boton-rojo-block" value="Aceptar">
         
</form> 

