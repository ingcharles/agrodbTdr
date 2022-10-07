<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	
	$conexion = new Conexion();;
	$cc = new ControladorCatalogos();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
	<header>
		<h1>Ingreso de Rutas de Transporte Institucional</h1>
	</header>
	<div id="estado"></div>
	
	<form id="nuevoRecorridosInstitucionales" data-rutaAplicacion="serviciosLinea"  >
		<input type="hidden" id="opcion" name="opcion" /> 
		<input type="hidden" id="nombreProvincia" name="nombreProvincia" />
		<input type="hidden" id="nombreCanton" name="nombreCanton" />
		<input type="hidden" id="nombreOficina" name="nombreOficina" /> 
		<input type="hidden" id="identificadorResponsable" name="identificadorResponsable" value="<?php echo $_SESSION['usuario'];?>" />
		
		<fieldset>
			<legend>Transporte Institucional</legend>	
				<div data-linea="1">
					<label>Nombre ruta:</label> 
						<input type="text" id="nombreRuta" name="nombreRuta" maxlength="512" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$"/> 
				</div>
				<div data-linea="2" >
					<label>Provincia: </label>
					<select id="provincia" name="provincia">
						<option value="">Seleccione...</option>
						<?php 
							$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
							foreach ($provincias as $provincia)
								echo '<option value="'.$provincia['codigo'].'">' . $provincia['nombre'] . '</option>';
						?>
					</select>
				</div>
				<div data-linea="2" id="resultadoCantones">
					<label>Cantón: </label> 
					<select id="canton" name="canton">
						<option value="">Seleccione...</option>
					</select>
				</div>
				<div data-linea="3" id="resultadoOficinas">
					<label>Oficina: </label> 
					<select id="oficina" name="oficina">
						<option value="">Seleccione...</option>
					</select>
				</div>
				<div data-linea="3">
					<label>Sector:</label> 
					<select name="sector" id="sector" >
						<option value="" >Seleccione...</option>
						
					</select>
				</div>
				<div data-linea="4">
					<label>Conductor:</label> 
						<input type="text" id="conductor" name="conductor" maxlength="256" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$" /> 
				</div>
				<div data-linea="4">
					<label>Teléfono:</label> 
						<input type="text" id="telefono" name="telefono"  maxlength="16" data-er="^\([0-9]{2}\) [0-9]{4}-[0-9]{4}" data-inputmask="'mask': '(99) 9999-9999'"  /> 
				</div>
				<div data-linea="5">
					<label>Administrador Grupo:</label> 
						<input type="text" id="administradorGrupo" name="administradorGrupo" maxlength="256" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]*$"  /> 
				</div>
				<div data-linea="5">
					<label>Teléfono:</label> 
						<input type="text" id="telefonoAdministrador" name="telefonoAdministrador"  maxlength="16" data-inputmask="'mask': '(99) 9999-9999'"  /> 
				</div>
				<div data-linea="6">
					<label>Capacidad:</label> 
						<input type="text" id="capacidadVehiculo" name="capacidadVehiculo" maxlength="3" onKeyPress='validaSoloNumeros()' data-er="^[0-9]+$" /> 
				</div>
				<div data-linea="6">
					<label>Número Pasajeros:</label> 
						<input type="text" id="numeroPasajeros" name="numeroPasajeros" maxlength="3" onKeyPress='validaSoloNumeros()' data-er="^[0-9]+$"/> 
				</div>
				<div data-linea="7">
					<label>Placa Vehículo:</label> 
						<input type="text" id="placaVehiculo" name="placaVehiculo" maxlength="8" data-inputmask="'mask': 'aaa-9999'" data-er="^([A-Za-z]{3}-\d{4})$"/> 
				</div>
				<div data-linea="8">
					<label>Descripción Vehículo:</label> 
						<input type="text" id="descripcionVehiculo" name="descripcionVehiculo" maxlength="512" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü ,]+$"/> 
				</div>
		</fieldset>

		<button type="submit" id="btnGuardar"  name="btnGuardar" class="guardar" >Guardar</button>
		
	</form>
