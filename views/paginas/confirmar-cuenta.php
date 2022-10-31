
 <main>
 <div class="contenedor-citas">
   <div class="imagen"></div>
    <div class="app">
      <div class="seccion contenedor" id="consultar">
       <fieldset class="listado-servicios">
         <h1>CONFIRMACIÓN DE CUENTA</h1>
         <h2>ESPERE NOTIFICACIÓN</h2>
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
      </section>
   </div>
 </div>
</main>
