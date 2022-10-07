<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';
require_once '../../clases/ControladorCatalogos.php';

//print_r($_SESSION);

$conexion = new Conexion();
$cv = new ControladorVehiculos();
$cc = new ControladorCatalogos();

$localizacion = $cc->listarLocalizacion($conexion, 'SITIOS');

?>

<header>
	<h1>Reasignación de Vehículos</h1>
	<nav>
	<form id="listaAsignacionVehiculo" data-rutaAplicacion="transportes" data-opcion="listaAsignacionVehiculoFiltrado" data-destino="tabla">
		<table class="filtro">
			<tr>
				<th>Vehículo</th>

				<td>placa:</td>
				
				<td>
					<input type="text" id="placa" name="placa" />
				</td>
			</tr>
			
			<tr>	
				<th>Asignada a</th>

				<td>localización:</td>
				<td>
					<select id="localizacion" name="localizacion">
						<option value="">Seleccione....</option>
						<?php 
							while($fila = pg_fetch_assoc($localizacion)){
									//if(strstr($fila['nombre'], 'Coordinación') || strstr($fila['nombre'], 'Oficina Planta Central') || strstr($fila['nombre'], 'Laboratorios tumbaco')){
										echo '<option value="' . $fila['nombre'] . '">' . $fila['nombre'] . '</option>';
									//}
								}
						?>
						
					</select>
					
					<input type="hidden" name="opcion" value= "	<?php echo $_POST["opcion"];?>">
				</td>
						
			</tr>

			<tr>
				<td colspan="5"><button>Filtrar lista</button></td>
			</tr>
		</table>
		</form>
		
	</nav>
</header>

<div id="tabla"></div>
<script>
	$("#listaAsignacionVehiculo").submit(function(e){
		abrir($(this),e,false);
	});
	
	$(document).ready(function(){
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Seleccione un vehículo para reasignación.</div>');
	});
</script>
