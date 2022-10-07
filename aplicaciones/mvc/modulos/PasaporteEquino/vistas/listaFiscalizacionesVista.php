<header>
	<nav><?php echo $this->panelBusquedaFiscalizaciones;?></nav>
	<nav><?php echo $this->crearAccionBotones();?></nav>
</header>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>N° Movilización</th>
			<th>Sitio Origen</th>
			<th>Sitio Destino</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody></tbody>
</table>

<script>
$(document).ready(function () {
	construirValidador();
	
	construirPaginacion($("#paginacion"),<?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
	$("#listadoItems").removeClass("comunes");
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un ítem para revisarlo.</div>');
});

$("#fechaInicioFiltro").datepicker({ 
    changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd',
    onSelect: function(dateText, inst) {
    	var fecha=new Date($('#fechaInicioFiltro').datepicker('getDate')); 
    	fecha.setDate(fecha.getDate()+4);	//Cuenta desde el día actual, 5 en total 
  		$('#fechaFinFiltro').datepicker('option', 'minDate', $("#fechaInicioFiltro" ).val());
  		$('#fechaFinFiltro').datepicker('option', 'maxDate', fecha);
    }
 });

$("#fechaFinFiltro").datepicker({ 
    changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd',
    onSelect: function(dateText, inst) {
    	var fecha=new Date($('#fechaInicioFiltro').datepicker('getDate')); 
    }
 });

$("#btnFiltrar").click(function (event) {
	event.preventDefault();
	fn_filtrar();
});

	//Función para realizar la búsqueda de equinos con los parámetros de búsqueda especificados
function fn_filtrar() {
	event.preventDefault();
	fn_limpiar();

	var error = false;

	if(!$.trim($("#identificadorSolicitanteFiltro").val())){
		if(!$.trim($("#nombreSolicitanteFiltro").val())){
			if(!$.trim($("#nombreSitioOrigenFiltro").val())){
				if(!$.trim($("#numMovilizacionFiltro").val())){
					if(!$.trim($("#numPasaporteFiltro").val())){
							$("#identificadorSolicitanteFiltro").addClass("alertaCombo");
							$("#nombreSolicitanteFiltro").addClass("alertaCombo");
							$("#nombreSitioOrigenFiltro").addClass("alertaCombo");
							$("#numMovilizacionFiltro").addClass("alertaCombo");
							$("#numPasaporteFiltro").addClass("alertaCombo");
							error = true;
					}
				}
			}
		}			
	}
	
	
	if(!$.trim($("#fechaInicioFiltro").val())){
		$("#fechaInicioFiltro").addClass("alertaCombo");
		error = true;
	}
	
	if(!$.trim($("#fechaFinFiltro").val())){
		$("#fechaFinFiltro").addClass("alertaCombo");
		error = true;
	}		
    
	if (!error) {
		$("#paginacion").html("<div id='cargando'>Cargando...</div>");
		  $.post("<?php echo URL ?>PasaporteEquino/Fiscalizaciones/listarMovilizacionesFiltradas",
	    	{
			  tipoProceso: 'Fiscalizacion',
			  identificadorSolicitanteFiltro: $("#identificadorSolicitanteFiltro").val(),
			  nombreSolicitanteFiltro: $("#nombreSolicitanteFiltro").val(),
			  nombreSitioOrigenFiltro: $("#nombreSitioOrigenFiltro").val(),
			  numMovilizacionFiltro: $("#numMovilizacionFiltro").val(),
			  numPasaporteFiltro: $("#numPasaporteFiltro").val(),
			  fechaInicioFiltro: $("#fechaInicioFiltro").val(),
			  fechaFinFiltro: $("#fechaFinFiltro").val()

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