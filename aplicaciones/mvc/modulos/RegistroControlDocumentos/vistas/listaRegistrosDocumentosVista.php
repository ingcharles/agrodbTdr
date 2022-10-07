<header><nav><?php 
	echo $this->panelBusqueda;
	?></nav><br/>
	<nav><?php
	echo $this->crearAccionBotones();
	?></nav></header>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead><tr>
	<th>#</th>
		<th>No. GLPI</th>
		<th>No. Memorando</th>
		<th>Fecha aprobación</th>
		<th>Coord/Direc solicitante</th>
		<th>Formato</th>
		</tr></thead>
	<tbody></tbody>
</table>

<script>
	$(document).ready(function () {
		construirPaginacion($("#paginacion"),<?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
		$("#listadoItems").removeClass("comunes"); 
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
		});
	 $("#fecha_notificacion_desde").datepicker({
	    	yearRange: "c:c",
	    	changeMonth: false,
	        changeYear: false,
	        dateFormat: 'yy-mm-dd',
	        onSelect: function(dateText, inst) {
	      		 $('#fecha_notificacion_hasta').datepicker('option', 'minDate', $("#fecha_notificacion_desde" ).val()); 
	      		var fecha=new Date($('#fecha_notificacion_desde').datepicker('getDate'));
	      		
	    	  	fecha.setDate(fecha.getDate()+90);

	    		fecha.setMonth(fecha.getMonth());
	    		fecha.setUTCFullYear(fecha.getUTCFullYear());  
	    		$('#fecha_notificacion_hasta').datepicker('option', 'maxDate', fecha);
	          } 
	        
	      });
	 $("#fecha_notificacion_hasta").datepicker({
	    	yearRange: "c:c",
	    	changeMonth: false,
	        changeYear: false,
	        dateFormat: 'yy-mm-dd',
	        
	      });
	 $("#fecha_aprobacion_desde").datepicker({
	    	yearRange: "c:c",
	    	changeMonth: false,
	        changeYear: false,
	        dateFormat: 'yy-mm-dd',
	        onSelect: function(dateText, inst) {
	      		 $('#fecha_aprobacion_hasta').datepicker('option', 'minDate', $("#fecha_aprobacion_desde" ).val()); 
	      		var fecha=new Date($('#fecha_aprobacion_desde').datepicker('getDate'));
	      		
	    	  	fecha.setDate(fecha.getDate()+90);

	    		fecha.setMonth(fecha.getMonth());
	    		fecha.setUTCFullYear(fecha.getUTCFullYear());  
	    		$('#fecha_aprobacion_hasta').datepicker('option', 'maxDate', fecha);
	          } 
	        
	      });
	 $("#fecha_aprobacion_hasta").datepicker({
	    	yearRange: "c:c",
	    	changeMonth: false,
	        changeYear: false,
	        dateFormat: 'yy-mm-dd',
	        
	      });

	 // Función para filtrar
	 
	 $("#btnFiltrar").click(function (event) {
			$(".alertaCombo").removeClass("alertaCombo");
			$('#estado').html('');
			 var error = false;	
			event.preventDefault();
			if(!$.trim($("#fecha_aprobacion_desde").val())){
				   $("#fecha_aprobacion_desde").addClass("alertaCombo");
				   error = true;
			  }
			if(!$.trim($("#fecha_aprobacion_hasta").val())){
				   $("#fecha_aprobacion_hasta").addClass("alertaCombo");
				   error = true;
			  }

			if (!error) {
				fn_filtrar();
			}else{
				mostrarMensaje("Por favor revise los campos obligatorios...!!", "FALLO");
			}
		});

function fn_filtrar() {
	mostrarMensaje("", "FALLO");
	$('#estado').html('<span class="alerta">Verifique la información...!');
	$("#paginacion").html("<div id='cargando'>Cargando...</div>");
	  $.post("<?php echo URL ?>RegistroControlDocumentos/RegistroSgc/filtrarInformacion",
		    	{
		  numero_memorando_busq: $("#numero_memorando_busq").val(),
		  numero_glpi_busq: $("#numero_glpi_busq").val(),
		  fecha_aprobacion_desde: $("#fecha_aprobacion_desde").val(),
		  fecha_aprobacion_hasta: $("#fecha_aprobacion_hasta").val(),
		  fecha_notificacion_desde: $("#fecha_notificacion_desde").val(),
		  fecha_notificacion_hasta: $("#fecha_notificacion_hasta").val(),
		  coordinacion_busq: $("#coordinacion_busq").val(),
		  formato_busq: $("#formato_busq").val(),
		  estado_registro_busq: $("#estado_registro_busq").val(),
      },
    	function (data) {

	      	if(data.estado== 'EXITO'){
          	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
          	construirPaginacion($("#paginacion"), JSON.parse(data.contenido));
          	mostrarMensaje('', "EXITO");
	      	}else{
	      		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
          	construirPaginacion($("#paginacion"), JSON.parse(data.contenido));
	      		mostrarMensaje(data.mensaje, "FALLO");
	      	}
      }, 'json');
};
</script>
