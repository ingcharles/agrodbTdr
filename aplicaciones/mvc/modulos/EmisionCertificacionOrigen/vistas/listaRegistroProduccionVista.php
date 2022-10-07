<header><nav><?php 
	echo $this->panelBusqueda;
	?></nav><br/>
	<nav><?php
	echo $this->crearAccionBotones();
	?></nav></header>
	<script src="<?php echo URL ?>modulos/EmisionCertificacionOrigen/vistas/js/funcionEmiCertOri.js"></script>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead><tr>
	<th>#</th>
		<th>Fecha de faenamiento</th>
		<th>Especie</th>
		<th># animales recibidos</th>
		<th># canales obtenidos</th>
		<th>Subproductos</th>
		</tr></thead>
	<tbody></tbody>
</table>

<script>
	$(document).ready(function () {
		construirPaginacion($("#paginacion"),<?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
		$("#listadoItems").removeClass("comunes"); });
	    $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una operación para revisarla.</div>');
		$("#_eliminar").click(function () {
		if ($("#cantidadItemsSeleccionados").text() > 1) {
			alert('Por favor seleccione un registro a la vez');
			return false;
		}
	});

		 $("#fechaInicio").datepicker({
		    	yearRange: "c:c",
		    	changeMonth: true,
		        changeYear: false,
		        dateFormat: 'yy-mm-dd',
		        onSelect: function(dateText, inst) {

		        	var fecha=new Date($('#fechaInicio').datepicker('getDate'));
		        	
		    	  	$('#fechaFin').datepicker('option', 'minDate', $("#fechaInicio" ).val()); 
		    	  	
		    		fecha.setDate(fecha.getDate());
		    	  	fecha.setMonth(fecha.getMonth());
		    		fecha.setUTCFullYear(fecha.getUTCFullYear());  
		    		
		    		$('#fechaFin').datepicker('option', 'maxDate', +90);
		        }
		      });
		 $("#fechaFin").datepicker({
		    	yearRange: "c:c",
		    	changeMonth: true,
		        changeYear: false,
		        dateFormat: 'yy-mm-dd',
		        
		      });

		 function fn_filtrar() {
				$("#paginacion").html("<div id='cargando'>Cargando...</div>");
				  $.post("<?php echo URL ?>EmisionCertificacionOrigen/registroProduccion/filtrarInformacion",
					    	{
					  fechaInicio: $("#fechaInicio").val(),
					  fechaFin: $("#fechaFin").val()
			        },
			      	function (data) {
				      	if(data.estado == 'EXITO'){
		                	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
		                	construirPaginacion($("#paginacion"), JSON.parse(data.contenido));
		                	mostrarMensaje('', "EXITO");
				      	}else{
				      		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
		                	construirPaginacion($("#paginacion"), JSON.parse(data.contenido));
				      		mostrarMensaje(data.mensaje, "FALLO");
				      	}
			        }, 'json');
			}
</script>
