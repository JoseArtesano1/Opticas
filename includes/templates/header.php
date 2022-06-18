<?php 

  if(!isset($_SESSION)){
    session_start();
  }

  $auth=$_SESSION['login']?? false;
  $idU=$_SESSION['id']??false ;
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Expires" content="0">
   

    <title>Document</title>
    <link rel="stylesheet" href= "<?php echo $inicio ? 'https://unpkg.com/leaflet@1.7.1/dist/leaflet.css': '' ?> " />
    <link rel="stylesheet" href="/build/css/app.css">
</head>
<body onload= "<?php echo $inicio ?'interval()':'' ?>">
    <header class="header  <?php echo $inicio ?'inicio':'' ?>">
        <div class="contenedor contenido-header">
          <div class="barra">
              <a href="/">
                <!-- <img src="build/img/logo.svg" alt="logo Optica"> -->
                <h1>Optica Jose Antonio</h1>
              </a>
              
           <nav class="navegacion">
               
               <?php if($auth): ?>
                <a href="cerrar-sesion.php">Cerrar Sesión</a>
                <a href="cita.php">Cita</a>
                 <?php if($idU): ?>
                  <a href="/admin">Administrador</a>
                  <?php endif; ?>
               <?php else: ?>
                <a href="alta.php">Alta</a>
               <a href="login.php">Login</a>
                <?php endif; ?>
           </nav>
          </div>
          <!-- <h1>Alta Usuario</h1> -->
        </div>
    </header>