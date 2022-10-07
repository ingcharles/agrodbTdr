<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/GoogleAnalitica.php';

$conexion = new Conexion();

?>
	<header>
		<h1>Supervisar ubicación</h1>
	</header>
	<header>
		<nav>
			<form id="filtrarSupervisarSitiosUbicacion" data-rutaAplicacion="registroMasivoOperadores" data-opcion="listaSupervisarUbicacionSitio" data-destino="areaTrabajo #listadoItems" >
			<input type="hidden" name="opcion" value="<?php echo $_POST['opcion']; ?>" />
			<input type="hidden" name="provinciaFiltro" value="<?php echo $_POST['provinciaFiltro']; ?>" />
			<table class="filtro" style='width: 100%;' >
				<tbody>
				<tr>
					<th colspan="4">Buscar operadores</th>					
				</tr>
				<tr>
					<th>Identificación:</th>
					<th ><input type="text" id="numeroIdentificacion" name="numeroIdentificacion" maxlength="13"></th>
				</tr>				
				<tr>
					<th>Provincia:</th>
					<th>
					<select id="provinciaFiltro" name="provinciaFiltro" style='width: 82%;'>	
						<option value="0">Seleccionar...</option>											
						<?php 
							$cc = new ControladorCatalogos();
							$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
							foreach ($provincias as $provincia){
								echo '<option value="' . $provincia['nombre'] . '">' . $provincia['nombre'] . '</option>';
							}
						?>							 
					</select>	
					</th>			
				</tr>						
				<tr>
					<td colspan="4" id="mensajeError"></td>
					<td> <button id='buscar'>Buscar</button></td>
				</tr>
				</tbody>
			</table>
			</form>
		</nav>
</header>
<table>
		<thead>
			<tr>
				<th>#</th>				
				<th>Razón Social</th>
				<th>Nombre</th>
				<th>Provincia</th>	
				<th>Nombre sitio</th>
				<th>Código sitio</th>
			</tr>
		</thead>
		<?php 			
		    $cr = new ControladorRegistroOperador();
		    $contador = 0;
		    $itemsFiltrados[] = array();
		         $res = $cr -> listarSitiosXIdentificadorXProvincia($conexion, $_POST['numeroIdentificacion'], $_POST['provinciaFiltro']);

		         while($fila = pg_fetch_assoc($res)){
					echo '<tr id="'.$fila['identificador_operador'].'-'.$fila['id_sitio'].'"
							class="item"
							data-rutaAplicacion="registroMasivoOperadores"
							data-opcion="abrirSupervisarUbicacionSitio"
							ondragstart="drag(event)"
							draggable="true"
							data-destino="detalleItem">
							<td>'.++$contador.'</td>
							<td>'.$fila['razon_social'].'</td>
							<td>'.$fila['nombre_representante'].' '.$fila['apellido_representante'].'</td>
							<td>'.$fila['provincia'].'</td>
                            <td>'.$fila['nombre_lugar'].'</td>
                            <td>'.$fila['codigo_sitio'].'</td>
						</tr>';
					}	
					
		      
       ?>
	</table>
<script>	
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');												
	});

	$("#filtrarSupervisarSitiosUbicacion").submit(function(event){   
		event.preventDefault();			
		if(($('#numeroIdentificacion').val()!='') || ($('#provinciaFiltro').val()!='0'))	
			abrir($('#filtrarSupervisarSitiosUbicacion'),event, false);
		else		
			$('#mensajeError').html('<span class="alerta">Por favor ingreso datos para realizar la consulta</span>');		
	});	
</script>
