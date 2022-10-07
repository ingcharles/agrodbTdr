<header>
	<nav><?php echo $this->panelBusquedaTramites;?></nav>
	<nav><?php echo $this->crearAccionBotones();?></nav>
</header>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Número de Trámite</th>
			<th>Asunto</th>
			<th>Fecha Creación</th>
			<th>Remitente</th>
			<th>Destinatario</th>
		</tr>
	</thead>
	<tbody></tbody>
</table>

<script>
	$(document).ready(function () {
		construirValidador();
		construirPaginacion($("#paginacion"),<?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
		$("#listadoItems").removeClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
		$("#btnExcel").hide();
	});

	$("#btnFiltrar").click(function (event) {
		event.preventDefault();
		fn_filtrar();
	});

	function fn_filtrar() {
		event.preventDefault();
		fn_limpiar();

		var error = false;
		
		if(!$.trim($("#fechaInicio").val())){
			error = true;
			$("#fechaInicio").addClass("alertaCombo");
		}
		
        if (!$.trim($("#fechaFin").val())) {
        	error = true;
			$("#fechaFin").addClass("alertaCombo");
        }
        
		if (!error) {
			$("#paginacion").html("<div id='cargando'>Cargando...</div>");
			  $.post("<?php echo URL ?>SeguimientoDocumental/Tramites/listarTramitesFiltrados",
		    	{
				  	idVentanillaFiltro: $("#idVentanillaFiltro").val(),
				  	numTramite: $("#numTramite").val(),
				  	nombreRemitente: $("#nombreRemitente").val(),
				    nombreDestinatario: $("#nombreDestinatario").val(),
				    numQuipux: $("#numQuipux").val(),
				    numFactura: $("#numFactura").val(),
				    idUnidadDestinoFiltro: $("#idUnidadDestinoFiltro").val(),
				    fechaInicio: $("#fechaInicio").val(),
				    fechaFin: $("#fechaFin").val(),
				    estadoTramite: $("#estadoTramite").val()
		        },
		      	function (data) {
		        	if (data.estado === 'FALLO') {
	                mostrarMensaje(data.mensaje, "FALLO");
	                } else {
	                	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
	                	construirPaginacion($("#paginacion"), JSON.parse(data.contenido));
	                	$("#btnExcel").show();
	                }
		        }, 'json');
		} else {
			$("#estado").html();
			mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
		}		
	}

	function fn_filtrar_default() {
		event.preventDefault();
		fn_limpiar();

		var error = false;
		var f = new Date();

		if (!error) {
			$("#paginacion").html("<div id='cargando'>Cargando...</div>");
			  $.post("<?php echo URL ?>SeguimientoDocumental/Tramites/listarTramitesFiltrados",
		    	{
				  	idVentanillaFiltro: $("#idVentanillaFiltro").val(),
				  	numTramite: $("#numTramite").val(),
				  	nombreRemitente: $("#nombreRemitente").val(),
				    nombreDestinatario: $("#nombreDestinatario").val(),
				    numQuipux: $("#numQuipux").val(),
				    numFactura: $("#numFactura").val(),
				    idUnidadDestinoFiltro: $("#idUnidadDestinoFiltro").val(),
				    fechaInicio: $("#fechaInicio").val(),
				    fechaFin: $("#fechaFin").val(),
				    estadoTramite: $("#estadoTramite").val()
		        },
		      	function (data) {
		        	if (data.estado === 'FALLO') {
	                mostrarMensaje(data.mensaje, "FALLO");
	                } else {
	                	construirPaginacion($("#paginacion"), JSON.parse(data.contenido));
	                	$("#btnExcel").show();
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

	$("#fechaInicio").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd',
	    onSelect: function(dateText, inst) {
        	var fecha=new Date($('#fechaInicio').datepicker('getDate')); 
        	fecha.setDate(fecha.getDate()+180);	 
      		$('#fechaFin').datepicker('option', 'minDate', $("#fechaInicio" ).val());
      		$('#fechaFin').datepicker('option', 'maxDate', fecha);
	    }
	 });

	$("#fechaFin").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd',
	    onSelect: function(dateText, inst) {
        	var fecha=new Date($('#fechaInicio').datepicker('getDate')); 
	    }
	 });
</script>