<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';
$conexion = new Conexion();
$cro = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();
$cantones = $cc->listarSitiosLocalizacion($conexion, 'CANTONES');
$parroquias = $cc->listarSitiosLocalizacion($conexion, 'PARROQUIAS');
?>

<header>
	<h1>Nuevo operador</h1>
</header>

<form id='datosOperadorSitio'
	data-rutaAplicacion='registroMasivoOperadores'
	data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="opcion" name="opcion" value="0"> <input
		type="hidden" id="idFlujo" name="idFlujo" value="0">

	<div id="estado"></div>
	<div id="mensajeCargando"></div>

	<fieldset id="datosConsultaWebServices">
		<legend>Información del operador</legend>
		<div data-linea="1">
			<table width="100%">
				<tr>
					<td><input type="radio" name="clasificacion" value="Cédula"
						id="cedula" class="cedulaRUC">Cédula</td>
					<td><input type="radio" name="clasificacion" value="Pasaporte"
						id="pasaporte" class="cedulaRUC"> Pasaporte</td>
					<td><input type="radio" name="clasificacion" value="Refugiado"
						id="refugiado" class="cedulaRUC"> Refugiado</td>
				</tr>
				<tr>

					<td><input type="radio" name="clasificacion" value="Natural"
						id="natural" class="cedulaRUC"> RUC - P. Natural</td>
					<td><input type="radio" name="clasificacion" value="Juridica"
						id="juridica" class="cedulaRUC"> RUC - P. Jurídica</td>
					<td><input type="radio" name="clasificacion" value="Publica"
						id="publica" class="cedulaRUC"> RUC - Soc. Pública</td>
				</tr>
			</table>
			<hr>


		</div>


		<div data-linea="3">
			<label>Identificación</label> <input type="text" id="numero"
				name="numero" placeholder="Número de identificación"
				disabled="disabled" data-er="^([a-zA-Z0-9_]{7,13})+$">
		</div>
		<div data-linea="3">
			<label>Razón social</label> <input name="razonSocial" type="text"
				id="razonSocial" value="" placeholder="Ej: Nombre de la empresa"
				maxlength="200" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
		</div>
		<div data-linea="4">
			<label>Nombres</label> <input name="nombreLegal" type="text"
				id="nombreLegal" placeholder="Ej: Carlos Andres" maxlength="200"
				data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" />
		</div>
		<div data-linea="4">
			<label>Apellidos</label> <input name="apellidoLegal" type="text"
				id="apellidoLegal" placeholder="Ej: Hernandez Álvarez"
				maxlength="250" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" />
		</div>
		<div data-linea="5">
			<label>Correo electrónico</label> <input name="correoElectronico"
				type="text" id="correoElectronico"
				placeholder="Ej: andresperez@hotmail.com" maxlength="128"
				data-er="^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$" />
		</div>
	</fieldset>

	<fieldset>
		<legend>Coincidencia</legend>
		<div data-linea="1" id="resultadoVerificarOperador"></div>
	</fieldset>

	<fieldset id="informacionSitio">
		<legend>Información del sitio</legend>
		<div data-linea="1">
			<label>Nombre sitio</label> <input type="text" id="nombreSitio"
				name="nombreSitio" placeholder="Ej: Hacienda La Rosa"
				data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/°0-9 ]+$" />
		</div>
		<div data-linea="2">
			<label>Provincia</label> <select id="provincia" name="provincia">
				<option value="">Provincia....</option>
				<?php
    $provincias = $cc->listarSitiosLocalizacion($conexion, 'PROVINCIAS');
    foreach ($provincias as $provincia) {
        echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
    }
    ?>
			</select>
		</div>
		<div data-linea="2">
			<label>Cantón</label> <select id="canton" name="canton"
				disabled="disabled">
				<option value="">Cantón....</option>
			</select>
		</div>
		<div data-linea="2">
			<label>Parroquia</label> <select id="parroquia" name="parroquia"
				disabled="disabled">
				<option value="">Parroquia....</option>
			</select>
		</div>
		<div data-linea="3">
			<label>Dirección</label> <input type="text" id="direccion"
				name="direccion" placeholder="Ej: Santa Rosa"
				data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/°0-9 ]+$" />
		</div>

		<div data-linea="4">
			<label>Teléfono</label> <input name="telefono" type="text"
				id="telefono" placeholder="Ej: (02)375-7549"
				data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?"
				data-inputmask="'mask': '(99) 999-9999'" size="15" />
		</div>

		<div data-linea="4">
			<label>Celular</label> <input name="celular" type="text" id="celular"
				placeholder="Ej: (09)9759-7899"
				data-er="^\([0-9]{2}\) [0-9]{4}-[0-9]{4}"
				data-inputmask="'mask': '(99) 9999-9999'" size="15" />
		</div>
		<div data-linea="5">
			<label>Latitud</label> <input type="text" id="latitud" name="latitud"
				placeholder="785535.2557038852" maxlength="20" />
		</div>

		<div data-linea="5">
			<label>Longitud</label> <input type="text" id="longitud"
				name="longitud" placeholder="2431.0911238068647" maxlength="20" />
		</div>

		<div data-linea="5">
			<label>Zona</label> <input type="text" id="zona" name="zona"
				placeholder="17" maxlength="2" />
		</div>
	</fieldset>
	<fieldset id="agregarOperacion">
		<legend>Agregar Operación</legend>
		<div data-linea="1">
			<label>Área temática</label> <select id="areaOperacion"
				name="areaOperacion">
				<option value="">Seleccione...</option>
				<?php
    $tipoOperacionAreas = $cro->listarTipoOperacionArea($conexion);
    foreach ($tipoOperacionAreas as $tipoOperacionArea) {
        echo '<option  value="' . $tipoOperacionArea['id_area'] . '">' . $tipoOperacionArea['area_operacion'] . '</option>';
    }
    ?>
			</select>
		</div>
		<div id="resultadoTipoOperacion" data-linea="2"></div>
		<div id="resultadoTipoProducto" data-linea="3"></div>
		<div id="resultadoSubTipoProducto" data-linea="4"></div>
		<div id="resultadoProducto" data-linea="5"></div>
		<button type="button"class="mas" id="agregarProducto">Agregar producto</button>

	</fieldset>
	
	<fieldset>
		<legend>Productos agregados</legend>
		<table id="detalleProductos" style="width: 100%">
			<thead>
				<tr>
					<th>Tipo producto</th>
					<th>Subtipo producto</th>
					<th>Producto</th>
					<th>Opción</th>
				</tr>
			</thead>			
			<tbody>
			</tbody>
		</table>
	</fieldset>
	<div>
		<button type="submit" id="guardar" class="guardar">Guardar</button>
	</div>
