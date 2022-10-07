

<header>
	<h1>Matriz Items Proforma</h1>
	<nav>
	<form id="filtrar" data-rutaAplicacion="poa" data-opcion="listadoItemPOAFiltrados" data-destino="tabla">
			<div data-linea="1">	
				<table class="filtro">
					<tr>
						<th>Que contenga</th>
						<td>archivo:</td>
						<td><input name="archivo" type="text" /></td>
						<td>asunto:</td>
						<td><input name="asunto" type="text" /></td>
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
						<td><select name="estadoProceso" id="estadoProceso">
							<option value="1">Abierto</option>
							<option value="2">Completo</option>
						</select>
						</td>
						<td colspan="5"><button>Filtrar lista</button></td>
					</tr>
				</table>
			</div>
		</form>		
	</nav>
</header>
<div id="tabla"></div>
<script>
	$("#filtrar").submit(function(e){
		abrir($(this),e,false);
	});
	$(document).ready(function(){
		distribuirLineas();
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un ítem para revisarlo.</div>');
		$("#fechaInicio").datepicker();
		$("#fechaFin").datepicker();
	});
	</script>
