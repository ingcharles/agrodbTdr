<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';
$conexion = new Conexion();
$cv = new ControladorVehiculos();

$talleres = $cv->abrirDatosTalleres($conexion, $_SESSION['nombreLocalizacion']);
?>

<header>
	<h1>Histórico mantenimiento</h1>
	<nav>
	<form id="reporteMantenimiento" data-rutaAplicacion="transportes" data-opcion="listaMantenimientoReporte" data-destino="tabla">
		<table class="filtro">
			<tr>
				<th>Que contenga</th>
				<td>placa:</td>
				<td><input name="placa" type="text" /></td>
				<td>taller:</td>
				<td><select id="taller" name="taller" >
					<option value="">Taller....</option>
					<?php 
						while($fila = pg_fetch_assoc($talleres)){
							echo '<option value="' . $fila['id_taller'] . '">' . $fila['nombretaller'] . '</option>';					
						}
					?>
				</select></td>		
			</tr>
			<tr>
				<th></th>
				<td>número de factura:</td>
				<td><input name="factura" type="text" /></td>
				<td>motivo:</td>
				<td><input name="motivo" type="text" /></td>
			</tr>		
			<tr>
				<th>Entre las fechas de compra</th>
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
	$("#reporteMantenimiento").submit(function(e){
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
