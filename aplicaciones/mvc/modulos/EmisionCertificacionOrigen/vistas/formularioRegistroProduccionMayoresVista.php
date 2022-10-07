<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>EmisionCertificacionOrigen' data-opcion='registroProduccion/guardarProduccion' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
	<fieldset id="centroFaenamiento">
		<legend>Datos Centro de Faenamiento</legend>				

		<div data-linea="1">
			<label for="provincia">Provincia: </label>
			<select id="provincia">
				<?php echo $this->comboProvinciaCf();?>
			</select>
		</div>				

		<div data-linea="1">
			<label for="sitio">Sitio: </label>
			<select id="sitio">
				<option value="">Seleccionar....</option>
			</select>
		</div>				

		<div data-linea="2">
			<label for="area">Área: </label>
			<select id="area">
				<option value="">Seleccionar....</option>
			</select>
		</div>				

		<div data-linea="2">
			<label for="codigo_area">Código de área: </label>
			<input type="text" id="codigo_area" name="codigo_area" 
			disabled readonly />
		</div>				
		
	</fieldset >
	<fieldset id="produccionDiariaAgregada">
		<legend>Producción diaria Especies mayores</legend>				

		<div data-linea="1">
			<label for="fecha_faenamiento">Fecha de faenamiento: </label>
			<input type="text" id="fecha_faenamiento" name="fecha_faenamiento" value="<?php echo $this->modeloProductos->getFechaFaenamiento();?>"
			placeholder="Fecha de faenamiento" readonly />
		</div>	
		<div data-linea="2">
			<label for="fecha_recepcion">Fecha recepción animales: </label>
			<input type="text" id="fecha_recepcion" name="fecha_recepcion" value="<?php echo $this->modeloProductos->getFechaRecepcion();?>"
			placeholder="Fecha de recepción" readonly disabled/>
		</div>			

		<div data-linea="3">
			<label for="tipo_especie">Especie: </label>
			<select id="tipo_especie">
			    <option value="">Seleccionar....</option>
				<?php // echo $this->especie;?>
			</select>
			
		</div>				
       <div data-linea="4">
			<label for="num_animales_recibidos">N° Animales recibidos: </label>
			<select id="num_animales_recibidos">
				<?php echo $this->comboNumeros(500);?>
			</select>
		</div>	
		<div data-linea="5">
			<label for="num_canales_obtenidos">N° Canales obtenidos: </label>
			<select id="num_canales_obtenidos">
				<option value="">Seleccionar....</option>
			</select>
		</div>				

		<div data-linea="6">
			<label for="num_canales_obtenidos_uso">N° Canales obtenidas sin restricción de uso: </label>
			<select id="num_canales_obtenidos_uso">
				<option value="">Seleccionar....</option>
			</select>
		</div>				

		<div data-linea="7">
			<label for="num_canales_uso_industri">N° Canales para uso industrial: </label>
			<span id="num_canales_uso_industri"> </span>
		</div>				
		<div data-linea="8">
			<button type="button" class="guardar" id="produccionDiaria">Agregar</button>
		</div>
	</fieldset >
	<fieldset>
		<legend>Productos agregados</legend>	
		<div id="productosAgregados" style="width:100%">
			<?php echo $this->productosAgregados;?>
		</div>			
		
	</fieldset >
	<fieldset id="subproductosSN">
		<legend>Producción diaria: Subproductos Especies mayores</legend>				

		<div data-linea="1" id="opcionRadio">
			<label for="id_registro_produccion">Se obtuvieron subproductos: </label>
			<input  name="resultado" type="radio"  id="Si"   value="Si" onclick="verificarOpcion(id);"><span> Si</span>&nbsp;&nbsp;&nbsp;&nbsp;
			<input  name="resultado" type="radio"  id="No" value="No" onclick="verificarOpcion(id);"><span> No</span>&nbsp;&nbsp;&nbsp;&nbsp;

		</div>				

		<div data-linea="2" id="campoEspecie">
			<label for="tipo_especie_sub">Productos agregados: </label>
			<select id="tipo_especie_sub">
				<option value="">Seleccionar....</option>
			</select>
		</div>				
		<div data-linea="3" id="campoSubproducto">
			<label for="subproducto">Subproducto: </label>
			<select id="subproducto">
				<option value="">Seleccionar....</option>
			</select>
		</div>	
		<div data-linea="4" id="campoCantidad">
			<label for="cantidad">Cantidad: </label>
			<select id="cantidad">
				<option value="">Seleccionar....</option>
			</select>
		</div>				

		<div data-linea="7" id="campoAgregar">
			<button type="button" id="agregarSubproducto" class="guardar">Agregar</button>
		</div>
	</fieldset >
	<fieldset>
		<legend>Subproductos agregados</legend>				
       <div id="subProductosAgregados" style="width:100%">
       <?php echo $this->subProductosAgregados;?></div>	
		
	</fieldset >
	<div data-linea="7">
			<button type="submit" id="buttonGuardar" class="guardar">Guardar</button>
		</div>
		<div data-linea="7">
			<button type="button" class="" id="borrarRegistro">Eliminar</button>
		</div>
		<input type="hidden" name="tipo" value="<?php echo $this->tipo;?>">
