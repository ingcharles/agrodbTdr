<header>
<nav><?php echo $this->panelBusquedaCertificadosFitosanitarios;?></nav>
<nav><?php echo $this->crearAccionBotones();?></nav>
</header>
<div id="paginacion" class="normal"></div>
<table id="tablaItems">
	<thead><tr>
		<th>#</th>
		<th>CFE</th>
		<th>País</th>
		<th>Fecha de Solicitud</th>
		<th>Estado</th>
		</tr></thead>
	<tbody></tbody>
</table>

<script>
	$(document).ready(function () {
		construirPaginacion($("#paginacion"),<?php print_r(json_encode($this->itemsFiltrados, JSON_UNESCAPED_UNICODE)); ?>);
		$("#listadoItems").removeClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un item para revisarlo.</div>');

		$("#_reimpresion").click(function(){
			if($("#cantidadItemsSeleccionados").text() == 0){
				$("#detalleItem").html('<div class="mensajeInicial">Seleccione una solicitud en estado "Aprobado" y presione el botón Anula y Reemplaza.</div>');	
				return false;
			}else if($("#cantidadItemsSeleccionados").text() > 1){
				$("#detalleItem").html('<div class="mensajeInicial">Seleccione una solicitud a la vez.</div>');	
				return false;
			}
		});	

		$("#_eliminar").click(function(){
			if($("#cantidadItemsSeleccionados").text() == 0){
				$("#detalleItem").html('<div class="mensajeInicial">Seleccione una solicitud y presione el botón Desestimiento.</div>');	
				return false;
			}else if($("#cantidadItemsSeleccionados").text() > 1){
				$("#detalleItem").html('<div class="mensajeInicial">Seleccione una solicitud a la vez.</div>');	
				return false;
			}
		});	
	});
	
	$("#bTipoSolicitud").change(function () {
		fn_obtenerTiposProductosSVPorTipoSolicitud($("#bTipoSolicitud").val());
	});

	$("#bTipoProducto").change(function () {
		fn_obtenerSubtiposProductoPorIdTipoProducto($("#bTipoProducto").val());
	});

	$("#bSubtipoProducto").change(function () {
		fn_obtenerProductoPorIdSubipoProducto($("#bSubtipoProducto").val());
	});

	$("#btnFiltrar").click(function (event) {
		event.preventDefault();
		fn_filtrar();
	});
	
    function fn_obtenerTiposProductosSVPorTipoSolicitud(tipoSolicitud) {

    	$("#estado").html("").removeClass('alerta');
        
    	if ($("#bTipoSolicitud").val() !== ""){    
    		 $.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/buscarTiposProductosSVPorTipoSolicitud",
    	                {
							tipoSolicitud : tipoSolicitud    			 		
    	                }, function (data) {
    	                	fn_activarInactivarCampos("activar");
    	                    $("#bTipoProducto").html(data);               
    	                });
    	}else{

    		fn_activarInactivarCampos("inactivar");
    		
        }    	  

    }

    function fn_obtenerSubtiposProductoPorIdTipoProducto(idTipoProducto) {

    	$("#estado").html("").removeClass('alerta');
        
    	if ($("#bTipoProducto").val() !== ""){    
    		 $.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/buscarSubtiposProductoPorIdTipoProducto",
    	                { 
    			 			idTipoProducto : idTipoProducto   			 		
    	                }, function (data) {
    	                    //$("#id_puerto_pais_destino").removeAttr("disabled");
    	                    $("#bSubtipoProducto").html(data);               
    	                });
    	}  

    }

    function fn_obtenerProductoPorIdSubipoProducto(idSubtipoProducto) {

    	$("#estado").html("").removeClass('alerta');
        
    	if ($("#bSubtipoProducto").val() !== ""){    
    		 $.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/buscarProductoPorIdSubipoProducto",
    	                { 
    			 			idSubtipoProducto : idSubtipoProducto   			 		
    	                }, function (data) {
    	                    //$("#id_puerto_pais_destino").removeAttr("disabled");
    	                    $("#bProducto").html(data);               
    	                });
    	}  

    }

    function fn_filtrar() {
		event.preventDefault();
		fn_limpiar();

		var error = false;
		        
		if (!error) {
			$("#paginacion").html("<div id='cargando'>Cargando...</div>");
			  $.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/listarCertificadosFitosanitariosFiltrados",
		    	{
				  	identificadorOperador: $("#identificadorUsuario").val(),
				  	tipoSolicitud: $("#bTipoSolicitud").val(),
				  	paisDestino: $("#bPaisDestino").val(),
				  	idTipoProducto: $("#bTipoProducto").val(),
				  	idSubtipoProducto: $("#bSubtipoProducto").val(),
				  	idProducto: $("#bProducto").val(),
				  	fechaInicio: $("#bFechaInicio").val(),
				    fechaFin: $("#bFechaFin").val(),
				    estadoCertificado: $("#bEstado").val(),
				    numeroCertificado: $("#bNumeroCertificado").val()
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

	function fn_activarInactivarCampos(valor){

		switch (valor){

		case "activar":
			$("#bTipoProducto").removeAttr("disabled");
			$("#bSubtipoProducto").removeAttr("disabled");
			$("#bProducto").removeAttr("disabled");
		break;

		case "inactivar":
    		$("#bTipoProducto").prop("disabled", true);
    		$("#bSubtipoProducto").prop("disabled", true);
    		$("#bProducto").prop("disabled", true);
		break;

		}
		
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
