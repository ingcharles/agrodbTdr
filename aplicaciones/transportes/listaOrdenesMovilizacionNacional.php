<?php
session_start();
?>

<header>
	<h1>Agregar rutas de Movilización</h1>
	<nav>
	<form id="listaOrdenesMovilizacionNacional" data-rutaAplicacion="transportes" data-opcion="listaOrdenesMovilizacionNacionalFiltrado" data-destino="tabla">
		<table class="filtro">
			<tr>
				<th>Orden</th>

				<td>número:</td>
				
				<td>
					<input type="text" id="ordenMovilizacion" name="ordenMovilizacion" required="required"/>
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
	$("#listaOrdenesMovilizacionNacional").submit(function(e){
		abrir($(this),e,false);
	});
	
	$(document).ready(function(){
		$("#listadoItems").addClass("lista");
		$("#detalleItem").html('<div class="mensajeInicial">Seleccione una orden de movilización para añadir nuevas rutas.</div>');
	});
</script>