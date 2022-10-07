<?php 
	
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorNotificacionEventoSanitario.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCatalogos();
	$listaCatalogos = new ControladorNotificacionEventoSanitario();
	
	$cantones = $cc->listarSitiosLocalizacion($conexion,'CANTONES');
	$parroquias = $cc->listarSitiosLocalizacion($conexion,'PARROQUIAS');
	$oficina = $cc->listarSitiosLocalizacion($conexion,'SITIOS');
	
	$origenNotificaciones = $listaCatalogos->listarCatalogos($conexion,'ORIGEN');
	$canalNotificaciones = $listaCatalogos->listarCatalogos($conexion,'CANAL');
	
	$identificador = $_SESSION['usuario'];
	
	if($identificador==''){
		$usuario=0;
	}else{
		$usuario=1;
	}
?>

<header>
	<h1>Notificacion de Eventos Sanitarios</h1>
</header>

<div id="estado"></div>

<form id="nuevoNotificacionEventosSanitarios" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="guardarNotificacionEventoSanitario" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
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
		<legend>Datos Informante</legend>
		
		<div data-linea="3">
			<label>Nombre:</label>
			<input type="text" id="nombreInformante" name="nombreInformante" maxlength="32" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$" required="required"/>
		</div>
				
		<div data-linea="3">
			<label>Teléfono:</label>
			<input type="text" id="telefonoInformante" name="telefonoInformante" maxlength="16" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" data-inputmask="'mask': '(99) 999-9999'" size="15"/>
		</div>
		
		<div data-linea="4">
			<label>Celular:</label>
			<input type="text" id="celularInformante" name="celularInformante" maxlength="16" data-er="^\([0-9]{2}\) [0-9]{3}-[0-9]{4}( ext. [0-9]{1,4})?" data-inputmask="'mask': '(99) 999-9999'" size="15"/>
		</div>
		
		<div data-linea="4">
			<label>Correo Electrónico:</label>
			<input type="text" id="correoElectronicoInformante" name="correoElectronicoInformante" maxlength="32" data-er="^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$" />
		</div>
		
	</fieldset>
	
	<fieldset>
		<legend>Información del Predio</legend>

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
			<label>Sitio:</label>
			<input type="text" id="sitioPredio" name="sitioPredio" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
		</div>
		
		<div data-linea="6">
			<label>Finca:</label>
			<input type="text" id="fincaPredio" name="fincaPredio" maxlength="128" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü. ]+$"/>
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
	
	$("#nuevoNotificacionEventosSanitarios").submit(function(event){
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

		
		if(!$.trim($("#nombreInformante").val()) || !esCampoValido("#nombreInformante")){
			error = true;
			$("#nombreInformante").addClass("alertaCombo");
		}

		/*if(!$.trim($("#telefonoInformante").val()) || !esCampoValido("#telefonoInformante")){
			error = true;
			$("#telefonoInformante").addClass("alertaCombo");
		}*/

		/*if(!$.trim($("#celularInformante").val()) || !esCampoValido("#celularInformante")){
			error = true;
			$("#celularInformante").addClass("alertaCombo");
		}*/

		/*if(!$.trim($("#correoElectronicoInformante").val()) || !esCampoValido("#correoElectronicoInformante")){
			error = true;
			$("#correoElectronicoInformante").addClass("alertaCombo");
		}*/

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

		if(!$.trim($("#sitioPredio").val()) || !esCampoValido("#sitioPredio")){
			error = true;
			$("#sitioPredio").addClass("alertaCombo");
		}
		
		if(!$.trim($("#fincaPredio").val()) || !esCampoValido("#fincaPredio")){
			error = true;
			$("#fincaPredio").addClass("alertaCombo");
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
	});

    $("#parroquia").change(function(){
    	$("#nombreParroquia").val($("#parroquia option:selected").text());
    	$("#codigoParroquia").val($("#parroquia option:selected").attr('data-codigo'));
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