</form>
<script type="text/javascript">

	$(document).ready(function(){
		$("#latitud").numeric();
		$("#longitud").numeric();
		$("#zona").numeric();
		distribuirLineas();
		construirValidador();
	});
	var array_canton= <?php echo json_encode($cantones); ?>;
	var array_parroquia= <?php echo json_encode($parroquias); ?>;

	$("#natural").change(function(){
		$("#numero").attr("maxlength","13");
		$("#numero").removeAttr("disabled");
		$("#razonSocial").removeAttr("disabled");
	});
	
	$("#juridica").change(function(){
		$("#numero").attr("maxlength","13");
		$("#numero").removeAttr("disabled");
		$("#razonSocial").removeAttr("disabled");
	});

	$("#publica").change(function(){
		$("#numero").attr("maxlength","13");
		$("#numero").removeAttr("disabled");
		$("#razonSocial").removeAttr("disabled");
	});
	
	$("#cedula").change(function(){
		$("#numero").attr("maxlength","10");
		$("#numero").removeAttr("disabled");
		var valor = $("#numero").val();
		$("#numero").val(valor.substring(0,10));
		$("#razonSocial").attr('disabled',true);
	});
	
	$("#pasaporte").change(function(){
		$("#numero").attr("maxlength","13");
		$("#numero").removeAttr("disabled");
		$("#razonSocial").attr('disabled',true);
	});

	
	$("#refugiado").change(function(){
		$("#numero").attr("maxlength","10");
		$("#numero").removeAttr("disabled");
		var valor = $("#numero").val();
		$("#numero").val(valor.substring(0,10));
		$("#razonSocial").attr('disabled',true);
	});

	$(".cedulaRUC").change(function(){
		$("#numero").val("");
		$("#razonSocial").val("");
	});
	
    $("#provincia").change(function(){
    	scanton ='0';
		scanton = '<option value="">Cantón...</option>';
	    for(var i=0;i<array_canton.length;i++){
		    if ($("#provincia").val()==array_canton[i]['padre']){
		    	scanton += '<option data-latitud="'+array_canton[i]['latitud']+'"data-longitud="'+array_canton[i]['longitud']+'"data-zona="'+array_canton[i]['zona']+'" value="'+array_canton[i]['codigo']+'">'+array_canton[i]['nombre']+'</option>';
			}
	   	}
	    $('#canton').html(scanton);
	    $("#canton").removeAttr("disabled");
	});

    $("#canton").change(function(){
		sparroquia ='0';
		sparroquia = '<option value="">Parroquia...</option>';
	    for(var i=0;i<array_parroquia.length;i++){
		    if ($("#canton").val()==array_parroquia[i]['padre']){
		    	sparroquia += '<option value="'+array_parroquia[i]['codigo']+'">'+array_parroquia[i]['nombre']+'</option>';
			} 
	    }
	    $('#parroquia').html(sparroquia);
		$("#parroquia").removeAttr("disabled");
	});
 
    $("#areaOperacion").change(function(event){
    	if($("#areaOperacion").length != 0){
    		$("#resultadoTipoOperacion").html('');
			$("#resultadoTipoProducto").html('');
			$("#resultadoSubTipoProducto").html('');
			$("#resultadoProducto").html('');
	 	 }
	 	 
		if($("#areaOperacion").val() != 0){	
			$('#datosOperadorSitio').attr('data-opcion','accionesOperadorMasivo');
    		$('#datosOperadorSitio').attr('data-destino','resultadoTipoOperacion');
    		$('#opcion').val('tipoOperacion');		
    		abrir($("#datosOperadorSitio"),event,false);		
		}			 	
	 });

	$("#datosOperadorSitio").submit(function(event){
		$("#datosOperadorSitio").attr('data-opcion','guardarNuevoOperadorMasivo');
		event.preventDefault();
		chequearCampos(this);	
		
	});

	$("#apellidoLegal").change(function () {
	      var value = $(this).val();
	      if($("#razonSocial").val()=="")
	      $("#razonSocial").val($("#nombreLegal").val()+" "+value);
	      
	    });

	function chequearCampos(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("input:radio[name=clasificacion]:checked").val() == null){
			error = true;
			$("#pasaporte").addClass("alertaCombo");
			$("#refugiado").addClass("alertaCombo");
			$("#cedula").addClass("alertaCombo");
			$("#natural").addClass("alertaCombo");
			$("#juridica").addClass("alertaCombo");	
			$("#publica").addClass("alertaCombo");	
		}
		
		if(!$.trim($("#numero").val()) || !esCampoValido("#numero") ){
			error = true;
			$("#numero").addClass("alertaCombo");
		}
		
		if(!$.trim($("#razonSocial").val()) || !esCampoValido("#razonSocial")) {
			error = true;
			$("#razonSocial").addClass("alertaCombo");
		}
		
		if(!$.trim($("#nombreSitio").val()) || !esCampoValido("#nombreSitio")){
			error = true;
			$("#nombreSitio").addClass("alertaCombo");
		}
		
		if(!$.trim($("#nombreLegal").val()) || !esCampoValido("#nombreLegal")){
			error = true;
			$("#nombreLegal").addClass("alertaCombo");
		}
		
		if(!$.trim($("#apellidoLegal").val()) || !esCampoValido("#apellidoLegal")){
			error = true;
			$("#apellidoLegal").addClass("alertaCombo");
		}
		
		if(!$.trim($("#direccion").val()) || !esCampoValido("#direccion")){
			error = true;
			$("#direccion").addClass("alertaCombo");
		}
		
		if(!$.trim($("#provincia").val())){
			error = true;
			$("#provincia").addClass("alertaCombo");
		}
		
		if(!$.trim($("#canton").val())){
			error = true;
			$("#canton").addClass("alertaCombo");
		}
		
		if(!$.trim($("#parroquia").val())){
			error = true;
			$("#parroquia").addClass("alertaCombo");
		}
		
		if(!$.trim($("#telefono").val()) || !esCampoValido("#telefono")){
			error = true;
			$("#telefono").addClass("alertaCombo");
		}
	
		if(!$.trim($("#celular").val()) || !esCampoValido("#celular")){
			error = true;
			$("#celular").addClass("alertaCombo");
		}

		if($.trim($("#correoElectronico").val()) != ""){
			if(!esCampoValido("#correoElectronico")){
    			error = true;
    			$("#correoElectronico").addClass("alertaCombo");
			}
		}
		
		if(!$.trim($("#areaOperacion").val())) {
			error = true;
			$("#areaOperacion").addClass("alertaCombo");
		}

		if(!$.trim($("#tipoProducto").val())) {
			error = true;
			$("#tipoProducto").addClass("alertaCombo");
		}

		if(!$.trim($("#subTipoProducto").val())) {
			error = true;
			$("#subTipoProducto").addClass("alertaCombo");
		}

		if(!$.trim($("#tipoOperacion").val())) {
			error = true;
			$("#tipoOperacion").addClass("alertaCombo");
		}
	
		if (error){
			$("#estado").html("Por favor ingrese o revise el formato toda información.").addClass('alerta');
		}else{
			$("#razonSocial").removeAttr("disabled");
			ejecutarJson(form);
			if($('#estado').html()=="El operador ya se encuentra registrado en Agrocalidad.")
				$("#razonSocial").attr('disabled',true);
		}
	}	
		
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}		

	$("#numero").change(function(event){
			event.preventDefault();
			
			$("#nombreLegal").val('');
    		$("#apellidoLegal").val('');
    		$("#razonSocial").val('');
			
			/*if($("input:radio[name=clasificacion]:checked").val() == "Refugiado" || $("input:radio[name=clasificacion]:checked").val() == "Pasaporte"){
				var $botones = $("form").find("button[type='submit']");
				$botones.removeAttr("disabled");
			}else{*/
				var $botones = $("form").find("button[type='submit']"),
		    	serializedData = $("#datosConsultaWebServices").serialize(),
				//url = "aplicaciones/general/consultaWebServices.php";
		    	url = "aplicaciones/general/consultaValidarIdentificacion.php";
		  		$botones.attr("disabled", "disabled");
		   	    resultado = $.ajax({
			    url: url,
			    type: "post",
			    data: serializedData,
			    dataType: "json",
			    async:   true,
			    beforeSend: function(){
			    	$("#estado").html('').removeClass();
			    	$("#mensajeCargando").html("<div id='cargando'>Cargando...</div>").fadeIn();
				},
				
			    success: function(msg){
			    	if(msg.estado=="exito"){
				    	$botones.removeAttr("disabled");
				    		
							validarOperador();
							
			    	}else{
			    		mostrarMensaje(msg.mensaje,"FALLO");
						inhabilitarRegistroInformacion();
				    }
			    		
			   },
			    error: function(jqXHR, textStatus, errorThrown){
			    	$("#cargando").delay("slow").fadeOut();
			    	mostrarMensaje("ERR: " + textStatus + ", " +errorThrown,"FALLO");
			    },
		        complete: function(){
		        	$("#cargando").delay("slow").fadeOut();
		        }
			});
			//}
	
	function validarOperador(){

    			var data = $("#datosOperadorSitio").serialize();
    		    $.ajax({
    		        type: "POST",
    		        data: data,
    		        url: "aplicaciones/registroMasivoOperadores/validarExistenciaOperador.php",
    		        dataType: "json",
    		        success: function(msg) {  
    			
    		        	if(msg.estado=="exito"){ 
    		    			//$("#estadoClave").html("Su clave ha sido actualizada con éxito");		    			
    		    			//$("#actualizarClave").modal('hide');	
    		        		$("#nombreLegal").val(msg.nombreRepresentante);
    		        		$("#apellidoLegal").val(msg.apellidoRepresentante);
    		        		$("#razonSocial").val(msg.razonSocial);
    
    		        		inhabilitarRegistroInformacion();
    		        		
    		        		//alert(msg.mensaje);
    
    		    		}else{
    		    			//mostrarMensajeCambioClave(msg.mensaje,"FALLO");
    		    			$("#informacionSitio").hide();
    		    			habilitarRegistroInformacion();
    		    			
    		        	}         	                       
    		        }/*,
    		        error: function(msg){
    		        	mostrarMensajeCambioClave(msg.mensaje,"FALLO");   
    		        	 
    		        }*/
    		    });	
    
    			$('#datosOperadorSitio').attr('data-opcion','accionesOperadorMasivo');
        		$('#datosOperadorSitio').attr('data-destino','resultadoVerificarOperador');
        		$('#opcion').val('verificarCoincidenciaOperador');		
        		abrir($("#datosOperadorSitio"),event,false);

			}

	});

	$("input[name=clasificacion]").click(function(event) {	
		$("#estado").html('').removeClass();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		$("#botonocultamuestra").hide();
		$("#divocultamuestra").hide();

		$("#nombreLegal").val("");
		$("#apellidoLegal").val("");		
		
	});

	function inhabilitarRegistroInformacion(){

		$("#informacionSitio").hide();
		$("#agregarOperacion").hide();
		$("#guardar").hide();
		
    	$("#nombreSitio").attr("disabled", true);
    	$("#provincia").attr("disabled", true);
    	$("#direccion").attr("disabled", true);
    	$("#telefono").attr("disabled", true);
    	$("#celular").attr("disabled", true);
    	$("#latitud").attr("disabled", true);
    	$("#longitud").attr("disabled", true);
    	$("#zona").attr("disabled", true);
    	$("#areaOperacion").attr("disabled",true);
    	$("#guardar").attr("disabled", true);

	}

	function habilitarRegistroInformacion(){

		$("#informacionSitio").show();
		$("#agregarOperacion").show();
		$("#guardar").show();
		
    	$("#nombreSitio").attr("disabled", false);
    	$("#provincia").attr("disabled", false);
    	$("#direccion").attr("disabled", false);
    	$("#telefono").attr("disabled", false);
    	$("#celular").attr("disabled", false);
    	$("#latitud").attr("disabled", false);
    	$("#longitud").attr("disabled", false);
    	$("#zona").attr("disabled", false);
    	$("#areaOperacion").attr("disabled", false);
    	$("#guardar").attr("disabled", false);

	}

	//Funcion para agregar puertos de país de destino//
    $("#agregarProducto").click(function(event) {
    	event.preventDefault();
       	mostrarMensaje("", "");
    	
    	$(".alertaCombo").removeClass("alertaCombo");
    	var error = false;

    	if($("#areaOperacion").val() == ""){
			error = true;
			$("#areaOperacion").addClass("alertaCombo");
		}
    	
    	if($("#tipoOperacion").val() == ""){
			error = true;
			$("#tipoOperacion").addClass("alertaCombo");
		}

    	if($("#tipoProducto").val() == ""){
			error = true;
			$("#tipoProducto").addClass("alertaCombo");
		}

    	if($("#subTipoProducto").val() == ""){
			error = true;
			$("#subTipoProducto").addClass("alertaCombo");
		}

		if(!error){

			if($("#areaOperacion").val() != "" && $("#tipoOperacion").val() != "" && $("#tipoProducto").val() != "" && $("#subTipoProducto").val() != ""){

                $('input[name="producto[]"]:checked').each(function() {

                	$('#areaOperacion option:not(:selected)').attr('disabled',true);
                	$('#tipoOperacion option:not(:selected)').attr('disabled',true);
                    
                	var tipoProducto = $("#tipoProducto option:selected").text();
                	var subtipoProducto = $("#subTipoProducto option:selected").text();
                	var nombreProducto = $(this).attr('data-nombreProducto');
                    var codigoDetalleProductos = 'r_' + $(this).val();
                	var cadena = '';
                	
                	if($("#detalleProductos tbody #" + codigoDetalleProductos.replace(/ /g,'')).length == 0){
                
                		cadena = "<tr id='" + codigoDetalleProductos.replace(/ /g,'') + "'>" +
                		"<td>" + tipoProducto +
                		"</td>" +
                		"<td>" + subtipoProducto +
                		"</td>" +
                		"<td>" + nombreProducto +
                		"<input name='iProducto[]' value='" + $(this).val() + "' type='hidden'>" +
                		"</td>" +
                		"<td>" +
                		"<button type='button' onclick='quitarDetalleProductos(" + codigoDetalleProductos.replace(/ /g,'') + ")' class='menos'>Quitar</button>" +
                		"</td>" +				
                		"</tr>"
                
                		$("#detalleProductos tbody").append(cadena);
                		
                	}               
                });  		
			}
		}
    });

  //Funcion que quita una fila de la tabla exportadores productos
    function quitarDetalleProductos(fila){
		$("#detalleProductos tbody tr").eq($(fila).index()).remove();		
		if($('#detalleProductos tbody tr').length == 0) {	
		   	$('#areaOperacion option:not(:selected)').attr('disabled',false);
		   	$('#tipoOperacion option:not(:selected)').attr('disabled',false);
		}
	}
	
</script>