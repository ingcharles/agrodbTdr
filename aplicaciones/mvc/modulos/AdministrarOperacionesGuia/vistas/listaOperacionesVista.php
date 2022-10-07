<header>
	<nav><?php
echo $this->panelBusqueda;
?></nav>
	<br />
	<nav><?php
echo $this->crearAccionBotones();
?></nav>
</header>
<script
	src="<?php echo URL ?>modulos/AdministrarOperacionesGuia/vistas/js/operaciones.js"></script>

<div class="elementos"></div>

<script>
	var perfil=<?php echo json_encode($this->perfilUsuario);?>;
	var area =<?php echo json_encode($this->area);?>;
	
	$(document).ready(function () {
		$("#listadoItems").addClass("comunes"); 
	    $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una operación para revisarla.</div>');
	});

    $("#btnFiltrar").click(function () {
    	 
        if(($("#identificadorFiltro").val() !== ''  &&  $("#identificadorFiltro").val().length <= 13) ||  $("#razonSocial").val() !== '' ){
            if($.inArray("PFL_TEC_PC", perfil) >= 0 ){
                if($("#provincia").val() !== ''){
                	fn_limpiar();
                	fn_filtrar();
                }else{
                	$("#provincia").addClass("alertaCombo");
                	fn_mensajes(3);
                } 
            }else{
                fn_limpiar();
            	fn_filtrar();
            }
        }else{
        	$("#identificadorFiltro").addClass("alertaCombo");
        	$("#razonSocial").addClass("alertaCombo");
        	fn_mensajes(2);
        }

        if($("#tipoOperacion").val() == ""){
        	$("#tipoOperacion").addClass("alertaCombo");
        	fn_mensajes(5);
        }
         
    });
	// Función para filtrar
		function fn_filtrar() { 
			$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una operación para revisarla.</div>');
			var provincia='';
			if($.inArray("PFL_TEC_PC", perfil) >= 0 ){
				provincia = $("#provincia option:selected").text();
			}
			$("#paginacion").html("<div id='cargando'>Cargando...</div>");
		    $.post("<?php echo URL ?>AdministrarOperacionesGuia/Operaciones/filtrarOperacionesOperador",
		    	{
		    		identificadorOperador: $("#identificadorFiltro").val(),
		    		razonSocial: $("#razonSocial").val(),
		    		provincia: provincia,
		    		area:area,
		    		tipoOperacion:$("#tipoOperacion").val(),
		        },
		      	function (data) {
		        	if(data.estado == 'EXITO'){
			        	$(".elementos").html(data.contenido);
	                	mostrarMensaje('', "EXITO");
			      	}else{
			      		mostrarMensaje(data.mensaje, "FALLO");
			      		$(".elementos").html(data.contenido);
			      	}
		        }, 'json');
		    }

		$("#identificadorFiltro").change(function (event){
			var provincia='';
			if($.inArray("PFL_TEC_PC", perfil) >= 0 ){
				if($("#identificadorFiltro").val() != '' && $("#provincia").val() != ''){
					 provincia = $("#provincia option:selected").text();
		    		  $.post("<?php echo URL ?>AdministrarOperacionesGuia/Operaciones/cargarTipoOperacion", 
		    	              {
	        	    			  identificador:$("#identificadorFiltro").val(),
	         			          provincia:provincia,
	         			          area:area,
		    	              }, function (data) {
		    	              	if (data.estado === 'EXITO') {
		    	              		    $("#tipoOperacion").html(data.contenido);
		    		                    mostrarMensaje(data.mensaje, data.estado);
		    	                  } else {
		    	                  	mostrarMensaje(data.mensaje, "FALLO");
		    	                  }
		    	      }, 'json');
				  }
			}else{
				 if($("#identificadorFiltro").val() != ''){
		    		  $.post("<?php echo URL ?>AdministrarOperacionesGuia/Operaciones/cargarTipoOperacion", 
		    	              {
	        	    			  identificador:$("#identificadorFiltro").val(),
	         			          provincia:provincia,
	         			          area:area,
		    	              }, function (data) {
		    	              	if (data.estado === 'EXITO') {
		    	              		    $("#tipoOperacion").html(data.contenido);
		    		                    mostrarMensaje(data.mensaje, data.estado);
		    	                  } else {
		    	                  	mostrarMensaje(data.mensaje, "FALLO");
		    	                  }
		    	      }, 'json');
				  }

				}

		});
			$("#razonSocial").change(function (event){
				var provincia='';
				if($.inArray("PFL_TEC_PC", perfil) >= 0 ){
					if($("#razonSocial").val() != '' && $("#provincia").val() != ''){
						 provincia = $("#provincia option:selected").text();
			    		  $.post("<?php echo URL ?>AdministrarOperacionesGuia/Operaciones/cargarTipoOperacion", 
			    	              {
			    			 		  razonSocial:$("#razonSocial").val(),
		         			          provincia:provincia,
		         			          area:area,
			    	              }, function (data) {
			    	              	if (data.estado === 'EXITO') {
			    	              		    $("#tipoOperacion").html(data.contenido);
			    		                    mostrarMensaje(data.mensaje, data.estado);
			    	                  } else {
			    	                  	mostrarMensaje(data.mensaje, "FALLO");
			    	                  }
			    	      }, 'json');
					  }
				}else{
					 if($("#razonSocial").val() != ''){
			    		  $.post("<?php echo URL ?>AdministrarOperacionesGuia/Operaciones/cargarTipoOperacion", 
			    	              {
			    			          razonSocial:$("#razonSocial").val(),
		         			          provincia:provincia,
		         			          area:area,
			    	              }, function (data) {
			    	              	if (data.estado === 'EXITO') {
			    	              		    $("#tipoOperacion").html(data.contenido);
			    		                    mostrarMensaje(data.mensaje, data.estado);
			    	                  } else {
			    	                  	mostrarMensaje(data.mensaje, "FALLO");
			    	                  }
			    	      }, 'json');
					  }

					}
			
	    	
	    });
		$("#provincia").change(function (event){
	    	 if($("#identificadorFiltro").val() != '' && $("#provincia").val() != ''){
	    		  $.post("<?php echo URL ?>AdministrarOperacionesGuia/Operaciones/cargarTipoOperacion", 
	    	              {
	    			         identificador:$("#identificadorFiltro").val(),
	    			         provincia:$("#provincia option:selected").text(),
	    			         area:area,
	    	              }, function (data) {
	    	              	if (data.estado === 'EXITO') {
	    	              		    $("#tipoOperacion").html(data.contenido);
	    		                    mostrarMensaje(data.mensaje, data.estado);
	    	                  } else {
	    	                  	mostrarMensaje(data.mensaje, "FALLO");
	    	                  }
	    	      }, 'json');
			  }else if($("#razonSocial").val() != '' && $("#provincia").val() != ''){
				  
				  $.post("<?php echo URL ?>AdministrarOperacionesGuia/Operaciones/cargarTipoOperacion", 
	    	              {
					         razonSocial:$("#razonSocial").val(),
	    			         provincia:$("#provincia option:selected").text(),
	    			         area:area,
	    	              }, function (data) {
	    	              	if (data.estado === 'EXITO') {
	    	              		    $("#tipoOperacion").html(data.contenido);
	    		                    mostrarMensaje(data.mensaje, data.estado);
	    	                  } else {
	    	                  	mostrarMensaje(data.mensaje, "FALLO");
	    	                  }
	    	      }, 'json');
				  
			  }
	    });
		    
</script>
