<header><nav><?php 
	echo $this->panelBusqueda;
	?></nav><br/>
	<nav><?php
	
	?></nav></header>
<div id="paginacion" class="normal"></div>
<form id='formDescarga' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>RegistroControlDocumentos' data-opcion='registroSgc/descargaReporteRegistroSgc' data-destino="rutaDescar"  method="post">
<input type="hidden" name="id" id="id">
<div id="rutaDescar"></div>
</form>
<script>
	$(document).ready(function () {
	
	});

	 $("#coordinacion_busq").change(function(event){
			event.preventDefault();
			$(".alertaCombo").removeClass("alertaCombo");
			if( $("#coordinacion_busq").val() != ""){
				$("#estado").html("").removeClass('alerta');
		    	$.post("<?php echo URL ?>RegistroControlDocumentos/RegistroSgc/comboCoordinacion", 
		                {
		    		        coordinacion:$("#coordinacion_busq").val()
		    	  		         		  		     
		                }, function (data) {
		                	if (data.estado === 'EXITO') {
		                		    $("#subproceso_busq").html(data.contenido);
		    	                    mostrarMensaje(data.mensaje, data.estado);
		                        } else {
		                        	$("#subproceso_busq").html(data.contenido);
		                        	mostrarMensaje(data.mensaje, "FALLO");
		                        }
		            }, 'json');
			}else{
				$("#coordinacion_busq").val('');
			}
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
	
	$("#paginacion").html("<div id='cargando'>Cargando...</div>");
	  $.post("<?php echo URL ?>RegistroControlDocumentos/RegistroSgc/generarReporteDocumentosSgc",
		    	{
		  subproceso_busq: $("#subproceso_busq").val(),
		  fecha_aprobacion_desde: $("#fecha_aprobacion_desde").val(),
		  fecha_aprobacion_hasta: $("#fecha_aprobacion_hasta").val(),
		  coordinacion_busq: $("#coordinacion_busq").val(),
		  formato_busq: $("#formato_busq").val(),
		  estado_registro_busq: $("#estado_registro_busq").val(),
		  socializar_busq: $("#socializar_busq").val()
     },
   	function (data) {
    	 $("#paginacion").html("");
	      	if(data.estado== 'EXITO'){
         	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
         	$("#id").val(data.rutaArch);
			abrir($("#formDescarga"),event,false);
         	mostrarMensaje('', "EXITO");
	      	}else{
	      		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');
         	
	      		mostrarMensaje(data.mensaje, "FALLO");
	      	}
     }, 'json');
};
</script>
