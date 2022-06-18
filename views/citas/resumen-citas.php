<main>
<?php 

if($resultado){
 $mensaje=mostrarNotificacion(intval($resultado));  //intval convierte el string a  entero
 if($mensaje){ ?>
 <p class="alerta exito"> <?php echo s($mensaje) ?></p>
 <?php  }
}
?>

    <section class="seccion contenedor">
    <?php  if(!empty($horario)): ?> 
     <table class="ConsultaTabla tablasConf">
       <thead>
         <tr>
           <th>Servicio</th>
           <th>Fecha</th>
           <th>Hora Inicio</th>
           <th>Hora Final</th> 
        </tr>
       </thead>
       <!-- mostrar los resultados -->
       <tbody>
         
      <?php foreach($horario as $row):?>
         <tr>
            <td><img src="/imagenes/<?php echo $row['imagen'];?>" class="imagen-tabla"><?php echo $row['denominacion']; ?></td>
            <td><?php echo date("d/m/Y", strtotime($row['fecha'])); ?></td>
           <td><?php echo $row['hora']; ?></td>
           <td><?php echo SumaHoras($row['hora'],$row['duracion']); ?></td>
              <td>
              <form method="POST" class="w-100" id="miform">
                 <input type="hidden"  
                 name="idCita" value="<?php echo $row['idcitas'];?>"> 
                 <input type="submit" class="boton-rojo-block" value="Eliminar" onclick="return confirm('¿Realmente desea eliminar?')">
               </form> 
              </td>         
         </tr>
      <?php endforeach; ?> 

       </tbody>
   </table>
   <?php else: ?>
      <div class="comunicado"> <p>No tiene citas</p>  </div>
      
   <?php endif; ?>
   <picture>
         <source srcset="build/img/monturas.webp" type="image/webp">
         <source srcset="build/img/monturas.jpg" type="image/jpeg">
         <img src="build/img/monturas.jpg" alt="Image logo" loading="lazy">
     </picture>
 </section>

</main>