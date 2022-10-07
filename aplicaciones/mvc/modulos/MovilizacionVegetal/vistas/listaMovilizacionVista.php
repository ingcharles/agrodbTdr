<header>
	<nav><?php echo $this->panelBusquedaMovilizaciones;?></nav>
	<nav><?php echo $this->crearAccionBotones();?></nav>
</header>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Nº Permiso</th>
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
		
		/*if(!$.trim($("#identificadorOperador").val())){
			$("#identificadorOperador").addClass("alertaCombo");

			if (!$.trim($("#nombreOperador").val())) {
	        	$("#nombreOperador").addClass("alertaCombo");

				if(!$.trim($("#nombreSitio").val())){
					$("#nombreSitio").addClass("alertaCombo");

					if (!$.trim($("#numPermiso").val())) {
			        	error = true;
						$("#numPermiso").addClass("alertaCombo");
			        }
				}
	        }
		}*/
		        
		if (!error) {
			$("#paginacion").html("<div id='cargando'>Cargando...</div>");
			  $.post("<?php echo URL ?>MovilizacionVegetal/Movilizacion/listarMovilizacionesFiltradas",
		    	{
				  	identificadorUsuario: $("#identificadorUsuario").val(),
				  	provinciaTecnico: $("#provinciaTecnico").val(),
				  	identificadorOperador: $("#identificadorOperador").val(),
				  	nombreOperador: $("#nombreOperador").val(),
				  	nombreSitio: $("#nombreSitio").val(),
				  	numPermiso: $("#numPermiso").val(),
				    estadoMovilizacion: $("#estadoMovilizacion").val(),
				    fechaInicio: $("#fechaInicio").val(),
				    fechaFin: $("#fechaFin").val()
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

	$("#fechaInicio").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd',
	    onSelect: function(dateText, inst) {
        	var fecha=new Date($('#fechaInicio').datepicker('getDate')); 
        	fecha.setDate(fecha.getDate()+30);	 
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