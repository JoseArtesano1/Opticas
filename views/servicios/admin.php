<main class="contenedor seccion">
        <h1>Administrador</h1>
  <?php 
   if($resultado){
     $mensaje=mostrarNotificacion(intval($resultado));  //intval convierte el string a  entero
     if($mensaje){ ?>
      <p class="alerta exito"> <?php echo s($mensaje) ?></p>
     <?php  }
     }
  ?>
        <a href="/tecnicos/crear" class="boton boton-verde">Nuevo Técnico</a>
        <a href="/servicios/crear" class="boton boton-verde">Nuevo Servicio</a>
   <h2>Servicios</h2>
        <table class="ServiciosTabla tablasConf">
        <thead>
          <tr>
            <th>ID</th>
            <th>Denominacion</th>
            <th>Duración</th>
            <th>Técnico</th> 
            <th>Imagen</th> 
         </tr>
        </thead>
        <!-- mostrar los resultados -->
        <tbody>
       <?php foreach($servicios as $servicio):?>
          <tr>
            <td><?php echo $servicio->idServicios; ?></td>
            <td><?php echo $servicio->denominacion; ?></td>
            <td><?php echo $servicio->duracion; ?></td>
            <td><?php echo $servicio->idTecnicos; ?></td>
            <td><img src="/imagenes/<?php echo $servicio->imagen;?>" class="imagen-tabla"></td>
            <td>
              <form method="POST" class="w-100" action="/servicios/eliminar">
                 <input type="hidden"  
                 name="id" value="<?php echo $servicio->idServicios;?>"> 
                 <input type="hidden"  
                 name="tipo" value="servicio"> 
                 <input type="submit" class="boton-rojo-block" value="Eliminar">
              </form> 
              <a href="/servicios/actualizar?idServicios=<?php echo $servicio->idServicios;?>" 
               class="boton-amarillo-block">Actualizar</a>
            </td>
          </tr>
         <?php endforeach; ?>
        </tbody>
        </table>
        <!--TECNICOS  -->
        <h2>Técnicos</h2>
        <table class="tablasConf">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
          </tr>
        </thead>
        <!-- mostrar los resultados -->
        <tbody>
       <?php foreach($tecnicos as $tecnico): ?>
          <tr>
            <td><?php echo $tecnico->idTecnicos; ?></td>
            <td><?php echo $tecnico->nombre;?></td>
            <td>
                <form method="POST" class="w-100" action="/tecnicos/eliminar">
                 <input type="hidden"  
                 name="id" value="<?php echo $tecnico->idTecnicos;?>"> 
                 <input type="hidden"  
                 name="tipo" value="tecnico"> 
                 <input type="submit" class="boton-rojo-block" value="Eliminar">
                </form> 
                <a href="/tecnicos/actualizar?idTecnicos=<?php echo $tecnico->idTecnicos;?>"
                class="boton-amarillo-block">Actualizar</a>
            </td>
          </tr>
        <?php endforeach; ?> 
        </tbody>
        </table>
<!-- USUARIOS -->
    <h2>Usuarios Validados</h2>
        <table class="usuariosTabla tablasConf tablaGeneral">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Email</th>
          </tr>
        </thead>
        <!-- mostrar los resultados -->
        <tbody>
       <?php foreach($usuarios as $usuario): ?>
          <tr>
            <td><?php echo $usuario->idUsuarios; ?></td>
            <td><?php echo $usuario->nombre;?></td>
            <td><?php echo $usuario->telefono;?></td>
            <td><?php echo $usuario->correo;?></td>
            <td>
                <form method="POST" class="w-100" action="/paginas/eliminar">
                 <input type="hidden"  
                 name="id" value="<?php echo $usuario->idUsuarios;?>"> 
                 <input type="hidden"  
                 name="tipo" value="usuario"> 
                 <input type="submit" class="boton-rojo-block" value="Eliminar">
                </form> 
               
            </td>
          </tr>
        <?php endforeach; ?> 
        </tbody>
        </table>
<!-- CITAS -->
        <h2>Citas</h2>
          <form method="POST" class="w-100" action="">
               <select name="id_servicio"  id="id_servicio"   onchange="seleccion();">
                  <option selected value="">--Seleccione Servicio--</option>                  
                  <?php foreach($servicios as $servicio): ?>
                     <option 
                       value="<?php echo s($servicio->idServicios);?>">
                        <?php echo s($servicio->denominacion); ?>
                      </option>
                   <?php endforeach; ?>
                </select>
          </form>
        <table class="CitasTabla tablasConf tablaGeneral" id="origenTabla1">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Email</th>
            <th>Servicio</th>
            <th>Fecha</th>
            <th>Hora Inicio</th> 
            <th>Hora Final</th> 
         </tr>
        </thead>
        <!-- mostrar los resultados -->
        </table>
</main>