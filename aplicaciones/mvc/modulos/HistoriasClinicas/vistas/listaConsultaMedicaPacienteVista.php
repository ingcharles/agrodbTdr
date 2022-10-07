<header>
<nav><?php if($this->perfilUsuario == 'PFL_MEDICO'){
	echo $this->panelBusqueda;
	?></nav><br/>
	<nav><?php
	echo $this->crearAccionBotones();}
	?></nav>
</header>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead><tr>
	<th>#</th>
		<th>Fecha creación</th>
		<th>Síntomas</th>
		</tr></thead>
	<tbody></tbody>
</table>

<script>
	$(document).ready(function () {
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un registro para editar.</div>');
		construirPaginacion($("#paginacion"),<?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
		$("#listadoItems").removeClass("comunes"); $("#identificadorFiltro").attr('maxlength', 10);});
		$("#_eliminar").click(function () {
		if ($("#cantidadItemsSeleccionados").text() > 1) {
			alert('Por favor seleccione un registro a la vez');
			return false;
		}
	});
		 

</script>
