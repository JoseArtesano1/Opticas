<?php  
           $auth=$_SESSION['login']?? false;
           $idU=$_SESSION['id']??false ;
?>
 <main>
<div class="contenedor-citas">
<div class="imagen"></div>
<div class="app">
  <div class="seccion contenedor" id="consultar">
   <fieldset class="listado-servicios">
     <legend>Consulta Disponibilidad</legend>
     <label>Servicio y Duración</label>
     <select name="idServ"  id="idServ" class="combo"   onchange="seleccionServ();">
          <option selected value="">--Seleccione Servicio--</option>         
        <?php foreach($servicios as $servicio): ?>
                <option 
                   value="<?php echo s($servicio->idServicios);?>">
                   <?php echo s($servicio->denominacion); ?>-- <?php echo s($servicio->duracion); ?> min
                </option>
          <?php endforeach; ?>
     </select>
   
    <label  for="buscarFecha">Fecha</label>
    <input type="date"  min="<?php echo date("Y-m-d",strtotime($fecha_actual."+ 2 days"))?>"
      id="buscarFecha" 
      onchange="seleccionServFecha();">
    <div id="origenTabla">
    </div>
    </fieldset>
 </div>
   <section class="seccion contenedor" >
       <?php if(!$idU): ?> 
       <a href="/resumen-citas" class="boton boton-verde">Mis Citas</a>
       <?php endif; ?>
       <form class="formulario" method="POST" action="/cita" >
          <?php  foreach($errores as $error): ?>
                <div class="alerta error">
               <?php echo $error; ?>
               </div>
           <?php   endforeach?>
          <fieldset>
                <legend>Organiza Tú Cita</legend>
                <?php if($mensajes): ?>
                <p class="alerta error"> <?php echo s($mensajes) ?></p>
                <?php endif?>
                <label  for="fecha">Fecha</label>
                        <input required  onchange="cambioFechas();"
                            type="date"  value=""
                            id="fecha"  step="1"
                            min="<?php echo date("Y-m-d",strtotime($fecha_actual."+ 2 days"))?>" 
                            max="<?php echo date("Y-m-d",strtotime($fecha_actual."+ 60 days"))?>"
                            name="cita[fecha]">
                <label for="hora">Tiempo</label>
                         <input required  onchange="controlHorario();"
                         type="time" value=""
                         id="hora" 
                         name="cita[hora]">
                <label >Servicios</label>
               <div >
                <select name="cita[id_servicio]"  id="id_servicio">
                  <option value="">--Seleccione Servicio--</option>                  
                  <?php foreach($servicios as $servicio): ?>
                  <div >
                       <option <?php echo $cita->id_servicio===$servicio->idServicios? 'selected': ''; ?>
                       value="<?php echo s($servicio->idServicios);?>">
                        <?php echo s($servicio->denominacion); ?>
                       </option>
                   </div>
                  <?php endforeach; ?>
                </select>
              </div>    
            
            </fieldset>
             <input type="submit" class="boton-rojo-block" value="Aceptar">
             
        </form> 
     </section>
 </div>
</div>
</main>