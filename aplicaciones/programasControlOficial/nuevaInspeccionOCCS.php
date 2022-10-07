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
	<h1>Catastro de Explotaciones Ovinas, Caprinas y de Camélidos Sudamericanos</h1>
</header>

<div id="estado"></div>

<form id="nuevaInspeccionOCCS" data-rutaAplicacion="programasControlOficial" data-opcion="guardarInspeccionOCCS" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="archivo" name="archivo" value="0" />	
	<input type="hidden" id="identificador" name="identificador" value="<?php echo $identificador; ?>" />

	<fieldset>
		<legend>Información de Identificación del Predio</legend>

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
			<label>Cédula del Propietario:</label>
			<input type="text" id="cedulaPropietario" name="cedulaPropietario" maxlength="13" data-er="^[0-9]+$"/>
		</div>
		
		<div data-linea="3">
			<label>Teléfono:</label>
			<input type="text" id="telefono" name="telefono" maxlength="16" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" data-inputmask="'mask': '(99) 999-9999'" size="15"/>
		</div>
		
		<div data-linea="4">
			<label>Correo Electrónico:</label>
			<input type="text" id="correoElectronico" name="correoElectronico" maxlength="32" data-er="^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$" />
		</div>
		
		<div data-linea="4">
			<label>Nombre Asociación:</label>
			<input type="text" id="nombreAsociacion" name="nombreAsociacion" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" />
		</div>

	</fieldset>
	
	<fieldset>
		<legend>Ubicación y Datos Generales</legend>

		<div data-linea="5">
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
			
		<div data-linea="5">
			<label>Cantón</label>
				<select id="canton" name="canton" disabled="disabled">
				</select>
				
				<input type="hidden" id="nombreCanton" name="nombreCanton"/>
			</div>
			
			<div data-linea="6">	
			<label>Parroquia</label>
				<select id="parroquia" name="parroquia" disabled="disabled">
				</select>
				
				<input type="hidden" id="nombreParroquia" name="nombreParroquia"/>
				<input type="hidden" id="codigoParroquia" name="codigoParroquia"/>
			</div>
				
		<div data-linea="6">
			<label>Sector:</label>
			<input type="text" id="sector" name="sector" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
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
		
		<!-- div data-linea="8">
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
			
			<button type="button" class="subirArchivo" data-rutaCarga="aplicaciones/programasControlOficial/mapa/InspeccionOCCS">Subir mapa</button>
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
			
			<button type="button" class="subirArchivoInforme" data-rutaCarga="aplicaciones/programasControlOficial/informe/InspeccionOCCS">Subir informe</button>
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
	});
	
	//Validación y Guardado
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}
	
	$("#nuevaInspeccionOCCS").submit(function(event){
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

		if(!$.trim($("#cedulaPropietario").val()) || !esCampoValido("#cedulaPropietario")){
			error = true;
			$("#cedulaPropietario").addClass("alertaCombo");
		}

		if(!$.trim($("#telefono").val()) || !esCampoValido("#telefono")){
			error = true;
			$("#telefono").addClass("alertaCombo");
		}

		if(!$.trim($("#correoElectronico").val()) || !esCampoValido("#correoElectronico")){
			error = true;
			$("#correoElectronico").addClass("alertaCombo");
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

		if(!$.trim($("#sector").val()) || !esCampoValido("#sector")){
			error = true;
			$("#sector").addClass("alertaCombo");
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
	});

    $("#parroquia").change(function(){
    	$("#nombreParroquia").val($("#parroquia option:selected").text());
    	$("#codigoParroquia").val($("#parroquia option:selected").attr('data-codigo'));
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