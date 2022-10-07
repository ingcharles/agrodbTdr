<header>
<script src="<?php echo URL ?>modulos/InspeccionAntePostMortemCF/vistas/js/funcionCf.js"></script>
	<nav><?php
	echo $this->panelBusqueda;
	?></nav><br/>
</header>

<script>
	var opcion = <?php echo json_encode($this->opcion);?>;
	$(document).ready(function () {
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">.</div>');
		$("#rucFaenamiento").numeric();
		$("#fechaFin").attr('disabled','disabled');
	 });

	//******fecha del formulario reportes fin
	$("#fechaFin").datepicker({
		yearRange: "c:c",
		changeMonth: false,
	    changeYear: false,
	    dateFormat: 'yy-mm-dd',
	    onSelect: function(dateText, inst) {
	    	//$('#fechaInicio').datepicker('option', 'minDate', $("#fechaFin" ).val()); 
	    	var fecha=new Date($('#fechaFin').datepicker('getDate'));
	    	fecha.setDate(fecha.getDate()-30);
	    	fecha.setMonth(fecha.getMonth());
			fecha.setUTCFullYear(fecha.getUTCFullYear());  
			$('#fechaInicio').datepicker('option', 'minDate', fecha);
	
	        }
	  });
		
		$("#fechaInicio").datepicker({ 
			    changeMonth: true,
			    changeYear: true,
			    dateFormat: 'yy-mm-dd',
			    onSelect: function(dateText, inst) {
			    	$("#fechaFin").removeAttr('disabled','disabled');
			    	$('#fechaFin').datepicker('option', 'minDate', $("#fechaInicio" ).val()); 
			    	var fecha=new Date($('#fechaInicio').datepicker('getDate'));
			    	fecha.setDate(fecha.getDate()+30);
			    	fecha.setMonth(fecha.getMonth());
					fecha.setUTCFullYear(fecha.getUTCFullYear());  
					$('#fechaFin').datepicker('option', 'maxDate', fecha);	
			        	
			    }
	  });
	
		//Cuando se presiona en Filtrar lista, debe cargar los datos
	    $("#btnFiltrar").click(function () {
	    	 $(".alertaCombo").removeClass("alertaCombo");
	       	var error = false;
	          if (!$.trim($("#fechaInicio").val())) {
	  			   $("#fechaInicio").addClass("alertaCombo");
	  			   error = true;
	          }
	          if(!$.trim($("#fechaFin").val())){
		  			$("#fechaFin").addClass("alertaCombo");
		  			error =  true;
		  		  }
	          
	          if(opcion){
	        	  if(!$.trim($("#id_provincia").val())){
			  			$("#id_provincia").addClass("alertaCombo");
			  			error =  true;
			  		  }
	  		   }
	        if(!error){
	        	mostrarMensaje("", "EXITO");
				fn_filtrar();
	         }else{
	   			mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
	   		}
		});
	// Función para filtrar
	function fn_filtrar() {
		if(opcion){
		    var prov = $("#id_provincia option:selected").text();
		}else{
			var prov = 'Seleccionar...';
			}
		$("#paginacion").html("<div id='cargando'>Cargando...</div>");
	    $.post("<?php echo URL ?>InspeccionAntePostMortemCF/FormularioReporteAntePostMortem/generarReporteMensual",
	    	{
	        	provincia: prov,
	        	rucFaenamiento: $("#rucFaenamiento").val(),
	        	fechaInicio: $("#fechaInicio").val(),
	        	fechaFin: $("#fechaFin").val()
	        },
	      	function (data) {
	            construirPaginacion($("#paginacion"), JSON.parse(data));
	        });
	    
	    }
</script>
