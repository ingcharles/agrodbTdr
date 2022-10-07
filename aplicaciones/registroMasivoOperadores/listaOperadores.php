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
		<h1>Catastro</h1>
		<nav>
			<?php
			    $ca = new ControladorAplicaciones();
				$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);
				while($fila = pg_fetch_assoc($res)){
					echo '<a href="#"
							id="' . $fila['estilo'] . '"
							data-destino="detalleItem"
							data-opcion="' . $fila['pagina'] . '"
							data-rutaAplicacion="' . $fila['ruta'] . '"
							>'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';				
				}
			?>
		</nav>
	</header>
	<header>
		<nav>
			<form id="filtrarOperadorFiltro" data-rutaAplicacion="registroMasivoOperadores" data-opcion="listaOperadores" data-destino="areaTrabajo #listadoItems" >
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
				<th>RUC</th>
				<th>Razón Social</th>
				<th>Nombre</th>
				<th>Provincia</th>	
			</tr>
		</thead>
		<?php 			
		    $cr = new ControladorRegistroOperador();
		    $contador = 0;
		    $itemsFiltrados[] = array();
				$res = $cr -> listarOperadoresXProvincia($conexion, $_POST['numeroIdentificacion'], $_POST['provinciaFiltro']);				
				if(pg_num_rows($res) == 0){
					echo 'No hay registros.';
				}else{
					while($fila = pg_fetch_assoc($res)){
					echo '<tr id="'.$fila['identificador'].'"
							class="item"
							data-rutaAplicacion="registroMasivoOperadores"
							data-opcion="datosOperadorMasivo"
							ondragstart="drag(event)"
							draggable="true"
							data-destino="detalleItem">
							<td>'.++$contador.'</td>
							<td style="white-space:nowrap;"><b>'.$fila['identificador'].'</b></td>
							<td>'.$fila['razon_social'].'</td>
							<td>'.$fila['nombre_representante'].' '.$fila['apellido_representante'].'</td>
							<td>'.$fila['provincia'].'</td>
						</tr>';
					}			
				}
       ?>
	</table>
<script>	
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		//$('#numeroIdentificacion').ForceNumericOnly();
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un registro para revisarlo.</div>');												
	});

	$("#filtrarOperadorFiltro").submit(function(event){   
		event.preventDefault();			
		if(($('#numeroIdentificacion').val()!='') || ($('#provinciaFiltro').val()!='0'))	
			abrir($('#filtrarOperadorFiltro'),event, false);
		else		
			$('#mensajeError').html('<span class="alerta">Por favor ingreso datos para realizar la consulta</span>');		
	});	
</script>
