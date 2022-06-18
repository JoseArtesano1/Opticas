
 <fieldset>
                <legend>Alta Servicios</legend>
                <label for="denominacion">Nombre</label>
                <input type="text" placeholder="nombre Servicio" name="servicio[denominacion]"
                   id="denominacion" value="<?php echo s($servicio->denominacion ); ?>">
                <label for="duracion">Tiempo</label>
                <input type="number" id="duracion" min="5" max="60" step=""
                   name="servicio[duracion]" value="<?php echo s($servicio->duracion); ?>">
                <label for="imagen">Imagen</label>
                <input type="file" id="imagen" accept="image/jpeg, image/png" name="servicio[imagen]">
                <?php if($servicio->imagen):?>
                    <img src="/imagenes/<?php echo $servicio->imagen ?>" class="imagen-small">           
                <?php endif; ?>

                <label for="idTecnicos">Técnico</label>
                <select name="$servicio[idTecnicos]" id="idTecnicos">
                   <option selected value="">--Seleccione Técnico--</option>
                   <?php foreach($tecnicos as $tecnico):?>
                      <option <?php echo $servicio->idTecnicos===$tecnico->idTecnicos?'selected':''?>  value="<?php echo s($tecnico->idTecnicos);?>">
                      <?php echo s($tecnico->nombre) ;?>
                      </option>
                   <?php endforeach;?>
                </select>
              
</fieldset>