<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCatalogos();
	
	$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
	$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');
	
?>

<header>
	<h1>Predios para Certificación como Libres de Brucelosis y Tuberculosis Bovina</h1>
</header>

<div id="estado"></div>

<form id="nuevaCertificacionBT" data-rutaAplicacion="certificacionBrucelosisTuberculosis" data-opcion="guardarCertificacionBT" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">	

	<fieldset>
		<legend>Información de Localización del Predio</legend>

		<div data-linea="1">
			<label>Fecha:</label>
			<input type="text" id="fecha" name="fecha" />
		</div>
		
		<div data-linea="2">
			<label>Nombre del Encuestado:</label>
			<input type="text" id="nombreEncuestado" name="nombreEncuestado" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
		</div>
		
		<div data-linea="2">
			<label>Nombre del Predio:</label>
			<input type="text" id="nombrePredio" name="nombrePredio" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
		</div>
		
		<div data-linea="3">
			<label>Num. Cert. Fiebre Aftosa:</label>
			<input type="text" id="numCertFiebreAftosa" name="numCertFiebreAftosa" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
		</div>
		
		<div data-linea="3">
			<label>Certificación:</label>
					<select id="certificacion" name="certificacion" required="required" >
						<option value="">Certificación....</option>
						<option value="Brucelosis">Brucelosis</option>
						<option value="Tuberculosis">Tuberculosis</option>
					</select> 	
		</div>
		
	</fieldset>
	
	<fieldset>
		<legend>Información del Propietario</legend>
		
		<div data-linea="4">
			<label>Nombre:</label>
			<input type="text" id="nombrePropietario" name="nombrePropietario" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
		</div>
		
		<div data-linea="4">
			<label>Cédula:</label>
			<input type="text" id="cedulaPropietario" name="cedulaPropietario" maxlength="13" data-er="^[0-9]+$"/>
		</div>
		
		<div data-linea="5">
			<label>Teléfono:</label>
			<input type="text" id="telefonoPropietario" name="telefonoPropietario" maxlength="16" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" data-inputmask="'mask': '(99) 999-9999'" size="15"/>
		</div>
		
		
		<div data-linea="5">
			<label>Celular:</label>
			<input type="text" id="celularPropietario" name="celularPropietario" maxlength="16" data-er="^\([0-9]{2}\) [0-9]{4}-[0-9]{4}" data-inputmask="'mask': '(99) 9999-9999'" size="16"/>
		</div>
		
		<div data-linea="6">
			<label>Correo Electrónico:</label>
			<input type="text" id="correoElectronicoPropietario" name="correoElectronicoPropietario" maxlength="32" data-er="^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$" />
		</div>
		
	</fieldset>
	
	<fieldset>
		<legend>Ubicación y Datos Generales</legend>

		<div data-linea="7">
			<label>Provincia</label>
				<select id="provincia" name="provincia">
					<option value="">Provincia....</option>
					<?php 
						$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
						foreach ($provincias as $provincia){
							echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
						}
					?>
				</select> 
				
				<input type="hidden" id="nombreProvincia" name="nombreProvincia"/>
				
			</div>
			
		<div data-linea="7">
		<label>Cantón</label>
			<select id="canton" name="canton" disabled="disabled">
			</select>
			
			<input type="hidden" id="nombreCanton" name="nombreCanton"/>
		</div>
		
		<div data-linea="8">	
		<label>Parroquia</label>
			<select id="parroquia" name="parroquia" disabled="disabled">
			</select>
			
			<input type="hidden" id="nombreParroquia" name="nombreParroquia"/>
			<input type="hidden" id="codigoParroquia" name="codigoParroquia"/>
		</div>
		
	</fieldset>
	
	<fieldset>
		<legend>Coordenadas</legend>
		

		<div data-linea="9">
			<label>X:</label>
			<input type="text" id="x" name="x" maxlength="16" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
		</div>
		
		<div data-linea="9">
			<label>Y:</label>
			<input type="text" id="y" name="y" maxlength="16" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
		</div>
		
		<div data-linea="9">
			<label>Z:</label>
			<input type="text" id="z" name="z" maxlength="16" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
		</div>
		
		<div data-linea="9">
			<label>Huso/Zona:</label>
			<input type="text" id="huso" name="huso" maxlength="16" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
		</div>
		
		<div data-linea="10">	
			<input type="hidden" id="latitud" name="latitud" />
			<input type="hidden" id="longitud" name="longitud" />
			<input type="hidden" id="zona" name="zona" />
			<input type="hidden" id="zoom" name="zoom"/>
		</div>

	</fieldset>
	
	<!-- fieldset>
		<legend>Información de Otros Predios</legend>
		
		<div data-linea="11">
			<label>Posee otros predios?:</label>
					<select id="otroPredio" name="otroPredio" required="required" >
						<option value="">Otro Predio....</option>
						<option value="Si">Si</option>
						<option value="No">No</option>
					</select> 	
		</div>
		
		<div data-linea="12">
			<label id="lNombrePredio2">Nombre del Predio:</label>
			<input type="text" id="nombrePredio2" name="nombrePredio2" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
		</div>
		
		<div data-linea="13">
			<label id="lProvincia2">Provincia</label>
				<select id="provincia2" name="provincia2">
					<option value="">Provincia....</option>
					< ?php 
						$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
						foreach ($provincias as $provincia){
							echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
						}
					?>
				</select> 
				
				<input type="hidden" id="nombreProvincia2" name="nombreProvincia2"/>
				
			</div>
			
		<div data-linea="13">
		<label id="lCanton2">Cantón</label>
			<select id="canton2" name="canton2" disabled="disabled">
			</select>
			
			<input type="hidden" id="nombreCanton2" name="nombreCanton2"/>
		</div>
		
		<div data-linea="14">	
		<label id="lParroquia2">Parroquia</label>
			<select id="parroquia2" name="parroquia2" disabled="disabled">
			</select>
			
			<input type="hidden" id="nombreParroquia2" name="nombreParroquia2"/>
		</div>

	</fieldset-->
	
	<button type="submit" class="guardar">Guardar</button>