</body>
<script type="text/javascript">
var listaSectores = <?php echo json_encode($listaSectores);?>;
	$(document).ready(function(){
		distribuirLineas();
		construirValidador();
	});

	$("#provincia").change(function(event){
		if($("#provincia").val()!=0){
		$("#nombreProvincia").val($('#provincia option:selected').text());
		 $('#nuevoRecorridosInstitucionales').attr('data-destino','resultadoCantones');
		 $('#nuevoRecorridosInstitucionales').attr('data-opcion','accionesServiciosLinea');	 
	     $('#opcion').val('listaCantones');		
		 abrir($("#nuevoRecorridosInstitucionales"),event,false); 
		 $("#provincia").removeClass("alertaCombo");
		}
	});

	function validaSoloNumeros() {
		if ((event.keyCode < 48) || (event.keyCode > 57))		 
		event.returnValue = false;
	}

	$("#nuevoRecorridosInstitucionales").submit(function(event){
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!esCampoValido("#descripcionVehiculo")){
			error = true;
			$("#descripcionVehiculo").addClass("alertaCombo");
			$("#estado").html('Por favor revise el formato de la descripción del vehículo').addClass("alerta");
		}

		if(!$.trim($("#descripcionVehiculo").val())){
			error = true;
			$("#descripcionVehiculo").addClass("alertaCombo");
			$("#estado").html('Por favor ingrese la descripción del vehículo').addClass("alerta");	
		}

		if(!esCampoValido("#placaVehiculo")){
			error = true;
			$("#placaVehiculo").addClass("alertaCombo");
			$("#estado").html('Por favor revise el formato de la placa del vehículo').addClass("alerta");	
		}
		
		if(!$.trim($("#placaVehiculo").val())){
			error = true;
			$("#placaVehiculo").addClass("alertaCombo");
			$("#estado").html('Por favor ingrese la placa del vehículo').addClass("alerta");	
		}
		
		if(!$.trim($("#numeroPasajeros").val())){
			error = true;
			$("#numeroPasajeros").addClass("alertaCombo");
			$("#estado").html('Por favor ingrese el número de pasajeros del vehículo').addClass("alerta");	
		}
		
		if(!$.trim($("#capacidadVehiculo").val())){
			error = true;
			$("#capacidadVehiculo").addClass("alertaCombo");
			$("#estado").html('Por favor ingrese la capacidad del vehículo').addClass("alerta");	
		}

		if (!esCampoValido("#administradorGrupo")){
			error = true;
		    $("#administradorGrupo").addClass("alertaCombo");
		    $("#estado").html('Por favor revise el formato del nombre del administrador del grupo del whatsapp').addClass("alerta");
		}

		if (!esCampoValido("#telefono")){
			error = true;
		    $("#telefono").addClass("alertaCombo");
		    $("#estado").html('Por favor revise el formato del teléfono del conductor').addClass("alerta");
		}
		
		if(!$.trim($("#telefono").val())){
			error = true;
			$("#telefono").addClass("alertaCombo");
			$("#estado").html('Por favor ingrese el número de teléfono').addClass("alerta");
		}

		if (!esCampoValido("#conductor")){
			error = true;
		    $("#conductor").addClass("alertaCombo");
		    $("#estado").html('Por favor revise el formato del nombre del conductor').addClass("alerta");
		}
		
		if(!$.trim($("#conductor").val())){
			error = true;
			$("#conductor").addClass("alertaCombo");
			 $("#estado").html('Por favor ingrese nombre del conductor').addClass("alerta");
		}

		if(!$.trim($("#sector").val())){
			error = true;
			$("#sector").addClass("alertaCombo");
			$("#estado").html('Por favor seleccione el sector').addClass("alerta");
		}
			
		if(!$.trim($("#oficina").val())){
			error = true;
			$("#oficina").addClass("alertaCombo");
			$("#estado").html('Por favor seleccione la oficina').addClass("alerta");
		}
			
		if(!$.trim($("#canton").val())){
			error = true;
			$("#canton").addClass("alertaCombo");
			$("#estado").html('Por favor seleccione el cantón').addClass("alerta");
		}

		if(!$.trim($("#provincia").val())){
			error = true;
			$("#provincia").addClass("alertaCombo");
			$("#estado").html('Por favor seleccione la provincia').addClass("alerta");
		}

		if(!esCampoValido("#nombreRuta")){
			error = true;
			$("#nombreRuta").addClass("alertaCombo");
			$("#estado").html('Por favor verifique el formato del nombre de la ruta').addClass("alerta");
		}

		if(!$.trim($("#nombreRuta").val())){
			error = true;
			$("#nombreRuta").addClass("alertaCombo");
			$("#estado").html('Por favor ingrese el nombre de la ruta').addClass("alerta");
		}
		
		if (!error){
			$("#nombreProvincia").val($("#provincia option:selected").text());
			$("#nombreCanton").val($("#canton option:selected").text());
			$("#nombreOficina").val($("#oficina option:selected").text());
			$('#nuevoRecorridosInstitucionales').attr('data-opcion','guardarGARecorridosInstitucionales');    
			$('#nuevoRecorridosInstitucionales').attr('data-destino','detalleItem');
			abrir($("#nuevoRecorridosInstitucionales"),event,false);
		}
	});
</script>