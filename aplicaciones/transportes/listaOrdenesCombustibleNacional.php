<?php
session_start();
?>

<header>
	<h1>Eliminación de Órdenes de Combustible</h1>
	<nav>
	<form id="listaOrdenesCombustibleNacional" data-rutaAplicacion="transportes" data-opcion="listaOrdenesCombustibleNacionalFiltrado" data-destino="tabla">
		<table class="filtro">
			<tr>
				<th>Orden</th>

				<td>número:</td>
				
				<td>
					<input type="text" id="ordenCombustible" name="ordenCombustible" required="required"/>
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
	$("#listaOrdenesCombustibleNacional").submit(function(e){
		abrir($(this),e,false);
	});
	
	$(document).ready(function(){
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Seleccione una orden de combustible para eliminar.</div>');
	});
</script>
