<main class="contenedor seccion">
    <?php foreach($errores as $error):?>
          <div class="alerta error">
          <?php echo $error; ?>
          </div>
     <?php endforeach; ?>
     <picture>
         <source srcset="build/img/alta2.webp" type="image/webp">
         <source srcset="build/img/alta2.jpg" type="image/jpeg">
         <img src="build/img/alta2.jpg" alt="Image logo" loading="lazy">
     </picture>
     <h1>Datos Usuario</h1>
     <form action="/alta" class="formulario" method="POST">
              <fieldset>
                  <legend>Información Personal</legend>
                  <label for="nombre">Nombre</label>
                  <input type="text" placeholder="Tú nombre" id="nombre" name="usuario[nombre]" 
                  value="<?php echo s($usuario->nombre) ; ?>"> 
                  <label for="apellidos">Apellidos</label>
                  <input type="text" placeholder="Apellidos" id="apellidos" name="usuario[Apellidos]" 
                  value="<?php echo s($usuario->Apellidos) ; ?>">
                 </fieldset>
              <fieldset>
                <legend>Información contacto</legend>
                <label for="correo">Email</label>
                <input type="email" placeholder="Email" id="correo" name="usuario[correo]">
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response"> 
                <label for="telefono">Teléfono</label>
                <input type="tel" placeholder="Teléfono" id="telefono" name="usuario[telefono]">
                <label for="password">PassWord</label>
                <input type="password" name="usuario[acceso]" placeholder="Tu password" id="acceso" >
                <label for="condiciones">Aceptar Condiciones de uso</label>
                <input type="checkbox" id="condiciones" value="1" name="usuario[condiciones]">
                <a href="/condicion" class="boton boton-verde">Leer Condiciones</a>
              </fieldset>
              <input type="submit" value="Guardar" class="boton-verde">
     </form>
     <script src="https://www.google.com/recaptcha/api.js?render=6LeFbVAcAAAAAPknXiTi1lFbAPjRvCceNWnAcJog"></script>
    <script>
       grecaptcha.ready(function() {
        grecaptcha.execute('6LeFbVAcAAAAAPknXiTi1lFbAPjRvCceNWnAcJog', {action: 'homepage'})
        .then(function(token) {
        document.getElementById('g-recaptcha-response').value=token;
      });
      });
    </script>
    </main>