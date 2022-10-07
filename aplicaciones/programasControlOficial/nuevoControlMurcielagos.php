<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCatalogos();
	
	$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
	$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');
	$oficina = $cc->listarSitiosLocalizacion($conexion,'SITIOS');
	
	$identificador = $_SESSION['usuario'];
	
	if($identificador==''){
		$usuario=0;
	}else{
		$usuario=1;
	}
?>

<header>
	<h1>Identificación y Supervisión de Refugios de Murciélagos Hematófagos</h1>
</header>

<div id="estado"></div>

<form id="nuevoControlMurcielagosHematofagos" data-rutaAplicacion="programasControlOficial" data-opcion="guardarControlMurcielagosHematofagos" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="archivo" name="archivo" value="0" />
	<input type="hidden" id="archivoInforme" name="archivoInforme" value="0" />	
	<input type="hidden" id="identificador" name="identificador" value="<?php echo $identificador; ?>" />

	<fieldset>
		<legend>Información de Identificación del Refugio</legend>
		
		<div data-linea="1">
			<label>Fecha:</label>
			<input type="text" id="fecha" name="fecha" />
		</div>
		
		<div data-linea="2">
			<label>Nombre del Predio:</label>
			<input type="text" id="nombrePredio" name="nombrePredio" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
		</div>
		
		<div data-linea="2">
			<label>Nombre del Propietario:</label>
			<input type="text" id="nombrePropietario" name="nombrePropietario" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
		</div>
		
		<div data-linea="3">
			<label>Persona que conoce el refugio:</label>
			<input type="text" id="personaRefugio" name="personaRefugio" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
		</div>
		
		<div data-linea="21">
				<label>Tipo de Refugio:</label>
					<select id="refugio" name="refugio" required="required" >
						<option value="">Refugio....</option>
						<option value="1">Alcantarilla</option>
						<option value="2">Casa Abandonada</option>
						<option value="3">Caverna</option>
						<option value="4">Hueco de Árbol</option>						
						<option value="5">Puente</option>
						<option value="0">Otros</option>
					</select>
			</div>	
			
			<div data-linea="21">	
				<input type="text" id="nombreRefugio" name="nombreRefugio" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
			</div>

	</fieldset>
	
	<fieldset>
		<legend>Ubicación y Datos Generales</legend>

		<div data-linea="4">
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
			
		<div data-linea="4">
			<label>Cantón</label>
				<select id="canton" name="canton" disabled="disabled">
				</select>
				
				<input type="hidden" id="nombreCanton" name="nombreCanton"/>
			</div>
			
			<div data-linea="5">	
			<label>Parroquia</label>
				<select id="parroquia" name="parroquia" disabled="disabled">
				</select>
				
				<input type="hidden" id="nombreParroquia" name="nombreParroquia"/>
				<input type="hidden" id="codigoParroquia" name="codigoParroquia"/>
			</div>
				
		<div data-linea="5">
			<label>Sitio:</label>
			<input type="text" id="sitio" name="sitio" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
		</div>
		
		<div data-linea="6">	
			<label>Oficina Agrocalidad</label>
				<select id="oficina" name="oficina" disabled="disabled">
				</select>
		</div>
		
		<div data-linea="6">	
			<input type="text" id="nombreOficina" name="nombreOficina" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
		</div>
		
	</fieldset>
	
	<fieldset>
		<legend>Coordenadas</legend>		

		<div data-linea="7">
			<label>X:</label>
			<input type="text" id="x" name="x" maxlength="6" data-er="^[0-9]+$" />
		</div>
		
		<div data-linea="7">
			<label>Y:</label>
			<input type="text" id="y" name="y" maxlength="7" data-er="^[0-9]+$" />
		</div>
		
		<div data-linea="7">
			<label>Z:</label>
			<input type="text" id="z" name="z" maxlength="4" data-er="^[0-9]+$" />
		</div>
		
		<!-- >div data-linea="8">
			<label>Altitud:</label>
			<input type="text" id="altitud" name="altitud" maxlength="16" data-er="^[0-9.]+$" />
		</div-->
		
	</fieldset>
	
	<fieldset id="adjuntos">
		<legend>Mapa de Ubicación</legend>

		<div data-linea="10">
			<input type="file" class="archivo" name="informe" accept="application/pdf" /> 
			
			<input type="hidden" class="rutaArchivo" name="archivo" value="0" />
			
			<div class="estadoCarga">
				En espera de archivo... (Tamaño máximo; <?php echo ini_get('upload_max_filesize');?>B)
			</div>
			
			<button type="button" class="subirArchivo" data-rutaCarga="aplicaciones/programasControlOficial/mapa/MurcielagosHematofagos">Subir mapa</button>
		</div>
	</fieldset>
	
	<fieldset id="adjuntosInforme">
		<legend>Informe</legend>

		<div data-linea="11">
			<input type="file" class="archivo" name="informe" accept="application/pdf" /> 
			
			<input type="hidden" class="rutaArchivo" name="archivoInforme" value="0" />
			
			<div class="estadoCarga">
				En espera de archivo... (Tamaño máximo; <?php echo ini_get('upload_max_filesize');?>B)
			</div>
			
			<button type="button" class="subirArchivoInforme" data-rutaCarga="aplicaciones/programasControlOficial/informe/MurcielagosHematofagos">Subir informe</button>
		</div>
	</fieldset>
	
	<button type="submit" class="guardar">Guardar</button>

