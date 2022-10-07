<header>
<nav><?php echo $this->panelBusquedaCertificadosFitosanitarios;?></nav>
</header>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead><tr>
		<th>#</th>
		<th>CFE</th>
		<th>País</th>
		<th>Fecha de Emisión</th>
		<th>Estado</th>
		</tr></thead>
	<tbody></tbody>
</table>

<script>
	$(document).ready(function () {
		construirPaginacion($("#paginacion"),<?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
		$("#listadoItems").removeClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
	});	

	$("#btnFiltrar").click(function (event) {
		event.preventDefault();
		fn_filtrar();
	});
	
    function fn_filtrar() {
		event.preventDefault();
		fn_limpiar();

		var error = false;
		
		if($("#bEstado").val() == ""){
			$("#bEstado").addClass("alertaCombo");
			error = true;
		}
		        
		if (!error) {
			$("#paginacion").html("<div id='cargando'>Cargando...</div>");
			  $.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/listarCertificadosEmisionEphyto",
		    	{
				  	estadoCertificado: $("#bEstado").val(),
				  	fechaInicio: $("#bFechaInicio").val(),
				    fechaFin: $("#bFechaFin").val()
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

	$("#bFechaInicio").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd',
	    onSelect: function(dateText, inst) {
        	var fecha=new Date($('#bFechaInicio').datepicker('getDate')); 
        	fecha.setDate(fecha.getDate()+30);	 
      		$('#bFechaFin').datepicker('option', 'minDate', $("#bFechaInicio" ).val());
      		$('#bFechaFin').datepicker('option', 'maxDate', fecha);
      		$('#bFechaFin').datepicker('setDate', fecha);
	    }
	 }).datepicker("setDate", new Date());

	$("#bFechaFin").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd',
	    onSelect: function(dateText, inst) {
        	var fecha=new Date($('#bFechaInicio').datepicker('getDate')); 
	    }
	 }).datepicker("setDate", new Date());	
	
</script>