</form>


<script type="text/javascript">

var array_canton= <?php echo json_encode($cantones); ?>;
var array_parroquia= <?php echo json_encode($parroquias); ?>;

var array_canton2= <?php echo json_encode($cantones); ?>;
var array_parroquia2= <?php echo json_encode($parroquias); ?>;

	$("document").ready(function(){
		distribuirLineas();	
		construirValidador();

		$("#fecha").datepicker({
		      changeMonth: true,
		      changeYear: true
		});
		
		/*$('#lNombrePredio2').hide();
		$('#nombrePredio2').hide();
		$('#lProvincia2').hide();
		$('#provincia2').hide();
		$('#lCanton2').hide();
		$('#canton2').hide();
		$('#lParroquia2').hide();
		$('#parroquia2').hide();*/
		
	});
	
	//Validación y Guardado
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}
	
	$("#nuevaCertificacionBT").submit(function(event){
		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#fecha").val())){
			error = true;
			$("#fecha").addClass("alertaCombo");
		}

		if(!$.trim($("#nombreEncuestado").val()) || !esCampoValido("#nombreEncuestado")){
			error = true;
			$("#nombreEncuestado").addClass("alertaCombo");
		}

		if(!$.trim($("#nombrePredio").val()) || !esCampoValido("#nombrePredio")){
			error = true;
			$("#nombrePredio").addClass("alertaCombo");
		}

		if(!$.trim($("#numCertFiebreAftosa").val())){
			error = true;
			$("#numCertFiebreAftosa").addClass("alertaCombo");
		}

		if(!$.trim($("#certificacion").val())){
			error = true;
			$("#certificacion").addClass("alertaCombo");
		}

		if(!$.trim($("#nombrePropietario").val()) || !esCampoValido("#nombrePropietario")){
			error = true;
			$("#nombrePropietario").addClass("alertaCombo");
		}

		if(!$.trim($("#cedulaPropietario").val()) || !esCampoValido("#cedulaPropietario")){
			error = true;
			$("#cedulaPropietario").addClass("alertaCombo");
		}

		if(!$.trim($("#telefonoPropietario").val()) || !esCampoValido("#telefonoPropietario")){
			error = true;
			$("#telefonoPropietario").addClass("alertaCombo");
		}

		if(!$.trim($("#celularPropietario").val()) || !esCampoValido("#celularPropietario")){
			error = true;
			$("#celularPropietario").addClass("alertaCombo");
		}

		if(!$.trim($("#correoElectronicoPropietario").val()) || !esCampoValido("#correoElectronicoPropietario")){
			error = true;
			$("#correoElectronicoPropietario").addClass("alertaCombo");
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

		if(!$.trim($("#x").val())){
			error = true;
			$("#x").addClass("alertaCombo");
		}

		if(!$.trim($("#y").val())){
			error = true;
			$("#y").addClass("alertaCombo");
		}

		if(!$.trim($("#z").val())){
			error = true;
			$("#z").addClass("alertaCombo");
		}

		if(!$.trim($("#huso").val())){
			error = true;
			$("#huso").addClass("alertaCombo");
		}
		/*if(!$.trim($("#otroPredio").val())){
			error = true;
			$("#otroPredio").addClass("alertaCombo");
		}

		if($("#otroPredio option:selected").text()=='Si'){
			if(!$.trim($("#nombrePredio2").val()) || !esCampoValido("#nombrePredio2")){
				error = true;
				$("#nombrePredio2").addClass("alertaCombo");
			}

			if(!$.trim($("#provincia2").val())){
				error = true;
				$("#provincia2").addClass("alertaCombo");
			}

			if(!$.trim($("#canton2").val())){
				error = true;
				$("#canton2").addClass("alertaCombo");
			}

			if(!$.trim($("#parroquia2").val())){
				error = true;
				$("#parroquia2").addClass("alertaCombo");
			}
		}*/

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			abrir($(this),event,false);
		}
	});
	
	
	//Mapa

	
	//Ubicación Provincia, Cantón, Parroquia, Oficina
	$("#provincia").change(function(event){
    	scanton ='0';
		scanton = '<option value="">Seleccione...</option>';
	    for(var i=0;i<array_canton.length;i++){
		    if ($("#provincia").val()==array_canton[i]['padre']){
		    	scanton += '<option data-latitud="'+array_canton[i]['latitud']+'"data-longitud="'+array_canton[i]['longitud']+'"data-zona="'+array_canton[i]['zona']+'" value="'+array_canton[i]['codigo']+'">'+array_canton[i]['nombre']+'</option>';
			}
	   	}
	    $('#canton').html(scanton);
	    $("#canton").removeAttr("disabled");
	    $("#nombreProvincia").val($("#provincia option:selected").text());	
	});

    $("#canton").change(function(){
    	$("#nombreCanton").val($("#canton option:selected").text());
        
		sparroquia ='0';
		sparroquia = '<option value="">Seleccione...</option>';
	    for(var i=0;i<array_parroquia.length;i++){
		    if ($("#canton").val()==array_parroquia[i]['padre']){
		    	sparroquia += '<option value="'+array_parroquia[i]['codigo']+'" data-codigo="'+array_parroquia[i]['codigoProvincia']+'">'+array_parroquia[i]['nombre']+'</option>';
			    } 
	    	}

	    $('#parroquia').html(sparroquia);
		$("#parroquia").removeAttr("disabled");
	});

    $("#parroquia").change(function(){
    	$("#nombreParroquia").val($("#parroquia option:selected").text());
    	$("#codigoParroquia").val($("#parroquia option:selected").attr('data-codigo'));
	});

    /*$("#provincia2").change(function(event){
    	scanton2 ='0';
		scanton2 = '<option value="">Seleccione...</option>';
	    for(var i=0;i<array_canton2.length;i++){
		    if ($("#provincia2").val()==array_canton2[i]['padre']){
		    	scanton2 += '<option data-latitud="'+array_canton2[i]['latitud']+'"data-longitud="'+array_canton2[i]['longitud']+'"data-zona="'+array_canton2[i]['zona']+'" value="'+array_canton2[i]['codigo']+'">'+array_canton2[i]['nombre']+'</option>';
			}
	   	}
	    $('#canton2').html(scanton2);
	    $("#canton2").removeAttr("disabled");
	    $("#nombreProvincia2").val($("#provincia2 option:selected").text());	
	});

    $("#canton2").change(function(){
    	$("#nombreCanton2").val($("#canton2 option:selected").text());
        
		sparroquia2 ='0';
		sparroquia2 = '<option value="">Seleccione...</option>';
	    for(var i=0;i<array_parroquia2.length;i++){
		    if ($("#canton2").val()==array_parroquia2[i]['padre']){
		    	sparroquia2 += '<option value="'+array_parroquia2[i]['codigo']+'" data-codigo="'+array_parroquia2[i]['codigoProvincia']+'">'+array_parroquia2[i]['nombre']+'</option>';
			    } 
	    	}

	    $('#parroquia2').html(sparroquia2);
		$("#parroquia2").removeAttr("disabled");
	});

    $("#parroquia2").change(function(){
    	$("#nombreParroquia2").val($("#parroquia2 option:selected").text());
	});

    $("#otroPredio").change(function(){
    	if($("#otroPredio option:selected").val()=='Si'){
    		$('#lNombrePredio2').show();
    		$('#nombrePredio2').show();
    		$('#nombrePredio2').attr("required","required");
    		$('#lProvincia2').show();
    		$('#provincia2').show();
    		$('#provincia2').attr("required","required");
    		$('#lCanton2').show();
    		$('#canton2').show();
    		$('#canton2').attr("required","required");
    		$('#lParroquia2').show();
    		$('#parroquia2').show();
    		$('#parroquia2').attr("required","required");
        }else{
        	$('#lNombrePredio2').hide();
    		$('#nombrePredio2').hide();
    		$('#nombrePredio2').removeAttr("required","required");
    		$('#lProvincia2').hide();
    		$('#provincia2').hide();
    		$('#provincia2').removeAttr("required","required");
    		$('#lCanton2').hide();
    		$('#canton2').hide();
    		$('#canton2').removeAttr("required","required");
    		$('#lParroquia2').hide();
    		$('#parroquia2').hide();
    		$('#parroquia2').removeAttr("required","required");
        }
	});*/

</script>