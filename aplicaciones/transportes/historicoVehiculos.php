<header>
	<h1>Histórico vehículos</h1>
	<nav>
	<form id="reporteVehiculos" data-rutaAplicacion="transportes" data-opcion="listadoVehiculosFiltrados" data-destino="tabla">
		<table class="filtro">
			<tr>
				<th>Que contenga</th>
				<td>placa:</td>
				<td><input name="placa" type="text" /></td>
				<td>año de fabricación:</td>
				<td><input name="anio" type="text" /></td>		
			</tr>
			<tr>
				<th></th>
				<td>marca:</td>
				<td><input name="marca" type="text" /></td>
				<td>modelo:</td>
				<td><input name="modelo" type="text" /></td>
			</tr>		
			<tr>
				<th>Entre las fechas de compra</th>
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
