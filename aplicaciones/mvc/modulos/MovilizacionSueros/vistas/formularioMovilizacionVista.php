<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario'
	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>MovilizacionSueros'
	data-opcion='movilizacion/guardar' data-destino="detalleItem"
	data-accionEnExito="ACTUALIZAR" method="post">
	<input type="hidden" name="listDetalleProducto"
		id="listDetalleProducto" /> <input type="hidden"
		name="id_movilizacion" id="detalleProducto" /> <input type="hidden"
		name="cod_provincia" id="cod_provincia" /> <input type="hidden"
		name="id" id="id">
	<fieldset>
		<legend>Datos Origen</legend>

		<div data-linea="1">
			<label for="id_sitio_origen">Sitio Origen: </label> <select
				id="id_sitio_origen" name="id_sitio_origen">
				<option value="">Seleccionar...</option>
				<?php echo $this->sitios; ?>
			</select>
		</div>

		<div data-linea="2">
			<label for="id_area_origen">Área Origen: </label> <select
				id="id_area_origen" name="id_area_origen">
				<option value="">Seleccionar...</option>
			</select>
		</div>

	</fieldset>
	<fieldset>
		<legend>Datos Destino</legend>
		<div data-linea="1">
			<label for="identificador_operador_destino">RUC/CI: </label> <input
				type="text" id="identificador_operador_destino"
				name="identificador_operador_destino" placeholder="RUC/CI" 
				maxlength="13" />
		</div>
		<div data-linea="2">
			<label for="nombre_operador_destino">Razón Social: </label> <input
				type="text" id="nombre_operador_destino"
				name="nombre_operador_destino" placeholder="Razón Social" 
				maxlength="100" />
		</div>
		<div data-linea="3">
			<label for="id_provincia">Provincia: </label> <select
				id="id_provincia" name="id_provincia">
				<option value="">Seleccionar...</option>
				<?php echo $this->comboProvinciasEc();?>
			</select>
		</div>
		<div data-linea="3">
			<label for="id_canton">Cantón: </label> <select id="id_canton"
				name="id_canton">
				<option value="">Seleccionar...</option>
			</select>
		</div>
		<div data-linea="3">
			<label for="id_parroquia">Parroquia: </label> <select
				id="id_parroquia" name="id_parroquia">
				<option value="">Seleccionar...</option>
			</select>
		</div>
		<div data-linea="4">
			<label for="direccion_operador_destino">Dirección: </label> <input
				type="text" id="direccion_operador_destino"
				name="direccion_operador_destino" placeholder="Dirección" 
				maxlength="512" />
		</div>
		<div data-linea="5">
			<label for="id_uso_suero">Uso: </label> <select id="id_uso_suero"
				name="id_uso_suero">
				<option value="">Seleccionar...</option>
				<?php echo $this->uso;?>
			</select>
		</div>
		<div data-linea="5">
			<label for="id_detalle_uso_suero">Destino final: </label> <select id="id_detalle_uso_suero"
				name="id_detalle_uso_suero">
				<option value="">Seleccionar...</option>
				
			</select>
		</div>
	</fieldset>
	<fieldset>
		<legend>Datos de Moviliación</legend>

		<div data-linea="1" id="esperarMovi">
			<label for="transportista_identificador">RUC/CI: </label> <input
				type="text" id="transportista_identificador"
				name="transportista_identificador" placeholder="RUC/CI"
				maxlength="13" />
		</div>
		
		<div data-linea="2">
			<label for="transportista">Razón Social: </label> <input type="text"
				id="transportista" name="transportista" placeholder="Razón Social"
				maxlength="512" />
		</div>

		<div data-linea="3">
			<button type="button" class="buscar" id="buscar_trans">Buscar</button>
		</div>
		<hr>
		<div data-linea="4">
			<label for="identificador_operador_transportista">Transportista: </label>
			<select id="identificador_operador_transportista"
				name="identificador_operador_transportista" disabled>
				<option value="">Seleccionar...</option>
			</select>
		</div>

	</fieldset>
	<fieldset>
		<legend>Detalle de Productos a Movilizar</legend>

		<div data-linea="1">
			<label for="id_producto">Producto: </label> <select id="id_producto"
				disabled name="id_producto">
				<option value="">Seleccionar...</option>
				<?php echo $this->productos; ?>
			</select>
		</div>

		<div data-linea="2">
			<label for="cantidad_producto">Cantidad a Movilizar: </label> <input
				type="text" id="cantidad_producto" name="cantidad_producto"
				placeholder="Ej. 3" maxlength="8" />
		</div>

		<div data-linea="3">
			<button type="button" class="mas" id="agregar">Agregar</button>
		</div>

		<table id="detalleProducto" style="width: 100%">
			<tbody>

				<tr>
					<th>Producto</th>
					<th>Cantidad</th>
					<th>Eliminar</th>
				</tr>
			
			
			<tbody id="bodyTbl">

			</tbody>
		</table>

	</fieldset>
	<div data-linea="2">
		<button type="submit" class="guardar">Guardar</button>

	</div>

