<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();

$especies = $cc->listarEspecies($conexion);
$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');
?>

<header>
	<h1>Nueva notificación epidemiológica</h1>
</header>

<form id='nuevaNotificacion' data-rutaAplicacion='controlEpidemiologico' data-opcion='comboSitios' data-destino="comboSitio" data-accionEnExito="ACTUALIZAR">
	
	<div id="estado"></div>
	
	<fieldset>
		<legend>Información del notificante</legend>
			
			<div data-linea="1">
				<label>Número de identificación</label>
				<input type="text" id="identificadorNotificante" name="identificadorNotificante" placeholder="Número de identificación" data-er="^[0-9]+$" maxlength="10"/>
			</div>
			
			<div data-linea="2">			
				<label>Nombres</label> 
					<input type="text" id="nombreNotificante" name="nombreNotificante" placeholder="Ej: Juan" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" />
			</div>
			
			<div data-linea="2">			
				<label>Apellidos</label> 
					<input type="text" id="apellidoNotificante" name="apellidoNotificante" placeholder="Ej: Pérez" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" />
			</div>
			
			<div data-linea="3">
				<label>Teléfono</label> 
					<input type="text" id="telefonoNotificante" name="telefonoNotificante" placeholder="Ej: (02) 456-9857" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" data-inputmask="'mask': '(99) 999-9999'" />
			</div>
			
			<div data-linea="3">
				<label>Celular</label> 
					<input type="text" id="celularNotificante" name="celularNotificante" placeholder="Ej: (09) 9456-9857" data-er="^\([0-9]{2}\) [0-9]{4}-[0-9]{4}( ext. [0-9]{1,4})?" data-inputmask="'mask': '(99) 9999-9999'" />
			</div>
			
	</fieldset>
	
	<fieldset>
		<legend>Información del Propietario y Ubicación</legend>
			<table>
				<tr>
					<td><input type="radio" name="clasificacion" id="cedula" value="Cédula"></td>
					<td colspan = "2"><label for="cedula">Cédula de ciudadanía</label></td>
				
					<td><input type="radio" name="clasificacion" id="personaNatural" value="Natural"></td>
					<td colspan = "2"><label for="personaNatural">RUC - Persona natural</label></td>
				
					<td><input type="radio" name="clasificacion" id="personaJuridica" value="Juridica"></td>
					<td colspan = "2"><label for="personaJuridica">RUC - Persona jurídica</label></td>
				</tr>
			</table>
			
			<div data-linea="1">			
				<label>Cédula/RUC</label> 
					<input type="text" id="identificadorOperador" name="identificadorOperador" disabled="disabled" placeholder="Ej: 1915632485" data-er="^[0-9]+$" />
			</div>
			
			<div id="comboSitio"></div>
			
			<button type="button" id="registrarOperador" name="registrarOperador">Registrar operador</button>

	</fieldset>
		
	<fieldset>
		<legend>Información epidemiológica</legend>
			<div data-linea="1">			
				<label>Especie afectada</label> 
					<select id="especie" name="especie">
						<option value="">Especie....</option>
						<?php 
							foreach ($especies as $especie){
								echo '<option value="' . $especie['codigo'] . '">' . $especie['nombre'] . '</option>';
							}
						?>
					</select> 
					
					<input type="hidden" id="nombreEspecie" name="nombreEspecie" />
			</div>
			
			<div data-linea="1">			
				<label>Población afectada</label> 
					<input type="text" id="poblacionAfectada" name="poblacionAfectada" placeholder="Ej: 15" data-er="^[0-9]+$" />
			</div>
			
			<div data-linea="2">			
				<label>Patología denunciada</label> 
					<input type="text" id="patologia" name="patologia" placeholder="Ej: Fiebre aftosa" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>			
			
	</fieldset>
	
	<button type="submit" class="guardar">Guardar sitio</button> 

</form>
	
