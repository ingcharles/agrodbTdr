<header>
	<h1>Usuarios GUIA</h1>
	<nav>
		<table class="filtro" style='width: 400px;'>
				<tbody>
					<tr>
						<th colspan="2">Buscar:</th>
					</tr>
					<tr >
						<th>Identificador:</th>
						<td colspan="4">
							<input id="identificadorFiltro" type="text" name="identificadorFiltro" style="width: 100%">
						</td>
					</tr>
					<tr>
						<td id="mensajeError"></td>
						<td colspan="5">
							<button id="btnFiltrar">Buscar</button>
						</td>
					</tr>
				</tbody>
			</table>
	</nav>
</header>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Identificador</th>
			<th>Nombre usuario</th>
			<th>Estado</th>
			<th>Observación</th>
		</tr>
	</thead>
	<tbody></tbody>
</table>

<script>
 	$(document).ready(function () {

 		<?php echo $this->codigoJS; ?>

 		$("#listadoItems").removeClass("comunes"); 
 	});

	$("#_eliminar").click(function () {
 		if ($("#cantidadItemsSeleccionados").text() > 1) {
  			alert('Por favor seleccione un registro a la vez');
 			return false;
 		}
 	});

	//Cuando se presiona en Filtrar lista, debe cargar los datos
    $("#btnFiltrar").click(function () {
		fn_filtrar();
	});
 		
	// Función para filtrar
	function fn_filtrar() {
		$("#paginacion").html("<div id='cargando'>Cargando...</div>");
	    $.post("<?php echo URL ?>Usuarios/Usuarios/listarUsuarios",
	    	{
	        	identificador: $("#identificadorFiltro").val()
	        },
	      	function (data) {
	            construirPaginacion($("#paginacion"), JSON.parse(data));
	        });
	    }
 		
 </script>


