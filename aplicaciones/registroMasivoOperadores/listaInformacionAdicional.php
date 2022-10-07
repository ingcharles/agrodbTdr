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
	</header>
	<header>
		<nav>
			<form id="filtrarInformacionAdicional" data-rutaAplicacion="registroMasivoOperadores" data-opcion="listaInformacionAdicional" data-destino="areaTrabajo #listadoItems" >
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
						<option value="">Seleccionar...</option>											
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
				<th>Nombre Sitio</th>
				<th>Nombre Área</th>
				<th>Código Área</th>
			</tr>
		</thead>
		<?php 			
		    $cr = new ControladorRegistroOperador();
		    $contador = 0;
		    $estado = "declararDVehiculo";
		    $itemsFiltrados[] = array();
		    $res = $cr -> listarDatosVehiculoTransporteAnimalesPorIdProvinciaPorEstado($conexion, $_POST['numeroIdentificacion'], $_POST['provinciaFiltro'], $estado);

		         while($fila = pg_fetch_assoc($res)){
		             echo '<tr id="' . $fila['id_sitio'] . '-' . $fila['id_operacion'] . '"
							class="item"
							data-rutaAplicacion="registroMasivoOperadores"
							data-opcion="declararDatosVehiculoTransporteAnimalesVivos"
							ondragstart="drag(event)"
							draggable="true"
							data-destino="detalleItem">
							<td>'.++$contador.'</td>
                            <td>'.$fila['identificador_operador'].'</td>
							<td>'.$fila['nombre_operador'].'</td>
							<td>'.$fila['provincia_sitio'].'</td>
							<td>'.$fila['nombre_sitio'].'</td>
                            <td>'.$fila['nombre_area'].'</td>
                            <td>'.$fila['codigo_area'].'</td>
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

	$("#filtrarInformacionAdicional").submit(function(event){   
		event.preventDefault();			
		if(($('#numeroIdentificacion').val() != ''))	
			abrir($('#filtrarInformacionAdicional'),event, false);
		else		
			$('#mensajeError').html('<span class="alerta">Por favor ingreso datos para realizar la consulta</span>');		
	});	
</script>
