<header>
	<nav><?php echo $this->panelBusquedaTecnico;?></nav>
	<nav><?php echo $this->crearAccionBotones();?></nav>
</header>

<div id="paginacion" class="normal"></div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Expediente</th>
			<th>Nº Registro</th>
			<th>Producto</th>
			<th>Tipo solicitud</th>
			<th>Provincia</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody></tbody>
</table>

<script>
var combo = "<option>Seleccione....</option>";

$(document).ready(function () {
	construirPaginacion($("#paginacion"),<?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
	$("#listadoItems").removeClass("comunes");
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un ítem para revisarlo.</div>');
});

$("#btnFiltrar").click(function (event) {
	event.preventDefault();
	fn_filtrar();
});

//Función para realizar la búsqueda de solicitudes con los parámetros de búsqueda especificados
function fn_filtrar() {
	event.preventDefault();
	fn_limpiar();

	var error = false;

	if($("#numeroTramiteFiltro").val() == ''){
		if(!$.trim($("#estadoFiltro").val())){
			$("#numeroTramiteFiltro").addClass("alertaCombo");
			$("#estadoFiltro").addClass("alertaCombo");
			error = true;
		}
	}	
    
	if (!error) {
		$("#paginacion").html("<div id='cargando'>Cargando...</div>");
		  $.post("<?php echo URL ?>DossierPecuario/AdministracionSolicitudes/listarSolicitudesAdministracionFiltradas",
	    	{
			  numeroTramiteFiltro: $("#numeroTramiteFiltro").val(),
			  estadoFiltro: $("#estadoFiltro").val(),
			  idProvinciaFiltro: $("#idProvinciaFiltro").val(),
			  identificadorTecnicoFiltro: $("#identificadorTecnicoFiltro").val(),
			  filtro: 'tecnico'
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