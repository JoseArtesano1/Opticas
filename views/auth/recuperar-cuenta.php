



<main>
 <div class="contenedor-citas">
   <div class="imagen"></div>
    <div class="app">
      <div class="seccion contenedor" id="consultar">
       <fieldset class="listado-servicios">
         <h1>RECUPERAR CUENTA</h1>
         <h2>ESPERE NOTIFICACIÓN O NUEVA PAGINA</h2>
          <div id="origenTabla">
          </div>
       </fieldset>
     </div>
      <section class="seccion contenedor" >

        <?php foreach($alertas as $key=>$mensajes):
              foreach($mensajes as $mensaje): ?>
                <div class="alerta <?php echo $key;?>">
            <?php echo $mensaje; ?>
            </div>
         <?php endforeach;
             endforeach;?>
       <?php if(!$error) : ?>
         <form  method="POST" class="formulario" >
             <fieldset>
                  <legend>Renueva PassWord</legend>
                  <label for="password">PassWord</label>
                  <input type="password" 
                  placeholder="Tu nueva PassWord" 
                  id="acceso" 
                  name="acceso">
                  
               </fieldset>
          
               <input type="submit" value="Nueva" class="boton boton-verde">
                        
          </form>
          <?php endif; ?>
      </section>
   </div>
 </div>
</main>