<script type="text/javascript">
	var array_canton= <?php echo json_encode($cantones); ?>;
	var array_parroquia= <?php echo json_encode($parroquias); ?>;

	$(document).ready(function(){
		distribuirLineas();
		construirAnimacion($(".pestania"));	
		$('<option value="<?php echo $canton['id_localizacion'];?>"><?php echo $canton['nombre'];?></option>').appendTo('#canton');
		$('<option value="<?php echo $parroquia['id_localizacion'];?>"><?php echo $parroquia['nombre'];?></option>').appendTo('#parroquia');
		construirValidador();

		$("#registrarOperador").hide();
	});

	$("#identificadorOperador").change(function(event){
		$("#registrarOperador").hide();
		$("#nuevaNotificacion").attr('data-opcion','comboSitios');
		$("#nuevaNotificacion").attr('data-destino','comboSitio');
		abrir($("#nuevaNotificacion"),event,false); //Se ejecuta ajax, busqueda de sitios		 		
	});

	$("#cedula").change(function(){
		$("#identificadorOperador").removeAttr("disabled");
		$("#identificadorOperador").attr("maxlength","10");
		$("#identificadorOperador").val('');
		$("#comboSitio").html('');
		$("#registrarOperador").hide();
	});

	$("#personaNatural").change(function(){
		$("#identificadorOperador").removeAttr("disabled");
		$("#identificadorOperador").attr("maxlength","13");
		$("#identificadorOperador").val('');
		$("#comboSitio").html('');
		$("#registrarOperador").hide();
	});
	
	$("#personaJuridica").change(function(){
		$("#identificadorOperador").removeAttr("disabled");
		$("#identificadorOperador").attr("maxlength","13");
		$("#identificadorOperador").val('');
		$("#comboSitio").html('');
		$("#registrarOperador").hide();
	});

	$("#especie").change(function(){
    	$("#nombreEspecie").val($("#especie option:selected").text());
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


    /*VALIDACION*/
    function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

    function chequearCampos(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#identificadorNotificante").val()) || !esCampoValido("#identificadorNotificante") || $("#identificadorNotificante").val().length != $("#identificadorNotificante").attr("maxlength")){
			error = true;
			$("#identificadorNotificante").addClass("alertaCombo");
		}

		if(!$.trim($("#nombreNotificante").val()) || !esCampoValido("#nombreNotificante")){
			error = true;
			$("#nombreNotificante").addClass("alertaCombo");
		}

		if(!$.trim($("#apellidoNotificante").val()) || !esCampoValido("#apellidoNotificante")){
			error = true;
			$("#apellidoNotificante").addClass("alertaCombo");
		}

		if(!$.trim($("#telefonoNotificante").val()) || !esCampoValido("#telefonoNotificante")){
			error = true;
			$("#telefonoNotificante").addClass("alertaCombo");
		}

		if(!$.trim($("#celularNotificante").val()) || !esCampoValido("#celularNotificante")){
			error = true;
			$("#celularNotificante").addClass("alertaCombo");
		}
		
		if(!$.trim($("#identificadorOperador").val()) || !esCampoValido("#identificadorOperador") || $("#identificadorOperador").val().length != $("#identificadorOperador").attr("maxlength")){
			error = true;
			$("#identificadorOperador").addClass("alertaCombo");
		}

		if(!$.trim($("#sitio").val()) || !esCampoValido("#sitio")){
			error = true;
			$("#sitio").addClass("alertaCombo");
			$("#comboSitio").addClass("alertaCombo");
		}

		if(!$.trim($("#especie").val()) || !esCampoValido("#especie")){
			error = true;
			$("#especie").addClass("alertaCombo");
		}

		if(!$.trim($("#poblacionAfectada").val()) || !esCampoValido("#poblacionAfectada")){
			error = true;
			$("#poblacionAfectada").addClass("alertaCombo");
		}

		if(!$.trim($("#patologia").val()) || !esCampoValido("#patologia")){
			error = true;
			$("#patologia").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
		}
	}

    $("#nuevaNotificacion").submit(function(event){
    	$("#nuevaNotificacion").attr('data-opcion','guardarNuevaNotificacion');
		$("#nuevaNotificacion").attr('data-destino','detalleItem');
		event.preventDefault();
		chequearCampos(this);	
	});

    $("#registrarOperador").click(function(event){	
    	$('#nuevaNotificacion').attr('data-rutaAplicacion','registroMasivoOperadores');
    	$('#nuevaNotificacion').attr('data-opcion','nuevoOperador');	
		$('#nuevaNotificacion').attr('data-destino','detalleItem');
		abrir($("#nuevaNotificacion"),event,false); 	 		
	 });

</script>