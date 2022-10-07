<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';
$conexion = new Conexion();
$cv = new ControladorVehiculos();
?>

<header>
	<h1>Histórico Siniestros</h1>
	<nav>
	<form id="reporteSiniestro" data-rutaAplicacion="transportes" data-opcion="listaSiniestrosReporte" data-destino="tabla">
		<table class="filtro">
			<tr>
				<th>Que contenga</th>
				<td>placa:</td>
				<td><input name="placa" type="text" /></td>
				<td>motivo:</td>
				<td><select id="tipo_siniestro" name="tipo_siniestro" >
					<option value="">Tipo....</option>
					<option value="Choque">Choque</option>
					<option value="Robo">Robo</option>
				</select></td>		
			</tr>
			<tr>
				<th>Entre las fechas</th>
				<td>inicio:</td>
				<td><input type="text" name="fi" id="fechaInicio" /></td>
				<td>fin:</td>
				<td><input type="text" name="ff" id="fechaFin" /></td>
			</tr>
			<tr>
				<th>Mostrar</th>
				<td>estado:</td>
				<td><select name="estado">
					<option value="">Todos</option>
					<option value="1">Sin notificar</option>
					<option value="1">Pendientes</option>
					<option value="1">Negados</option>
					<option value="1">Aprobados</option>
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
	$("#reporteSiniestro").submit(function(e){
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
