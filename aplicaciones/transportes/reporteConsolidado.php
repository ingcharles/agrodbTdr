<header>
	<h1>Reporte vehículos nivel nacional.</h1>
	<nav>
	<form id="reporteConsolidado" data-rutaAplicacion="transportes" data-opcion="listaVehiculoConsolidadoReporte" data-destino="detalleItem">
		<table class="filtro">
			<tr>
				<th>Que contenga</th>
				<td>placa:</td>
				<td><input name="placa" type="text" /></td>		
			</tr>	
			<tr>
				<th>Entre las fechas</th>
				<td>inicio:</td>
				<td><input type="text" name="fi" id="fechaInicio" /></td>
				<td>fin:</td>
				<td><input type="text" name="ff" id="fechaFin" /></td>
			</tr>
			<tr>
				<td colspan="5"><button>Filtrar lista</button></td>
			</tr>
		</table>
		</form>
		
	</nav>
</header>
<!-- >div id="tabla"></div-->
<script>

	$("#reporteConsolidado").submit(function(e){
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
