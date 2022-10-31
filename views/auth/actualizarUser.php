<main class="contenedor seccion">
        <h1>SOLICITAR RECUPERACION PASSWORD</h1>
        <?php foreach($mistake as $key=>$mensajes):
              foreach($mensajes as $mensaje): ?>
                <div class="alerta <?php echo $key;?>">
            <?php echo $mensaje; ?>
            </div>
         <?php endforeach;
             endforeach;?>
             
         <a href="/admin" class="boton boton-verde">Volver</a>
        <form action="/actualizarUser" method="POST" class="formulario"  >
        <fieldset>
                  <legend>Información Personal</legend>
                  <label for="correo">Email</label>
                  <input type="email" 
                  placeholder="Email" 
                  id="correo" 
                  name="correo">
                  
               </fieldset>
          
            <input type="submit" value="Solicitar Recuperar PassWord" class="boton boton-verde">
                        
        </form>
  
</main>