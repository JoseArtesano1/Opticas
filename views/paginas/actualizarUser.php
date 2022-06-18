
<main class="contenedor seccion">
        <h1>Actualizar Usuario</h1>
        <?php  foreach($errores as $error): ?>
            <div class="alerta error">
            <?php echo $error; ?>
            </div>
         <?php   endforeach?>
         <a href="/admin" class="boton boton-verde">Volver</a>
        <form action="" method="POST" class="formulario" >
        <fieldset>
                  <legend>Información Personal</legend>
                  <label for="nombre">Nombre</label>
                  <input type="text" placeholder="Tú nombre" id="nombre" readonly  
                  value="<?php echo s($usuario->nombre) ; ?>"> 
                 
                  <label for="apellidos">Apellidos</label>
                  <input type="text" placeholder="Apellidos" id="apellidos" readonly 
                  value="<?php echo s($usuario->Apellidos) ; ?>">
                  <label for="telefono">Teléfono</label>
                  <input type="text" placeholder="Apellidos" id="telefono" readonly 
                  value="<?php echo s($usuario->telefono) ; ?>">
                                
                  <label for="estado">Activar si existe el Usuario</label>
                  <input type="checkbox" id="estado" value="1" name="usuario[estado]"  require >
                  
               </fieldset>
          
            <input type="submit" value="Activar" class="boton boton-verde">
                        
        </form>
  
</main>