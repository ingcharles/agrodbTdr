<header>
	<nav><?php echo $this->panelBusquedaMiembros;?></nav>
	<nav><?php echo $this->crearAccionBotones();?></nav>
</header>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Identificador</th>
			<th>Nombre asociado</th>
			<th>Sitio</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody></tbody>
</table>

<script>
	$(document).ready(function () {
		construirPaginacion($("#paginacion"),<?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
    	$("#listadoItems").removeClass("comunes");
    	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un ítem para revisarlo.</div>');
	});

	$("#btnFiltrar").click(function (event) {
		event.preventDefault();
		fn_filtrar();
	});

	//Función para realizar la búsqueda de miembros con los parámetros de búsqueda especificados
	function fn_filtrar() {
		event.preventDefault();
		fn_limpiar();

		var error = false;

		if(!$.trim($("#identificadorMiembroFiltro").val())){
			$("#identificadorMiembroFiltro").addClass("alertaCombo");
			error = true;
		}
        
		if (!error) {
			$("#paginacion").html("<div id='cargando'>Cargando...</div>");
			  $.post("<?php echo URL ?>PasaporteEquino/Miembros/listarMiembrosFiltrados",
		    	{
				  identificadorMiembroFiltro: $("#identificadorMiembroFiltro").val(),
				  nombreMiembroFiltro: $("#nombreMiembroFiltro").val(),
				  nombreSitioMiembroFiltro: $("#nombreSitioMiembroFiltro").val()
		        },
		      	function (data) {
		        	if (data.estado === 'FALLO') {
	                mostrarMensaje(data.mensaje, "FALLO");
	                } else {
	                	construirPaginacion($("#paginacion"), JSON.parse(data.contenido));
	                }
		        }, 'json');
		} else {
			$("#estado").html();
			mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
		}		
	}

	function fn_limpiar() {
		$(".alertaCombo").removeClass("alertaCombo");
		$('#estado').html('');
	}
</script>