</form>


<script type="text/javascript">

var array_canton= <?php echo json_encode($cantones); ?>;
var array_parroquia= <?php echo json_encode($parroquias); ?>;
var array_oficina= <?php echo json_encode($oficina); ?>;

	$("document").ready(function(){
		distribuirLineas();	
		construirValidador();

		$("#fecha").datepicker({
		      changeMonth: true,
		      changeYear: true
		});

		$('#nombreOficina').hide();
		$('#nombreRefugio').hide();
	});
	
	//Validación y Guardado
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}
	
	$("#nuevoControlMurcielagosHematofagos").submit(function(event){
		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#fecha").val())){
			error = true;
			$("#fecha").addClass("alertaCombo");
		}

		/*if(!$.trim($("#nombrePredio").val()) || !esCampoValido("#nombrePredio")){
			error = true;
			$("#nombrePredio").addClass("alertaCombo");
		}

		if(!$.trim($("#nombrePropietario").val()) || !esCampoValido("#nombrePropietario")){
			error = true;
			$("#nombrePropietario").addClass("alertaCombo");
		}*/

		if(!$.trim($("#personaRefugio").val()) || !esCampoValido("#personaRefugio")){
			error = true;
			$("#personaRefugio").addClass("alertaCombo");
		}

		if(!$.trim($("#refugio").val())){
			error = true;
			$("#refugio").addClass("alertaCombo");
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

		if(!$.trim($("#sitio").val()) || !esCampoValido("#sitio")){
			error = true;
			$("#sitio").addClass("alertaCombo");
		}

		if(!$.trim($("#oficina").val())){
			error = true;
			$("#oficina").addClass("alertaCombo");
		}

		if(!$.trim($("#x").val()) || !esCampoValido("#x")){
			error = true;
			$("#x").addClass("alertaCombo");
		}

		if(!$.trim($("#y").val()) || !esCampoValido("#y")){
			error = true;
			$("#y").addClass("alertaCombo");
		}

		if(!$.trim($("#z").val()) || !esCampoValido("#z")){
			error = true;
			$("#z").addClass("alertaCombo");
		}

		/*if(!$.trim($("#altitud").val()) || !esCampoValido("#altitud")){
			error = true;
			$("#altitud").addClass("alertaCombo");
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

		soficina ='0';
		soficina = '<option value="">Seleccione...</option>';
	    for(var i=0;i<array_oficina.length;i++){
		    if ($("#canton").val()==array_oficina[i]['padre']){
		    	soficina += '<option value="'+array_oficina[i]['codigo']+'">'+array_oficina[i]['nombre']+'</option>';
			    } 
	    	}
	    soficina += '<option value="0">Otro</option>';

	    $('#oficina').html(soficina);
		$("#oficina").removeAttr("disabled");
	});

    $("#parroquia").change(function(){
    	$("#nombreParroquia").val($("#parroquia option:selected").text());
    	$("#codigoParroquia").val($("#parroquia option:selected").attr('data-codigo'));
	});

    $("#oficina").change(function(){
        if($("#oficina option:selected").val()!='0'){
        	$('#nombreOficina').hide();
    		$("#nombreOficina").val($("#oficina option:selected").text());
        }else{
        	$("#nombreOficina").val('');
    	    $('#nombreOficina').show();
        }
	});

    $("#refugio").change(function(){
        if($("#refugio option:selected").val()!='0'){
        	$('#nombreRefugio').hide();
    		$("#nombreRefugio").val($("#refugio option:selected").text());
        }else{
        	$("#nombreRefugio").val('');
    	    $('#nombreRefugio').show();
        }
	});

	//Archivo Mapa
	$('button.subirArchivo').click(function (event) {
	
	    var boton = $(this);
	    var archivo = boton.parent().find(".archivo");
	    var rutaArchivo = boton.parent().find(".rutaArchivo");
	    var extension = archivo.val().split('.');
	    var estado = boton.parent().find(".estadoCarga");
	    numero = Math.floor(Math.random()*100000000);
	    
	    if (extension[extension.length - 1].toUpperCase() == 'PDF') {
	        subirArchivo(archivo, $("#identificador").val() +"_"+numero, boton.attr("data-rutaCarga"), rutaArchivo, new carga(estado, archivo, boton)); 
	    } else {
	        estado.html('Formato incorrecto, sólo se admite archivos en formato PDF');
	        archivo.val("0");
	    }        
	});

	//Archivo informe
	$('button.subirArchivoInforme').click(function (event) {
	
		var boton = $(this);
	    var archivo = boton.parent().find(".archivo");
	    var rutaArchivo = boton.parent().find(".rutaArchivo");
	    var extension = archivo.val().split('.');
	    var estado = boton.parent().find(".estadoCarga");
	    numero = Math.floor(Math.random()*100000000);
	    
	    if (extension[extension.length - 1].toUpperCase() == 'PDF') {
	        subirArchivo(archivo, $("#identificador").val() +"_"+numero, boton.attr("data-rutaCarga"), rutaArchivo, new carga(estado, archivo, boton)); 
	    } else {
	        estado.html('Formato incorrecto, sólo se admite archivos en formato PDF');
	        archivo.val("0");
	    }        
	});
</script>