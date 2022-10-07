<?php 
	
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorEventoSanitario.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCatalogos();
	$listaCatalogos = new ControladorEventoSanitario();
	
	$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
	$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');
	$oficina = $cc->listarSitiosLocalizacion($conexion,'SITIOS');
	
	$origenNotificaciones = $listaCatalogos->listarCatalogos($conexion,'ORIGEN');
	$canalNotificaciones = $listaCatalogos->listarCatalogos($conexion,'CANAL');
	$medidas = $listaCatalogos->listarCatalogos($conexion,'MEDIDA');
	$decisiones = $listaCatalogos->listarCatalogos($conexion,'DECISION');
	$bioseguridades = $listaCatalogos->listarCatalogos($conexion,'DECISION');
	$zonas = $listaCatalogos->listarCatalogos($conexion,'ZONAS');

	$identificador = $_SESSION['usuario'];
	
	if($identificador==''){
		$usuario=0;
	}else{
		$usuario=1;
	}
?>

<header>
	<h1>Eventos Sanitarios</h1>
</header>

<div id="estado"></div>

<form id="nuevoEventosSanitarios" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="guardarEventoSanitario" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="archivo" name="archivo" value="0" />	
	<input type="hidden" id="identificador" name="identificador" value="<?php echo $identificador; ?>" />
		

	<fieldset>
		<legend>Información General</legend>

		<div data-linea="2">
			<label>Fecha:</label>
			<input type="text" id="fecha" name="fecha" />
		</div>
		
		<div data-linea="3">
			<label>Origen de la Notificación:</label>
				<select id="origenNotificacion" name="origenNotificacion">
					<option value="">Origen....</option>
					<?php 
						while ($origen = pg_fetch_assoc($origenNotificaciones)){
							echo '<option value="' . $origen['codigo'] . '">' . $origen['nombre'] . '</option>';
						}
					?>
				</select> 
				<input type="text" id="nombreOrigen" name="nombreOrigen" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
		</div>
		
		<div data-linea="4">
			<label>Canal de la Notificación:</label>
				<select id="canalNotificacion" name="canalNotificacion">
					<option value="">Canal....</option>
					<?php 
						while ($canal = pg_fetch_assoc($canalNotificaciones)){
							echo '<option value="' . $canal['codigo'] . '">' . $canal['nombre'] . '</option>';
						}
					?>
				</select> 
				<input type="hidden" id="nombreCanal" name="nombreCanal"/>
		</div>
		
	</fieldset>
	
	<fieldset>
		<legend>Información de la finca</legend>
		
		<div data-linea="3">
			<label>Nombre del propietario:</label>
			<input type="text" id="nombrePropietario" name="nombrePropietario" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required="required"/>
		</div>
		
		<div data-linea="3">
			<label>Número de Cedula:</label>
			<input type="text" id="cedulaPropietario" name="cedulaPropietario" maxlength="13" data-er="^[0-9]+$" />
		</div>
		
		<div data-linea="4">
			<label>Teléfono:</label>
			<input type="text" id="telefonoPropietario" name="telefonoPropietario" maxlength="16" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" data-inputmask="'mask': '(99) 999-9999'" size="15"/>
		</div>
		
		<div data-linea="4">
			<label>Celular:</label>
			<input type="text" id="celularPropietario" name="celularPropietario" maxlength="16" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" data-inputmask="'mask': '(99) 999-9999'" size="15"/>
		</div>
		
		<div data-linea="5">
			<label>Correo Electrónico:</label>
			<input type="text" id="correoElectronicoPropietario" name="correoElectronicoPropietario" maxlength="32" data-er="^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$" />
		</div>
		
		<div data-linea="5">
			<label>Nombre del Predio:</label>
			<input type="text" id="nombrePredio" name="nombrePredio" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
		</div>
		
		<div data-linea="6">
			<label>Extención del Predio:</label>
			<input type="text" id="extencionPredio" name="extencionPredio" maxlength="16" data-er="^[0-9]+$" size="15"/>
		</div>
		
		<div data-linea="6">
			<label>Unidad Medida:</label>
			<select id="unidadMedida" name="unidadMedida">
				<option value="">Unidad de Medida....</option>
				<?php 
					while ($medida = pg_fetch_assoc($medidas)){
						echo '<option value="' . $medida['codigo'] . '">' . $medida['nombre'] . '</option>';
					}
				?>
			</select> 
			<input type="hidden" id="medidaPredio" name="medidaPredio"/>
		</div>
		
		<div data-linea="7">
			<label>Tiene otro predio:</label>
				<select id="otroPredio" name="otroPredio">
					<option value="">Seleccione....</option>
					<?php 
						while ($decision = pg_fetch_assoc($decisiones)){
							echo '<option value="' . $decision['codigo'] . '">' . $decision['nombre'] . '</option>';
						}
					?>
				</select> 
		</div>
		
		<div data-linea="6">
			<label id = "lnumeroPredios">Número  de Predios:</label>
			<input type="text" id="numeroPredios" name="numeroPredios" maxlength="16" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" size="15"/>
		</div>
		
		<div data-linea="7">
			<label>Tiene medidas de Bioseguridad:</label>
				<select id="bioseguridad" name="bioseguridad">
					<option value="">Seleccione....</option>
					<?php 
						while ($bioseguridad = pg_fetch_assoc($bioseguridades)){
							echo '<option value="' . $bioseguridad['codigo'] . '">' . $bioseguridad['nombre'] . '</option>';
						}
					?>
				</select> 
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Ubicación del Predio</legend>

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
			<label>Oficina</label>
			<select id="oficina" name="oficina" disabled="disabled">
			</select>
				
			<input type="hidden" id="nombreOficina" name="nombreOficina"/>
		</div>
		
		<div data-linea="6">
			<label>Sitio:</label>
			<input type="text" id="sitioPredio" name="sitioPredio" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
		</div>
			
		<div data-linea="7">
			<label>Semana:</label>
			<select id="semana" name="semana">
				<option value="">Seleccione....</option>
				<?php 
					$semana = 1;
					while ($semana <= 52){
						echo '<option value="' . $semana . '">' . $semana . '</option>';
						$semana++;
					}
				?>
			</select> 
		</div>
				
		<fieldset>
		<legend>Coordenadas</legend>
		
		<div data-linea="7">
			<label>Huso o Zona:</label>
				<select id="zonaPredio" name="zonaPredio">
					<option value="">Seleccione....</option>
					<?php 
						while ($zona = pg_fetch_assoc($zonas)){
							echo '<option value="' . $zona['codigo'] . '">' . $zona['nombre'] . '</option>';
						}
					?>
				</select> 
		</div>
		
		<div data-linea="8">
			<label> UTM X:</label>
			<input type="text" id="utmX" name="utmX" maxlength="6" data-er="^[0-9]+$" />
		</div>
		
		<div data-linea="8">
			<label>UTM Y:</label>
			<input type="text" id="utmY" name="utmY" maxlength="7" data-er="^[0-9]+$" />
		</div>
		
		<div data-linea="8">
			<label>UTM Z:</label>
			<input type="text" id="utmZ" name="utmZ" maxlength="4" data-er="^[0-9]+$" />
		</div>
		
	</fieldset>
		
	</fieldset>
	
	<fieldset id="adjuntosInforme">
		<legend>Adjuntar Mapa</legend>

		<div data-linea="11">
			<input type="file" class="archivo" name="mapa" accept="application/pdf" /> 
			
			<input type="hidden" class="rutaArchivo" name="archivoInforme" value="0" />
			
			<div class="estadoCarga">
				En espera de archivo... (Tamaño máximo; <?php echo ini_get('upload_max_filesize');?>B)
			</div>
			
			<button type="button" class="subirArchivoInforme" data-rutaCarga="aplicaciones/seguimientoEventosSanitarios/eventoSanitario/mapa">Subir archivo</button>
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
		
		$('#nombreOrigen').hide();
		$("#numeroPredios").hide();
		$("#lnumeroPredios").hide();

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
	
	$("#nuevoEventosSanitarios").submit(function(event){
		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#fecha").val())){
			error = true;
			$("#fecha").addClass("alertaCombo");
		}

		if(!$.trim($("#origenNotificacion").val())){
			error = true;
			$("#origenNotificacion").addClass("alertaCombo");
		}
		
		if(!$.trim($("#canalNotificacion").val())){
			error = true;
			$("#canalNotificacion").addClass("alertaCombo");
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
		
		if(!$.trim($("#correoElectronicoPropietario").val()) || !esCampoValido("#correoElectronicoPropietario")){
			error = true;
			$("#correoElectronicoPropietario").addClass("alertaCombo");
		}
		
		if(!$.trim($("#nombrePredio").val()) || !esCampoValido("#nombrePredio")){
			error = true;
			$("#nombrePredio").addClass("alertaCombo");
		}
		
		if(!$.trim($("#extencionPredio").val()) || !esCampoValido("#extencionPredio")){
			error = true;
			$("#extencionPredio").addClass("alertaCombo");
		}
		
		if(!$.trim($("#medidaPredio").val())){
			error = true;
			$("#medidaPredio").addClass("alertaCombo");
		}
		
		if(!$.trim($("#otroPredio").val())){
			error = true;
			$("#otroPredio").addClass("alertaCombo");
		}
		
		if(!$.trim($("#bioseguridad").val())){
			error = true;
			$("#bioseguridad").addClass("alertaCombo");
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
		
		if(!$.trim($("#oficina").val())){
			error = true;
			$("#oficina").addClass("alertaCombo");
		}
		
		if(!$.trim($("#semana").val())){
			error = true;
			$("#semana").addClass("alertaCombo");
		}
		
		if(!$.trim($("#zonaPredio").val())){
			error = true;
			$("#zonaPredio").addClass("alertaCombo");
		}


		if(!$.trim($("#sitioPredio").val()) || !esCampoValido("#sitioPredio")){
			error = true;
			$("#sitioPredio").addClass("alertaCombo");
		}
		
		if(!$.trim($("#utmX").val()) || !esCampoValido("#utmX")){
			error = true;
			$("#utmX").addClass("alertaCombo");
		}
		
		if(!$.trim($("#utmY").val()) || !esCampoValido("#utmY")){
			error = true;
			$("#utmY").addClass("alertaCombo");
		}
		
		if(!$.trim($("#utmZ").val()) || !esCampoValido("#utmZ")){
			error = true;
			$("#utmZ").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			abrir($(this),event,false);
		}
	});
	
	$("#origenNotificacion").change(function(event){
		if($("#origenNotificacion option:selected").val()!='0'){
        	$('#nombreOrigen').hide();
    		$("#nombreOrigen").val($("#origenNotificacion option:selected").text());
        }else{
        	$("#nombreOrigen").val('');
    	    $('#nombreOrigen').show();
        }
	});
	
	$("#otroPredio").change(function(event){
		if($("#otroPredio option:selected").val() =='0'){
			$("#numeroPredios").val('0');
			$("#numeroPredios").hide();
			$("#lnumeroPredios").hide();
		}else{
			$("#numeroPredios").val('');
			$("#numeroPredios").show();
			$("#lnumeroPredios").show();
		}
	});

	$("#canalNotificacion").change(function(){
    	$("#nombreCanal").val($("#canalNotificacion option:selected").text());
	});
	
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
    	$("#nombreOficina").val($("#oficina option:selected").text());
	});
	
	$("#unidadMedida").change(function(){
    	$("#medidaPredio").val($("#unidadMedida option:selected").text());
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
