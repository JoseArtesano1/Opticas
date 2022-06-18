

 <main class="contenedor seccion">
        <h1>Crear Técnico</h1>
        <a href="/admin" class="boton boton-verde">Volver</a>
        <?php  foreach($errores as $error): ?>
            <div class="alerta error">
            <?php echo $error; ?>
            </div>
         <?php   endforeach?>
        <form action="/tecnicos/crear" class="formulario" method="POST">
        <?php include __DIR__ . '/formulario.php';  ?>
                 <input type="hidden"  
                 name="tipo" value="tecnico"> 
             <input type="submit" value="Crear" class="boton boton-verde">
         
        </form>

        <form action="" class="formulario" method="POST"   enctype="multipart/form-data">
          <fieldset>
          <legend>Alta Festivos</legend>
           <label for="archivo">Festivos</label>
           <input type="file" name="festivo[dia]" id="dia">
          </fieldset>
                 <input type="hidden"  
                 name="tipo" value="festivo"> 
           <input type="submit" value="Carga Festivos" class="boton boton-verde">
        </form>
        <?php if($mensaje): ?>
                <p class="alerta error"> <?php echo s($mensaje) ?></p>
        <?php endif?>
    </main>