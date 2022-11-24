<header>
	<nav><?php echo $this->panelBusqueda; ?></nav>
	<nav><?php echo $this->crearAccionBotones(); ?></nav>
</header>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Id</th>
			<th>Identificador</th>
			<th>Fecha ingreso</th>
			<th>Identificador backup</th>
			<th>Días Planificados</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody></tbody>
</table>

<script>
	$(document).ready(function() {
		construirPaginacion($("#paginacion"), <?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
		$("#listadoItems").removeClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
	});
	$("#_eliminar").click(function() {
		if ($("#cantidadItemsSeleccionados").text() > 1) {
			alert('Por favor seleccione un registro a la vez');
			return false;
		}
	});

	$("#tablaItems").click(function() {});


	//Funciones quer permiten filtrar
	$("#btnFiltrar").click(function() {

			fn_filtrar();
		}

	);

	function fn_filtrar() {

		$("#paginacion").html("<div id='cargando'>Cargando...</div>");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');

		$.post("<?php echo URL ?>VacacionesPermisos/CronogramaVacaciones/listarSolicitudeCronogramaVacacion", {
				estado_cronograma_vacacion: $('#estado_cronograma_vacacion').val()
			},

			function(data) {
				if (data.estado === 'FALLO') {
					construirPaginacion($("#paginacion"), JSON.parse(data.contenido));
					mostrarMensaje(data.mensaje, "FALLO");
				} else {
					construirPaginacion($("#paginacion"), JSON.parse(data.contenido));
				}
			}, 'json');
	}
</script>