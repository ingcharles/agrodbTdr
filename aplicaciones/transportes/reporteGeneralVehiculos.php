<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();

$sit = $cc->listarLocalizacion($conexion,'SITIOS');

?>

<header>
	<h1>Reporte vehículos nivel nacional</h1>
	<nav>
	<form id="reporteVehiculos" data-rutaAplicacion="transportes" data-opcion="listaVehiculosReporteGeneral" data-destino="tabla">
		<table class="filtro">
			<tr>
				<th>Que contenga</th>
				<td>
					Placa:
				</td>
				<td>
					<input name="placa" type="text" />
				</td>	
				
				<td>
					Cantidad:
				</td>
				
				<td>
					<select id="cantidad" name="cantidad">
						<option value="5">5</option>	
						<option value="10" selected="selected">10</option>	
						<option value="15" >15</option>	
						<option value="20" >20</option>	
						<option value="25" >25</option>
						<option value="40" >40</option>				
					</select>
				</td>	
			</tr>
			
			<tr>
				
				<th>Sitio</th>
				<td colspan="4">
					<select id="sitio" name="sitio">
						<option value="" selected="selected">Todos</option>
							<?php 
								while($fila = pg_fetch_assoc($sit)){
									echo '<option value="' . $fila['nombre'] . '">' . $fila['nombre'] . '</option>';
								}
							?>					
					</select>
				</td>
			</tr>			
			
					
			<tr>
				<th>Entre las fechas</th>
				<td>Inicio:</td>
				<td><input type="text" name="fechaInicio" id="fechaInicio" /></td>
				<td>Fin:</td>
				<td><input type="text" name="fechaFin" id="fechaFin" /></td>
			</tr>
			<tr>
				<th>Mostrar</th>
				<td>Tipo:</td>
				<td>
					<select name="tipo" id="tipo">
						<!-- option value="1">Vehiculos</option-->
						<option value="5">Combustible (Mayor consumo)</option>
						<option value="2">Mantenimiento (Costo)</option>
						<!--option value="7">Menor rendimiento</option-->
						<option value="3">Km. Recorridos (Movilizaciones)</option>
						<option value="4">Siniestros</option>
						<option value="9">Vehiculos dados de baja</option>
						<option value="6">Vehiculos mas antiguos</option>
						<option value="10">Gasolineras (detalle consumo)</option>
						<option value="11">Talleres (detalle consumo)</option>
						<option value="12">Vehículos registrados</option>
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
	$("#reporteVehiculos").submit(function(e){
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