</form>
	
	
<script type="text/javascript">
	$(document).ready(function() {
		
		construirValidador();
		distribuirLineas();
		$("#id_sitio_origen").numeric();
		$("#transportista_identificador").numeric();
		$("#cantidad_producto").numeric();
		$("#identificador_operador_destino").numeric();
		$("#cantidad_producto").numeric();
		fn_limpiar();
		bloquearCamposDestino();
	 });
	var arreglo = [];
	var arrayAreas = <?php echo json_encode($this->areas); ?>;

	$("#formulario").submit(function (event) {
		event.preventDefault();
		event.stopImmediatePropagation();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		$(".guardar").attr('disabled','disabled');
		
		if(!$.trim($("#id_sitio_origen").val())){
			error = true;
			$("#id_sitio_origen").addClass("alertaCombo");
		}
		if(!$.trim($("#id_area_origen").val())){
			error = true;
			$("#id_area_origen").addClass("alertaCombo");
		}
		if(!$.trim($("#nombre_operador_destino").val())){
			error = true;
			$("#nombre_operador_destino").addClass("alertaCombo");
		}
		if(!$.trim($("#identificador_operador_destino").val())){
			error = true;
			$("#identificador_operador_destino").addClass("alertaCombo");
		}
		if(!$.trim($("#id_provincia").val())){
			error = true;
			$("#id_provincia").addClass("alertaCombo");
		}
		if(!$.trim($("#id_canton").val())){
			error = true;
			$("#id_canton").addClass("alertaCombo");
		}
		if(!$.trim($("#direccion_operador_destino").val())){
			error = true;
			$("#direccion_operador_destino").addClass("alertaCombo");
		}
		if(!$.trim($("#id_uso_suero").val())){
			error = true;
			$("#id_uso_suero").addClass("alertaCombo");
		}
		if(!$.trim($("#id_detalle_uso_suero").val())){
			error = true;
			$("#id_detalle_uso_suero").addClass("alertaCombo");
		}
		if(!$.trim($("#identificador_operador_transportista").val())){
			error = true;
			$("#identificador_operador_transportista").addClass("alertaCombo");
		}

		if(arreglo == 0 ){
			error = true;
			$("#id_producto").addClass("alertaCombo");
			$("#cantidad_producto").addClass("alertaCombo");
		}
		
		if (!error) {
			if(!$.trim($("#id_parroquia").val())){
				$("#id_parroquia").attr("disabled","disabled");
			}
			var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
			if (respuesta.estado == 'exito'){
				abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
				$("#id").val(respuesta.contenido);
				$($(this)).attr('data-opcion', 'Movilizacion/visorPdf');
				abrir($(this),event,false);
			}else {
				//agregar un error 
				}
		} else {
			$(".guardar").removeAttr('disabled');
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	 //Cuando seleccionamos sitio - agregamos áreas
    $("#id_sitio_origen").change(function() {
    	$(".alertaCombo").removeClass("alertaCombo");
    	$("#id_producto").html('<option value="">Seleccione...</option>');
		$("#id_producto").attr("disabled","disabled");
    	sArea = '<option value="">Seleccione...</option>';
	    for(var i=0; i < arrayAreas.length; i++) {
		    var spli = $("#id_sitio_origen").val();
		    area = spli.split('-');
		    if (area[0] == arrayAreas[i]['id_sitio'] ) {
			    $("#cod_provincia").val(area[1]);
		    	sArea += '<option value="'+arrayAreas[i]['id_area']+'">'+arrayAreas[i]['nombre_area']+'</option>';
		    }
	   	}
	    $('#id_area_origen').html(sArea);
	    limpiarCampos();
	    
	});
	
    //Cuando seleccionamos la provincia
    $("#id_provincia").change(function() {
        if($("#id_provincia").val() != ''){
        $.post("<?php echo URL ?>MovilizacionSueros/Movilizacion/buscarCantones", 
				{
				    idProvincia: $("#id_provincia").val()
				},
				function (data) {
	            	$("#id_canton").html(data);
	            	$("#id_canton").removeAttr("disabled");
	        	});
        }else {
        	 $("#id_canton").html('<option value="">Seleccione...</option>');
            }
        $("#id_parroquia").html('<option value="">Seleccione...</option>');
        
	});

  //Cuando seleccionamos la canton
    $("#id_canton").change(function() {
    	if($("#id_canton").val() != ''){
        $.post("<?php echo URL ?>MovilizacionSueros/Movilizacion/buscarParroquias", 
				{
				    idCanton: $("#id_canton").val()
				},
				function (data) {
	            	$("#id_parroquia").html(data);
	            	$("#id_parroquia").removeAttr("disabled");
	        	});
    	}else {
       	 $("#id_parroquia").html('<option value="">Seleccione...</option>');
           }
	});


  //Cuando seleccionamos la provincia
    $("#id_uso_suero").change(function() {
        if($("#id_uso_suero").val() != ''){
        $.post("<?php echo URL ?>MovilizacionSueros/Movilizacion/buscarDetalleSuero", 
				{
				    idUsoSuero: $("#id_uso_suero").val()
				},
				function (data) {
	            	$("#id_detalle_uso_suero").html(data);
	            	$("#id_detalle_uso_suero").removeAttr("disabled");
	        	});
        }else {
        	 $("#id_detalle_uso_suero").html('<option value="">Seleccione...</option>');
            }
        
	});
//******************buscar producto************************************
//Cuando seleccionamos la canton
    $("#id_area_origen").change(function() {
    	$(".alertaCombo").removeClass("alertaCombo");
    	if($("#id_area_origen").val() != ''){
        $.post("<?php echo URL ?>MovilizacionSueros/Movilizacion/buscarProductos", 
				{
				    idAreaOrigen: $("#id_area_origen").val()
				},
				function (data) {
					if(data != 'FALLO'){
		            	$("#id_producto").html(data);
		            	$("#id_producto").removeAttr("disabled");
					}else{
						$("#id_producto").html('<option value="">Seleccione...</option>');
						$("#id_producto").attr("disabled","disabled");
						$("#id_producto").addClass("alertaCombo");
						 mostrarMensaje("No existen productos a movilizar..!!", "FALLO");
					} 
	        	});
    	}else {
       	 $("#id_producto").html('<option value="">Seleccione...</option>');
           }
	});
  //****************buscar transporte ********************************
  $("#buscar_trans").click(function() {
	  mostrarMensaje("", "");
	      $("#esperarMovi").append("<div id='cargando'>Cargando...</div>").fadeIn();
	        $.post("<?php echo URL ?>MovilizacionSueros/Movilizacion/buscarTransporte", 
					{
					    idtransportista: $("#transportista_identificador").val(),
					    transportista: $("#transportista").val()
					},
					function (data) {
					  if(data != 'FALLO'){
		            	$("#identificador_operador_transportista").html(data);
		            	$("#identificador_operador_transportista").removeAttr("disabled");
					  }else{
						  $("#identificador_operador_transportista").html('<option value="">Seleccione...</option>');
						  $("#identificador_operador_transportista").attr("disabled","disabled");
						  mostrarMensaje("No existen registros.", "FALLO");
					 }
		        	});
        	$("#cargando").remove();
        	$("#transportista_identificador").val('');
        	$("#transportista").val('');

  });
//****************validar identificador********************************
  $("#identificador_operador_destino").change(function() {
	  var datos = [];
	  mostrarMensaje("", "");
	      $("#esperarMovi").append("<div id='cargando'>Cargando...</div>").fadeIn();
	        $.post("<?php echo URL ?>MovilizacionSueros/Movilizacion/validarRuc", 
					{
					    identificador: $("#identificador_operador_destino").val()
					},
					function (data) {
						var respuesta = JSON.parse(data);
					  if(respuesta.estado == 'exito'){
						  desbloquearCamposDestino();
					  }else{
						  bloquearCamposDestino();
						  mostrarMensaje(respuesta.mensaje, "FALLO");
					 }
		        	});
        	$("#cargando").remove();

  });
  //******************** agregar productos *********************************************
    $("#agregar").click(function(event) {
    	event.preventDefault();
       	mostrarMensaje("", "");
    	$(".alertaCombo").removeClass("alertaCombo");
    	var error = false;

    	if(!$.trim($("#id_producto").val())){
			error = true;
			$("#id_producto").addClass("alertaCombo");
		}

        if (!$.trim($("#cantidad_producto").val())) {
        	error = true;
			$("#cantidad_producto").addClass("alertaCombo");
        }
        if(!error){
            var valores = $("#cantidad_producto").val();
            var datos='';
        	$("#detalleProducto tbody tr").find("."+ $("#id_producto").val() +"").each(function() {
            	 valores = parseFloat(valores) + parseFloat($(this).html());
            });
            
			datos = {"idProducto":$("#id_producto").val(),"cantidad":$("#cantidad_producto").val(),"producto":$("#id_producto option:selected").text()};
        	arreglo.push(datos);
        	
        	$.post("<?php echo URL ?>MovilizacionSueros/Movilizacion/agregarProducto", 
					{
					    idProducto: $("#id_producto").val(),
					    cantidad: $("#cantidad_producto").val(),
					    producto: $("#id_producto option:selected").text(),
					    total: valores,
					    arreglo : arreglo
					},
					function (data) {
						  if(data != 'FALLO'){
							 $("#bodyTbl").html(data);
							 $("#cantidad_producto").val('');
							 $("#id_producto").val(""); 
							 $("#listDetalleProducto").val(JSON.stringify(arreglo));
						  }else{
							  arreglo.pop();
							  $("#listDetalleProducto").val(JSON.stringify(arreglo));
							  mostrarMensaje("No dispone la cantidad de suero solicitado..!!.", "FALLO");
						  }
		        	});
		}else{
			mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
		}
    });
    //************limpiar campos*********************************************
    
    function limpiarCampos(){
    	    mostrarMensaje("", "");
			$("#nombre_operador_destino").val('');
			$("#identificador_operador_destino").val('');
			$("#id_provincia").val('');
			$("#id_canton").html('<option value="">Seleccione...</option>');
			$("#id_parroquia").html('<option value="">Seleccione...</option>');
			$("#direccion_operador_destino").val(''); 
			$("#transportista_identificador").val('');
			$("#transportista").val('');
			$("#id_uso_suero").val('');
			$("#id_detalleuso_suero").val('');
			$("#identificador_operador_transportista").val('');
			$("#id_producto").val('');
			$("#cantidad_producto").val('');
			$("#bodyTbl").html('');
			 arreglo=[];
			 $("#detalleProducto").val("");
			 $("#listDetalleProducto").val("");
			
    }
   

    function bloquearCamposDestino(){
	    mostrarMensaje("", "");
		$("#nombre_operador_destino").val('');
		$("#id_provincia").val('');
		$("#id_canton").html('<option value="">Seleccione...</option>');
		$("#id_parroquia").html('<option value="">Seleccione...</option>');
		$("#direccion_operador_destino").val(''); 
		$("#id_uso_suero").val('');
		$("#id_detalleuso_suero").val('');

		$("#nombre_operador_destino").attr("disabled","disabled");
		$("#id_provincia").attr("disabled","disabled");
		$("#id_canton").attr("disabled","disabled");
		$("#id_parroquia").attr("disabled","disabled");
		$("#direccion_operador_destino").attr("disabled","disabled");
		$("#id_uso_suero").attr("disabled","disabled");
		$("#id_detalle_uso_suero").attr("disabled","disabled");
    }
    function desbloquearCamposDestino(){
	    mostrarMensaje("", "");
	    $("#nombre_operador_destino").val('');
		$("#id_provincia").val('');
		$("#id_canton").html('<option value="">Seleccione...</option>');
		$("#id_parroquia").html('<option value="">Seleccione...</option>');
		$("#direccion_operador_destino").val(''); 
		$("#id_uso_suero").val('');
		$("#id_detalle_uso_suero").val('');

		$("#nombre_operador_destino").removeAttr("disabled");
		$("#id_provincia").removeAttr("disabled");
		$("#id_canton").removeAttr("disabled");
		$("#id_parroquia").removeAttr("disabled");
		$("#direccion_operador_destino").removeAttr("disabled");
		$("#id_uso_suero").removeAttr("disabled");
		$("#id_detalle_uso_suero").removeAttr("disabled");
    }
   
</script>