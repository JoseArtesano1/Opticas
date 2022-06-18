<main class="contenedor seccion contenido-centrado" id="fondologin">
        <h1>Iniciar Sesión</h1>
         
         <?php foreach($errores as $error):?>
          <div class="alerta error">
          <?php echo $error; ?>
          </div>
         <?php endforeach; ?>
         <?php  if($miUsuario===null): ?> 
          <?php else: ?>
            <div class="comunicado"> <p>En Breve será activado</p>  </div>
          <?php endif; ?>
          <?php  if($mensajes): ?> 
                     <p class="alerta error"> <?php echo s($mensajes) ?></p>
          <?php endif; ?>

        <form class="formulario" id="formularioLogin" method="POST" action="/login" >
        <fieldset>
                <legend>Email y Password</legend>
               
                <label for="correo">E-mail</label>
                <input type="email" name="correo"  placeholder="Tu Email" id="correo" >

               <label for="acceso">Password</label>
                <input type="password" name="acceso" placeholder="Tu password" id="acceso" >
               
            </fieldset>
            <input type="submit" value="Iniciar Sesión" class="boton boton-verde">
        </form>
        <?php 

      if($resultado){
       $mensaje=mostrarNotificacion(intval($resultado));  //intval convierte el string a  entero
       if($mensaje){ ?>
       <p class="alerta exito"> <?php echo s($mensaje) ?></p>
       <?php  }
      }
      ?>
    </main>