<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

$conexion = new Conexion();
$cv = new controladorVehiculos();

$gasolineras = $cv->abrirDatosGasolineras($conexion,$_SESSION['nombreLocalizacion']);

?>

<header>
	<h1>Histórico carga combustible</h1>
	<nav>
	<form id="reporteCombustible" data-rutaAplicacion="transportes" data-opcion="listaCombustiblesReporte" data-destino="tabla">
		<table class="filtro">
			<tr>
				<th>Que contenga</th>
				<td>placa:</td>
				<td><input name="placa" type="text" /></td>
				<td>gasolinera:</td>
				<td>
					<select id="gasolinera" name="gasolinera">
						<option value="" >Gasolinera....</option>
							<?php 
								while($fila = pg_fetch_assoc($gasolineras)){
									echo '<option value="' . $fila['id_gasolinera'] . '" data-extra="' . $fila['extra'] . '" data-super="' . $fila['super'] . '" data-diesel="' . $fila['diesel'] . '">' . $fila['nombre'] . '</option>';					
								}
							?>
					</select>
				</td>		
			</tr>
			<tr>
				<th></th>
				<td>tipo de combustible:</td>
				<td><select id="tipo" name="tipo" >
					<option value="" selected="selected">Tipo combustible....</option>
					<option value="Extra" >Extra</option>
					<option value="Super" >Super</option>
					<option value="Diesel" >Diesel</option>
					<option value="Ecopais" >Ecopaís</option>
				</select></td>
			</tr>		
			<tr>
				<th>Entre las fechas</th>
				<td>inicio:</td>
				<td><input type="text" name="fi" id="fechaInicio" readonly="readonly" /></td>
				<td>fin:</td>
				<td><input type="text" name="ff" id="fechaFin" readonly="readonly" /></td>
			</tr>
			<tr>
				<th>Mostrar</th>
				<td>estado:</td>
				<td><select name="estado">
					<option value="">Todos</option>
					<option value="1">Asignar Vehículo</option>
					<option value="2">Por imprimir</option>
					<option value="3">Por finalizar</option>
					<option value="4">Cerradas</option>
				</select>
				</td>
				<td colspan="5"><button>Filtrar lista</button></td>
			</tr>
		</table>
		</form>
		
	</nav>
</header>
<div id="tabla"></div>
<script>
	$("#reporteCombustible").submit(function(e){
		abrir($(this),e,false);
	});
	$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
		$("#fechaInicio").datepicker({
		      changeMonth: true,
		      changeYear: true
		    });
		$("#fechaFin").datepicker({
		      changeMonth: true,
		      changeYear: true
		    });
	});
	</script>
