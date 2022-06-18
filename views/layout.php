<?php 

  if(!isset($_SESSION)){
    session_start();
  }
  $auth=$_SESSION['login']?? false;
  $idU=$_SESSION['id']??false ;

  if(!isset($inicio)){
    $inicio=false;
  }
 
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
  <link rel="stylesheet" href="../build/css/app.css">
  <link rel="icon" href="data:,">
</head>
<body onload= "<?php echo $inicio ?'interval()':'' ?>">
  <header class="header  <?php echo $inicio ?'inicio':'' ?>">
      <div class="contenedor contenido-header">
        <div class="barra">
            <a href="/">
              <h1>Optica Jose Antonio</h1>
            </a>
           
         <nav class="navegacion">
             <?php if($auth): ?>
              <a href="/logout">Cerrar Sesión</a>
              <a href="/cita">Cita</a>
               <?php if($idU): ?>
                <a href="/admin">Administrador</a>
                <?php endif; ?>
             <?php else: ?>
              <a href="/alta">Alta</a>
             <a href="/login">Login</a>
              <?php endif; ?>
         </nav>
        </div>
       
      </div>
  </header>

<?php echo $contenido;  ?>
  <footer class="footer seccion">
      <div class="contenedor contenedor-footer">
        <nav class="navegacion">
        <?php if($auth): ?>
              <a href="/logout">Cerrar Sesión</a>
              <a href="/cita">Cita</a>
               <?php if($idU): ?>
                <a href="/admin">Administrador</a>
                <?php endif; ?>
             <?php else: ?>
              <a href="/alta">Alta</a>
             <a href="/login">Login</a>
              <?php endif; ?>
        </nav>
      </div>
      <p class="copyright">Todos los derechos reservados <?php echo date('Y'); ?> &copy;</p>
     
</footer>
<script src= "<?php echo isset($inicio) ?'https://unpkg.com/leaflet@1.7.1/dist/leaflet.js':'' ?>"></script>
<script src="../build/js/bundle.min.js"></script>
</body>
</html>