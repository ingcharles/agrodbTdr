<header>
	<nav><?php echo $this->panelBusqueda; ?></nav>
	<nav><?php echo $this->crearAccionBotones(); ?></nav>
</header>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Funcionario</th>
			<th>Funcionario backup</th>
			<th>Días planificados</th>
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
		$("#btnFiltrar").click(function () {
			fn_filtrar();
		}

		);

function fn_filtrar() {


$.post("<?php echo URL ?>VacacionesPermisos/CronogramaVacaciones/listarSolicitudesCronogramaVacacion",
	{
		estado_cronograma_vacacion: $('#estado_cronograma_vacacion').val()	
	},

	function (data) {
		if (data.estado === 'FALLO') {
			construirPaginacion($("#paginacion"), JSON.parse(data.contenido));
			mostrarMensaje(data.mensaje, "FALLO");
		} else {
			construirPaginacion($("#paginacion"), JSON.parse(data.contenido));
		}
	}, 'json');
}

</script>