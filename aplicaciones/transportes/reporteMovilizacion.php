<header>
	<h1>Histórico movilización</h1>
	<nav>
	<form id="reporteMovilizacion" data-rutaAplicacion="transportes" data-opcion="listaMovilizacionReporte" data-destino="tabla">
		<table class="filtro">
			<tr>
				<th>Que contenga</th>
				<td>tipo de movilización:</td>
					<td><select id="tipo" name="tipo" >
					<option value="" selected="selected">Tipo....</option>
					<option value="Orden Movilización" >Orden Movilización</option>
					<option value="Salvaconducto" >Salvaconducto</option>
					</select></td>
									
				<td>placa:</td>
				<td><input name="placa" type="text" /></td>		
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
	$("#reporteMovilizacion").submit(function(e){
		abrir($(this),e,false);
	});
	$(document).ready(function(){

		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Seleccione uno o varios items y presione el boton "Generar reporte".</div>');
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
