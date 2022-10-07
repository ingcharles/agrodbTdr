<?php
session_start();
?>

<header>
	<h1>Revisión de Órdenes Generadas - Nacional</h1>
	<nav>
	<form id="listaOrdenesNacional" data-rutaAplicacion="transportes" data-opcion="listaOrdenesNacionalFiltrado" data-destino="tabla">
		<table class="filtro">
			<tr>
				<th>Orden</th>

				<td>Número:</td>
				
				<td>
					<input type="text" id="numeroOrden" name="numeroOrden" required="required"/>
				</td>
				
				<td>Tipo:</td>
				
				<td>
					<select id="tipoOrden" name="tipoOrden" required="required">
						<option value="Combustible" >Combustible</option>
						<option value="Mantenimiento" >Mantenimiento</option>
						<option value="Movilizacion" >Movilización</option>
					</select>
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
	$("#listaOrdenesNacional").submit(function(e){
		abrir($(this),e,false);
	});
	
	$(document).ready(function(){
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Seleccione una orden para visualizar.</div>');
	});
</script>