</form >
 <div id="cargarMensajeTemporal"></div>
<script type ="text/javascript">
   var tipo = <?php echo json_encode($this->tipo);?>; 
   var visualizar  = <?php echo json_encode($this->visualizar);?>;
   var idproducto  = <?php echo json_encode($this->idRegistro);?>;
	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
		$("#estado").html("");
		$("#campoEspecie").hide();
		$("#campoSubproducto").hide();
		$("#campoCantidad").hide();
		$("#campoAgregar").hide();
		$("#borrarRegistro").hide();

		if(visualizar=='si'){
			$("#produccionDiariaAgregada").hide();
			$("#produccionDiaria").hide();
			$("#buttonGuardar").hide();
			$("#centroFaenamiento").hide();
			$("#subproductosSN").hide();
		}
		if(visualizar=='eliminar'){
			$("#produccionDiariaAgregada").hide();
			$("#buttonGuardar").hide();
			$("#subproductosSN").hide();
			$("#produccionDiaria").hide();
			$("#centroFaenamiento").hide();
 			$("#borrarRegistro").show();
		}
		
	 });

	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;
		$(".alertaCombo").removeClass("alertaCombo");
		var resultado =  $("input[name='resultado']").map(function(){ if($(this).prop("checked")){return $(this).val();}}).get();
		if(resultado == ''){
			error=true;
			$("#opcionRadio").addClass("alertaCombo");
			}
		if (!error) {
			abrir($(this), event, false);
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#fecha_faenamiento").datepicker({
    	yearRange: "c:c",
    	changeMonth: false,
        changeYear: false,
        dateFormat: 'yy-mm-dd',
        minDate: -1,
        maxDate: 0,
        onSelect: function(dateText, inst) {

        	var fecha=new Date($('#fecha_faenamiento').datepicker('getDate'));
        	
    		fecha.setDate(fecha.getDate()-1);
    	  	fecha.setMonth(fecha.getMonth());
    		fecha.setUTCFullYear(fecha.getUTCFullYear());  
    		
    		$('#fecha_recepcion').datepicker('option', 'minDate', fecha);
    		$('#fecha_recepcion').datepicker('option', 'maxDate', fecha);
    		$('#fecha_recepcion').datepicker( 'setDate', fecha );
        }
        
      });
	$("#fecha_recepcion").datepicker({
    	yearRange: "c:c",
    	changeMonth: false,
        changeYear: false,
        dateFormat: 'yy-mm-dd'
      });
	 $("#num_animales_recibidos").change(function (event){
    	 if($("#num_animales_recibidos").val() != ''){
    		  $.post("<?php echo URL ?>EmisionCertificacionOrigen/registroProduccion/listarCanalObtenido", 
    	              {
    			         numCanalesObtenidos:$("#num_animales_recibidos").val(),
    	              }, function (data) {
    	              	if (data.estado === 'EXITO') {
    	              		    $("#num_canales_obtenidos").html(data.contenido);
    	              		    $("#num_canales_obtenidos_uso").html('<option value="">Seleccionar....</option>');
    	              		    $("#num_canales_uso_industri").html('');
    		                    mostrarMensaje(data.mensaje, data.estado);
    	                  } else {
    	                  	mostrarMensaje(data.mensaje, "FALLO");
    	                  }
    	      }, 'json');
		  }
    });
    $("#num_canales_obtenidos").change(function (event){
    	 if($("#num_canales_obtenidos").val() != ''){
    		  $.post("<?php echo URL ?>EmisionCertificacionOrigen/registroProduccion/listarCanalSinRestrUso", 
    	              {
    			         numCanalesObtenidos:$("#num_canales_obtenidos").val(),
    	              }, function (data) {
    	              	if (data.estado === 'EXITO') {
    	              		    $("#num_canales_obtenidos_uso").html(data.contenido);
    	              		    $("#num_canales_uso_industri").html('');
    		                    mostrarMensaje(data.mensaje, data.estado);
    	                  } else {
    	                  	mostrarMensaje(data.mensaje, "FALLO");
    	                  }
    	      }, 'json');
		  }
    });
    
    $("#num_canales_obtenidos_uso").change(function (event){
   	 if($("#num_canales_obtenidos_uso").val() != ''){
   		  $.post("<?php echo URL ?>EmisionCertificacionOrigen/registroProduccion/listarCanalIndustr", 
   	              {
   			         numCanalesObtenidos:$("#num_canales_obtenidos").val(),
   			         numCanalesObtenidosUso:$("#num_canales_obtenidos_uso").val(),
   	              }, function (data) {
   	              	if (data.estado === 'EXITO') {
   	              		    $("#num_canales_uso_industri").html(data.contenido);
   		                    mostrarMensaje(data.mensaje, data.estado);
   	                  } else {
   	                  	mostrarMensaje(data.mensaje, "FALLO");
   	                  }
   	      }, 'json');
		  }
   });

    $("#produccionDiaria").click(function (event){
      	event.preventDefault();
		var error = false;
		$(".alertaCombo").removeClass("alertaCombo");

		if(!$.trim($("#fecha_recepcion").val())){
			   $("#fecha_recepcion").addClass("alertaCombo");
			   error = true;
		}
		if(!$.trim($("#tipo_especie").val())){
			   $("#tipo_especie").addClass("alertaCombo");
			   error = true;
		}
		if(!$.trim($("#num_animales_recibidos").val())){
			   $("#num_animales_recibidos").addClass("alertaCombo");
			   error = true;
		}
		if(!$.trim($("#num_canales_obtenidos").val())){
			   $("#num_canales_obtenidos").addClass("alertaCombo");
			   error = true;
		}
		if(!$.trim($("#num_canales_obtenidos_uso").val())){
			   $("#num_canales_obtenidos_uso").addClass("alertaCombo");
			   error = true;
		}
		
		if (!error) {
			$("#cargarMensajeTemporal").html("<div id='cargando'>Cargando...</div>");
			$("#fecha_faenamiento").attr('disabled',false);
			 $.post("<?php echo URL ?>EmisionCertificacionOrigen/registroProduccion/agregarProduccion", 
     	              {
        				 fecha_recepcion:$("#fecha_recepcion").val(),
        				 fecha_faenamiento:$("#fecha_faenamiento").val(),
        				 tipo_especie:$("#tipo_especie").val(),
        				 num_animales_recibidos:$("#num_animales_recibidos").val(),
        				 num_canales_obtenidos:$("#num_canales_obtenidos").val(),
        				 num_canales_obtenidos_uso:$("#num_canales_obtenidos_uso").val(),
        				 num_canales_uso_industri:$("#num_canales_obtenidos").val() - $("#num_canales_obtenidos_uso").val()
        			         
     	              }, function (data) {
     	            	 $("#cargarMensajeTemporal").html("");
     	              	if (data.estado === 'EXITO') {
     	              		$("#productosAgregados").html(data.contenido);
     	              		$("#tipo_especie_sub").html(data.subContenido);
     	              		$("#subproducto").val('');
    	            		$("#cantidad").val('');
		                    mostrarMensaje(data.mensaje, data.estado);
		                    distribuirLineas();
     	                  } else {
     	                  	mostrarMensaje(data.mensaje, "FALLO");
     	                  	$("#subproducto").val('');
    	            		$("#cantidad").val('');
     	                  }
     	      }, 'json');
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
      });

    function eliminarProducto(id){
    	$("#cargarMensajeTemporal").html("<div id='cargando'>Cargando...</div>");
    	$.post("<?php echo URL ?>EmisionCertificacionOrigen/registroProduccion/eliminarProduccion", 
	              {
			     id:id
	              }, function (data) {
	            	 $("#cargarMensajeTemporal").html("");
	              	if (data.estado === 'EXITO') {
	              		$("#productosAgregados").html(data.contenido);
	              		$("#tipo_especie_sub").html(data.subContenido);
	              		$("#subproducto").val('');
	            		$("#cantidad").val('');
	                    mostrarMensaje(data.mensaje, data.estado);
	                    distribuirLineas();
	                  } else {
	                  	mostrarMensaje(data.mensaje, "FALLO");
	                  }
	      }, 'json');
        }
    function verificarOpcion(id){
    	if(id=='Si'){
    		$("#campoEspecie").show();
    		$("#campoSubproducto").show();
    		$("#campoCantidad").show();
    		$("#campoAgregar").show();
    		distribuirLineas();
    	}else{
    		$("#campoEspecie").hide();
    		$("#campoSubproducto").hide();
    		$("#campoCantidad").hide();
    		$("#campoAgregar").hide();

    		$("#tipo_especie_sub").val('');
    		$("#subproducto").val('');
    		$("#cantidad").val('');
    		
    	}
    }
    
    $("#tipo_especie_sub").change(function (event){
      	 if($("#tipo_especie_sub").val() != ''){
      		  $.post("<?php echo URL ?>EmisionCertificacionOrigen/registroProduccion/agregarSubproducto", 
      	              {
      			         tipoEspecieSub:$("#tipo_especie_sub").val(),
      	              }, function (data) {
      	              	if (data.estado === 'EXITO') {
      	              		    $("#subproducto").html(data.contenido);
      	              		    $("#cantidad").html('<option value="">Seleccionar...</option>');
      		                    mostrarMensaje(data.mensaje, data.estado);
      	                  } else {
      	                	 $("#subproducto").html('<option value="">Seleccionar...</option>');
      	                	 $("#cantidad").html('<option value="">Seleccionar...</option>');
      	                  	mostrarMensaje(data.mensaje, "FALLO");
      	                  }
      	      }, 'json');
   		  }else{
   			$("#subproducto").html('<option value="">Seleccionar...</option>'); 
   			$("#cantidad").html('<option value="">Seleccionar...</option>');
   		  }
      });
    
    $("#subproducto").change(function (event){
     	 if($("#subproducto").val() != ''){
     		  $.post("<?php echo URL ?>EmisionCertificacionOrigen/registroProduccion/numPiezaSubproducto", 
     	              {
     						tipoEspecie:$("#tipo_especie_sub").val(),
     						numPiezas:$("#subproducto").val()
     	              }, function (data) {
     	              	if (data.estado === 'EXITO') {
     	              		    $("#cantidad").html(data.contenido);
     		                    mostrarMensaje(data.mensaje, data.estado);
     	                  } else {
     	                  	mostrarMensaje(data.mensaje, "FALLO");
     	                  }
     	      }, 'json');
  		  }else{
  			 $("#cantidad").html('<option value="">Seleccionar...</option>');
  		  }
     });

    $("#agregarSubproducto").click(function (event){
      	event.preventDefault();
		var error = false;
		$(".alertaCombo").removeClass("alertaCombo");

		if(!$.trim($("#tipo_especie_sub").val())){
			   $("#tipo_especie_sub").addClass("alertaCombo");
			   error = true;
		}
		if(!$.trim($("#subproducto").val())){
			   $("#subproducto").addClass("alertaCombo");
			   error = true;
		}
		if(!$.trim($("#cantidad").val())){
			   $("#cantidad").addClass("alertaCombo");
			   error = true;
		}
		
		if (!error) {
			$("#cargarMensajeTemporal").html("<div id='cargando'>Cargando...</div>");
			 $.post("<?php echo URL ?>EmisionCertificacionOrigen/registroProduccion/agregarSubProduccion", 
     	              {
				         tipo_especie_sub:$("#tipo_especie_sub").val(),
				         subproducto:$("#subproducto option:selected").text(),
				         cantidad:$("#cantidad").val()
        			         
     	              }, function (data) {
     	            	 $("#cargarMensajeTemporal").html("");
     	              	if (data.estado === 'EXITO') {
     	              		$("#subProductosAgregados").html(data.contenido);
		                    mostrarMensaje(data.mensaje, data.estado);
		                    distribuirLineas();
     	                  } else {
     	                  	mostrarMensaje(data.mensaje, "FALLO");
     	                  }
     	      }, 'json');
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
      });

    function eliminarSubproducto(id){
    	$("#cargarMensajeTemporal").html("<div id='cargando'>Cargando...</div>");
    	$.post("<?php echo URL ?>EmisionCertificacionOrigen/registroProduccion/eliminarSubproduccion", 
	              {
			     id:id
	              }, function (data) {
	            	 $("#cargarMensajeTemporal").html("");
	              	if (data.estado === 'EXITO') {
	              		$("#subProductosAgregados").html(data.contenido);
	              		$("#subproducto").val('');
	            		$("#cantidad").val('');
	                    mostrarMensaje(data.mensaje, data.estado);
	                    distribuirLineas();
	                  } else {
	                  	mostrarMensaje(data.mensaje, "FALLO");
	                  }
	      }, 'json');
        }

    $("#borrarRegistro").click(function (event) {
    	$.post("<?php echo URL ?>EmisionCertificacionOrigen/registroProduccion/eliminarRegistro", 
	              {
			       id:idproducto
	              }, function (data) {
	            	 $("#cargarMensajeTemporal").html("");
	              	if (data.estado === 'EXITO') {
	              		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
	                    mostrarMensaje(data.mensaje, data.estado);
	                  } else {
	                  	mostrarMensaje(data.mensaje, "FALLO");
	                  }
	      }, 'json');
	});

    $("#provincia").change(function (event){
    	if($("#provincia").val() != ''){
    	$.post("<?php echo URL ?>EmisionCertificacionOrigen/registroProduccion/buscarSitio", 
	              {
			       id:$("#provincia").val()
	              }, function (data) {
	              	if (data.estado === 'EXITO') {
	              		$("#sitio").html(data.contenido);
	                    mostrarMensaje(data.mensaje, data.estado);
	                  } else {
	                  	mostrarMensaje(data.mensaje, "FALLO");
	                  }
	      }, 'json');
    	}else{
    		$("#sitio").html('<option value="">Seleccionar....</option>');
    		$("#tipo_especie").html('<option value="">Seleccionar....</option>');
    		$("#codigo_area").val('');
    		$("#area").html('<option value="">Seleccionar....</option>');
    	}
	});

    $("#sitio").change(function (event){
    	if($("#sitio").val() != ''){
    	$.post("<?php echo URL ?>EmisionCertificacionOrigen/registroProduccion/buscarArea", 
	              {
			       id:$("#sitio").val()
	              }, function (data) {
	              	if (data.estado === 'EXITO') {
	              		$("#area").html(data.contenido);
	                    mostrarMensaje(data.mensaje, data.estado);
	                  } else {
	                  	mostrarMensaje(data.mensaje, "FALLO");
	                  }
	      }, 'json');
    	}else{
    		$("#tipo_especie").html('<option value="">Seleccionar....</option>');
    		$("#codigo_area").val('');
    		$("#area").html('<option value="">Seleccionar....</option>');
    	}
	});
    $("#area").change(function (event){
    	if($("#area").val() != ''){
    	$.post("<?php echo URL ?>EmisionCertificacionOrigen/registroProduccion/buscarEspecie", 
	              {
			       id:$("#area").val()
	              }, function (data) {
	              	if (data.estado === 'EXITO') {
	              		$("#tipo_especie").html(data.contenido);
	              		$("#codigo_area").val(data.codigo);
	                    mostrarMensaje(data.mensaje, data.estado);
	                  } else {
	                  	mostrarMensaje(data.mensaje, "FALLO");
	                  }
	      }, 'json');
    	}else{
    		$("#tipo_especie").html('<option value="">Seleccionar....</option>');
    		$("#codigo_area").val('');
        	}
	});
</script>
