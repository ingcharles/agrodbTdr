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
		<th>N° certificado</th>
		<th>Sitio origen</th>
		<th>Sitio destino</th>
		<th>F. emisión</th>
		<th>Estado</th>
		</tr></thead>
	<tbody></tbody>
</table>

<script>
	$(document).ready(function () {
		construirPaginacion($("#paginacion"),<?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
		 $("#listadoItems").removeClass("comunes");
		 $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una operación para revisarla.</div>');
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
		 $("#fechaInicio").click(function () {
	    	 $(this).val('');
	     });
		 $("#fechaFin").click(function () {
	    	 $(this).val('');
	     });

		 function fn_filtrar() {
				$("#paginacion").html("<div id='cargando'>Cargando...</div>");
				  $.post("<?php echo URL ?>EmisionCertificacionOrigen/emisionCertificado/filtrarInformacion",
					    	{
					  fechaInicio: $("#fechaInicio").val(),
					  fechaFin: $("#fechaFin").val(),
					  estadoEmision:$("#estadoEmision").val(),
					  numCertificado:$("#numCertificado").val(),
					  nombreSitio:$("#nombreSitio").val()
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
