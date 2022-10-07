<header>
	<nav><?php echo $this->listaBotones;?></nav>
</header>

<div id="paginacion" class="normal"></div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Ventanilla</th>
			<th>Código</th>
			<th>Unidad Asignada</th>
			<th>Provincia</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody></tbody>
</table>

<script>
	$(document).ready(function () {
		construirPaginacion($("#paginacion"),<?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
		$("#listadoItems").removeClass("comunes");
	});

	function fn_filtrar() {
		 $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
		$("#paginacion").html("<div id='cargando'>Cargando...</div>");
	    $.post("<?php echo URL ?>SeguimientoDocumental/Ventanillas/actualizarVentanillas",
	      	function (data) {
	            construirPaginacion($("#paginacion"), JSON.parse(data));
	        });
	}

	$("#tablaItems").click(function () {});
</script>
