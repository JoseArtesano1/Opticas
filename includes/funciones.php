<?php
      
define('TEMPLATES_URL', __DIR__ . "/templates");
define('FUNCIONES_URL', __DIR__ . '/funciones.php');
define('CARPETA_IMAGENES', $_SERVER['DOCUMENT_ROOT'] . '/imagenes/');

function incluirTemplate(string $nombre, bool $inicio=false){  //parametro por defecto

    include TEMPLATES_URL . "/${nombre}.php";
    
}

function estarAutenticado(){

    session_start();

    $auth=$_SESSION['login']??null;
    $idU=$_SESSION['id']??null;

    if(!$auth){
      header('Location: /');
    }elseif(!$idU){
      header('Location: /cita');
    }
}


function SumaHoras( $horat, $minutos_sumar ) 
{ 
  $minutoAnadir=$minutos_sumar;
  $segundos_horaInicial=strtotime($horat);
  $segundos_minutoAnadir=$minutoAnadir*60;
  $nuevaHora=date("H:i:s",$segundos_horaInicial+$segundos_minutoAnadir);
  return $nuevaHora;
}  


 //obtener intervalo
function intervaloHora($hora_inicio, $hora_fin, $intervalo ) {
  $hora_inicio = new DateTime( $hora_inicio );
  $hora_fin    = new DateTime( $hora_fin );
  $hora_fin->modify('+1 second'); // Añadimos 1 segundo para que nos muestre $hora_fin
          
  // Establecemos el intervalo en minutos        
  $intervalo = new DateInterval('PT'.$intervalo.'M');

  // Sacamos los periodos entre las horas
  $periodo   = new DatePeriod($hora_inicio, $intervalo, $hora_fin);        

  foreach( $periodo as $horaP ) {
          // Guardamos las horas intervalos 
      $intervaloHoras[] =  $horaP->format('H:i:s');
  }

  return $intervaloHoras;
}




function debuguear($variable){
 echo "<pre>";
  var_dump($variable);
 echo "</pre>";
 exit;
}


//escapar html
function s($html):string{
  $s=htmlspecialchars($html);

  return $s;
}

//validar tipo contenido

function validarTipoContenido($tipo){
   $tipos=['servicio', 'tecnico', 'cita', 'festivo', 'usuario'];
  return in_array($tipo, $tipos);
}

//mostrar los mensajes

function mostrarNotificacion($codigo){
  $mensaje='';

  switch($codigo){

      case 1:
          $mensaje="Creado correctamente";
          break;
      case 2:
          $mensaje="Actualizado correctamente";
          break;
      case 3:    
          $mensaje="Eliminado correctamente";
          break;
      case 4:
          $mensaje="No se puede Eliminar el Servicio, Elimina sus citas";
          break;
      case 5:
          $mensaje="No se puede Eliminar el Técnico, Elimina su servicio";
          break;
      case 6:
          $mensaje="Creado correctamente, recibira un correo con sus claves";
          break;
      case 7:
          $mensaje="Eres un bot";
          break; 
      default:
         $mensaje=false;
         break;

  }

  return $mensaje;
}


function validarORedireccionar(string $url ,$idEspecial){
  //obtener el parametro id del index
  $id=$_GET[$idEspecial];

  //validar la url por id valido
  $id=filter_var($id,FILTER_VALIDATE_INT);

  if(!$id){
      header("Location: ${url}");
  }

  return $id;
}


function correo($mail, $nombre, $movil, $email,$fecha, $hora, $horafinal, $servicio, $crear,$rol){
      //configurar SMTP
      $mail->isSMTP();
     // $mail->Host='smtp.gmail.com';  //especifico de gmail
      $mail->Host='smtp.mailtrap.io'; 
      $mail->SMTPAuth=true;
      $mail->Username='5ea1ba94030e99';
      $mail->Password='7d97af4c33a407';
      $mail->SMTPSecure='tls';   //canal seguro
      $mail->Port=2525;

      //configurar el contenido de email
      $mail->setFrom('pruebaproyectosjr@gmail.com', 'OpticaCitas'); //quien envia el email
      if($rol){
        $mail->addAddress('pruebaproyectosjr@gmail.com', 'OpticaCitas'); //quien le llega o recibe
      }else{
        
        $mail->addAddress($email, 'OpticaCitas'); //quien le llega o recibe
      }
     
      if ($crear){
        $mail->Subject='Tienes una nueva Cita';
      }else{
        $mail->Subject='Cita cancelada';
      }
      

     //habilitar html
     $mail->isHTML(true);
      $mail->CharSet='UTF-8';  //PARA admitir acentos y otras cosas
      // $mail->msgHTML("NOMBRE: " . $nombre . " TELEFONO: " . $movil . " EMAIL: " . $email . " SERVICIO: " . $servicio .  " FECHA: " . $fecha . " INICIO: " . $hora . " FIN: " . $horafinal);
     if($rol){
      $contenido= '<html>';
      $contenido .='<p>Tienes un nuevo mensaje</p>';
      $contenido .='<p>NOMBRE: ' . $nombre  . '</p>';
      $contenido .='<p>TELEFONO: ' . $movil  . '</p>';
      $contenido .='<p>EMAIL: ' . $email  . '</p>';
      $contenido .='<p>SERVICIO: ' . $servicio  . '</p>';
      $contenido .='<p>FECHA: ' . $fecha  . '</p>';
      $contenido .='<p>INICIO: ' . $hora  . '</p>';
      $contenido .='<p>FIN: ' . $horafinal  . '</p>';
      $contenido .= '</html>';

      $mail->Body=$contenido;

     }else{
      $contenido= '<html>';
      $contenido .='<p>Su cita ha sido cancelada</p>';
      $contenido .='<p>SERVICIO: ' . $servicio  . '</p>';
      $contenido .='<p>FECHA: ' . $fecha  . '</p>';
      $contenido .='<p>INICIO: ' . $hora  . '</p>';
      $contenido .='<p>FIN: ' . $horafinal  . '</p>';
      $contenido .= '</html>';

      $mail->Body=$contenido;

     }
         //  $mail->AltBody='Esto es texto alternativo sin html' . $v['nombre'] . $v['fecha'] ;
            //enviar el email
            if($mail->send()){
            return  $mensaje= "mensaje enviado";
            }else{
            return  $mensaje= "no se pudo enviar";
            }

        
}

function correoAlta($mail, $correo, $password, $alta){

   //configurar SMTP
   $mail->isSMTP();
      $mail->Host='smtp.mailtrap.io'; 
      $mail->SMTPAuth=true;
      $mail->Username='5ea1ba94030e99';
      $mail->Password='7d97af4c33a407';
      $mail->SMTPSecure='tls';   //canal seguro
      $mail->Port=2525;


    //configurar el contenido de email
    $mail->setFrom('pruebaproyectosjr@gmail.com', 'OpticaCitas'); //quien envia el email
    if($alta){
           $mail->addAddress($correo, 'OpticaCitas');  //quien recibe
    }else{
          $mail->addAddress('pruebaproyectosjr@gmail.com', 'OpticaCitas'); //quien recibe
    }
       

    $mail->Subject='Alta Usuario';
    //habilitar html
    $mail->isHTML(true);
    $mail->CharSet='UTF-8';  //PARA admitir acentos y otras cosas
      
      $contenido= '<html>';
      $contenido .='<p>Su contraseña y usuario</p>';
      $contenido .='<p>Usuario: ' . $correo  . '</p>';
      $contenido .='<p>Contraseña: ' . $password  . '</p>';
      $contenido .= '</html>';

      $mail->Body=$contenido;
  
      if($mail->send()){
        return  true;
        }else{
        return false;
        }

}