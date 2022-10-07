<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';

	
	$conexion = new Conexion();
	$cro = new ControladorRegistroOperador();
	$cc = new ControladorCatalogos();
	$cac = new ControladorAdministrarCatalogos();
	
	$datos=explode('@', $_POST['id']);
	$idSitio = $datos[0];
	$idOperacion = $datos[1];
	
	$qUnidadMedida = $cc->listarUnidadesMedida($conexion);
	
	
	$qTipoTanque = $cac -> listarItemsPorCodigo($conexion, 'COD-TANQU-IA', '1');
	
	$qSitio = $cro -> abrirSitio($conexion, $idSitio);
	$sitio = pg_fetch_result($qSitio, 0, 'nombre_lugar');
	
	$idArea = pg_fetch_result($cro->obtenerDatosAreaXIdOperacion($conexion, $idOperacion), 0, 'id_area');
	$nombreArea = pg_fetch_result($cro->obtenerDatosAreaXIdOperacion($conexion, $idOperacion), 0, 'nombre_area');
	$qOperacion = $cro->abrirOperacionXid($conexion, $idOperacion);
	$operacion = pg_fetch_assoc($qOperacion);
	$qDatosVehiculo = $cro->obtenerDatosVehiculoXIdOperadorTipoOperacionPorEstado($conexion, $operacion['id_operador_tipo_operacion'], 'activo');
	$datosVehiculo = pg_fetch_assoc($qDatosVehiculo);
	$placa = $datosVehiculo['placa_vehiculo'];

	$tipoTanqueVehiculo = $datosVehiculo['id_tipo_tanque_vehiculo'];
	$marcaVehiculo = $datosVehiculo['nombre_marca_vehiculo'];
	$modeloVehiculo = $datosVehiculo['nombre_modelo_vehiculo'];
	$claseVehiculo = $datosVehiculo['nombre_clase_vehiculo'];
	$colorVehiculo = $datosVehiculo['nombre_color_vehiculo'];
	$tipoVehiculo = $datosVehiculo['nombre_tipo_vehiculo'];
	$anioVehiculo = $datosVehiculo['anio_vehiculo'];
	$capacidadInstaladaVehiculo = $datosVehiculo['capacidad_vehiculo'];
	$unidadMedidaVehiculo = $datosVehiculo['codigo_unidad_medida'];
	$horaInicio = $datosVehiculo['hora_inicio_recoleccion'];
	$horaFin = $datosVehiculo['hora_fin_recoleccion'];

	// $qModelo = $cac -> listarItemsPorCodigo($conexion, 'COD-MODEL-IA', '1');
	$qTipo = $cac -> listarItemsPorCodigo($conexion, 'COD-TIPOX-IA', '1');
?>

<header>
	<h1>Declarar Datos del Vehículo</h1>
</header>

<div id="estado"></div>

<form id='declararDatosVehiculo' data-rutaAplicacion='registroOperador' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" class="idArea" name="idArea" value="<?php echo $idArea;?>" />
	<input type="hidden" class="idOperacion" name="idOperacion" value="<?php echo $idOperacion;?>" />
	<input type="hidden" name="opcion" id="opcion" />
	<fieldset>
		<legend>Datos del Vehículo</legend>		
		<div data-linea="1">			
			<label>Sitio: </label><?php echo $sitio; ?>
		</div>
		<div data-linea="1">			
			<label>Área: </label><?php echo $nombreArea; ?>
		</div>
		<hr/>
		<div data-linea="2">			
		<label>*Placa: </label><input type="text" id="placa" name="placa" placeholder="Ej: AAA-0000" data-er="[A-Za-z]{3}-[0-9]{3,4}" data-inputmask="'mask': 'aaa-9999'"  value="<?php echo $placa; ?>"/>
		</div>
		<div data-linea="2">				
			<label>Año: </label><input	type="text" id="anio" name="anio" readonly  value="<?php echo $anioVehiculo; ?>"/>
		</div>	
		<div data-linea="3">				
			<label>Marca: </label><input	type="text" id="marca" name="marca" readonly  value="<?php echo $marcaVehiculo; ?>"/>
		</div>
		<div data-linea="3">				
			<label>Modelo: </label><input	type="text" id="modelo" name="modelo" readonly  value="<?php echo $modeloVehiculo; ?>"/>
		</div>
		<div data-linea="4">				
			<label>Color: </label><input	type="text" id="color" name="color" readonly  value="<?php echo $colorVehiculo; ?>"/>
		</div>
		<div data-linea="4">				
			<label>Tipo: </label><input	type="text" id="tipo" name="tipo" readonly  value="<?php echo $tipoVehiculo; ?>"/>
		</div>
		<div data-linea="5">				
			<label>Clase: </label><input	type="text" id="clase" name="clase" readonly value="<?php echo $claseVehiculo; ?>" />
		</div>
		
		
		<div data-linea="6">			
			<label for="tipoTanque">*Tipo de tanque: </label>
            <select id="tipoTanque" name="tipoTanque">
            <option value="">Seleccione...</option>
                <?php
                    while ($tipoTanque = pg_fetch_assoc($qTipoTanque)) {
                        echo '<option value="' . $tipoTanque['id_item'] . '">' . $tipoTanque['nombre'] . '</option>';
                    }
                ?>
            </select>
		</div>
		<div data-linea="7">				
			<label>*Capacidad instalada: </label>
			<input	type="text" id="capacidadInstalada" name="capacidadInstalada" value="<?php echo $capacidadInstaladaVehiculo; ?>" />
		</div>
		<div data-linea="7">
			<label for="unidadMedida">*Unidad: </label>			
            <select id="unidadMedida" name="unidadMedida">
            <option value="">Seleccione...</option>
                <?php
                    while ($unidadMedida = pg_fetch_assoc($qUnidadMedida)) {
                        echo '<option value="' . $unidadMedida['codigo'] . '">' . $unidadMedida['nombre'] . '</option>';
                    }
                ?>
            </select>
		</div>	
		<div data-linea="8">
			<label>*Recolección - Hora Inicio:</label> <input type="text" id="horaInicioRecoleccion" name="horaInicioRecoleccion" placeholder="06:30" data-inputmask="'mask': '99:99'" value="<?php echo $horaInicio; ?>" />
		</div>	
		<div data-linea="8">
			<label>*Recolección - Hora Fin:</label> <input type="text" id="horaFinRecoleccion" name="horaFinRecoleccion" placeholder="08:30" data-inputmask="'mask': '99:99'" value="<?php echo $horaFin?>" ; />
		</div>
	</fieldset>
	
	<button type="submit" class="guardar">Guardar</button>
	
</form>

<div id="cargarMensajeTemporal"></div>

<script type="text/javascript">

$(document).ready(function(){
	distribuirLineas();
	construirValidador();
	cargarValorDefecto("marca","<?php echo $marcaVehiculo; ?>");
	cargarValorDefecto("modelo","<?php echo $modeloVehiculo; ?>");
	cargarValorDefecto("clase","<?php echo $claseVehiculo; ?>");
	cargarValorDefecto("color","<?php echo $colorVehiculo; ?>");
	cargarValorDefecto("tipo","<?php echo $tipoVehiculo; ?>");
	cargarValorDefecto("tipoTanque","<?php echo $tipoTanqueVehiculo; ?>");	
	cargarValorDefecto("unidadMedida","<?php echo ($unidadMedidaVehiculo) != "" ? $unidadMedidaVehiculo : "L"; ?>");
	$("#capacidadInstalada").numeric();


});

function esCampoValido(elemento){
	var patron = new RegExp($(elemento).attr("data-er"),"g");
	return patron.test($(elemento).val());
}
                


$("#marca").change(function(event){
	event.stopImmediatePropagation();
	if( $("#marca").val() != ""){
		 $('#declararDatosVehiculo').attr('data-opcion','combosDatosVehiculo');
		 $('#declararDatosVehiculo').attr('data-destino','resultadoMarca');
		 $('#opcion').val('marca');	
		 abrir($("#declararDatosVehiculo"),event,false);	
	}	
});


$("#horaInicioRecoleccion").change(function(){

	$("#horaInicioRecoleccion").removeClass('alertaCombo');
		
		var horaNueva = $("#horaInicioRecoleccion").val().replace(/\_/g, "0");
		$("#horaInicioRecoleccion").val(horaNueva);
		
		var hora = $("#horaInicioRecoleccion").val().substring(0,2);
		var minuto = $("#horaInicioRecoleccion").val().substring(3,5);
		
		if(parseInt(hora)>=1 && parseInt(hora)<25){
			if(parseInt(minuto)>=0 && parseInt(minuto)<60){
				if(parseInt(hora)==24){
					minuto = '00';
					$("#horaInicioRecoleccion").val('24:00');
				}			

			}else{
				$("#horaInicioRecoleccion").addClass('alertaCombo');
				$("#horaInicioRecoleccion").val('');
				$("#estado").html("Los minutos ingresados están incorrectos, por favor actualice la información").addClass('alerta');
			}
		}else{
			$("#horaInicioRecoleccion").addClass('alertaCombo');
			$("#horaInicioRecoleccion").val('');
			$("#estado").html("La hora ingresada está fuera de rango").addClass('alerta');
		}

});

$("#horaFinRecoleccion").change(function(){

	$("#horaFinRecoleccion").removeClass('alertaCombo');
		
		var horaNueva = $("#horaFinRecoleccion").val().replace(/\_/g, "0");
		$("#horaFinRecoleccion").val(horaNueva);
		
		var hora = $("#horaFinRecoleccion").val().substring(0,2);
		var minuto = $("#horaFinRecoleccion").val().substring(3,5);
		
		if(parseInt(hora)>=1 && parseInt(hora)<25){
			if(parseInt(minuto)>=0 && parseInt(minuto)<60){
				if(parseInt(hora)==24){
					minuto = '00';
					$("#horaFinRecoleccion").val('24:00');
				}			

			}else{
				$("#horaFinRecoleccion").addClass('alertaCombo');
				$("#horaFinRecoleccion").val('');
				$("#estado").html("Los minutos ingresados están incorrectos, por favor actualice la información").addClass('alerta');
			}
		}else{
			$("#horaFinRecoleccion").addClass('alertaCombo');
			$("#horaFinRecoleccion").val('');
			$("#estado").html("La hora ingresada está fuera de rango").addClass('alerta');
		}

});

$("#declararDatosVehiculo").submit(function(event){
	
	event.preventDefault();
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	
	if($("#capacidadInstalada").val() == "" || $("#capacidadInstalada").val() == 0){	
		error = true;		
		$("#capacidadInstalada").addClass("alertaCombo");
	}

	

	if($("#placa").val() == ""){	
		error = true;		
		$("#placa").addClass("alertaCombo");
	}

	if($("#tipoTanque").val() == ""){	
		error = true;		
		$("#tipoTanque").addClass("alertaCombo");
	}

	

	if($("#capacidadInstalada").val() == "" || $("#capacidadInstalada").val() == 0){	
		error = true;		
		$("#capacidadInstalada").addClass("alertaCombo");
	}

	if($("#unidadMedida").val() == ""){	
		error = true;		
		$("#unidadMedida").addClass("alertaCombo");
	}

	if($("#numeroProveedores").val() == "" || $("#numeroProveedores").val() == 0){	
		error = true;		
		$("#numeroProveedores").addClass("alertaCombo");
	}
	
	if($("#horaInicioRecoleccion").val() == "" || $("#horaInicioRecoleccion").val() == 0){	
		error = true;		
		$("#horaInicioRecoleccion").addClass("alertaCombo");
	}

	if($("#horaFinRecoleccion").val() == "" || $("#horaFinRecoleccion").val() == 0){	
		error = true;		
		$("#horaFinRecoleccion").addClass("alertaCombo");
	}

	error = controlarHoras($("#horaInicioRecoleccion").val(),$("#horaFinRecoleccion").val());

	if (!error){
		$('#declararDatosVehiculo').attr('data-opcion','guardarDeclararDatosVehiculo');
		ejecutarJson(this);
		$(".guardar").prop('disabled',false);
		
	}else{
		
		$("#estado").html("Por favor verifique la información ingresada.").addClass("alerta");
		
	}
});

$("#placa").blur(function(event){

event.stopImmediatePropagation();
$(".alertaCombo").removeClass("alertaCombo");
mostrarMensaje('', "FALLO");
var error = true;

if(!$.trim($("#placa").val())  ){
	error = false;
//    $('#servicio').val('');
}
if (error){
	var placa = $("#placa").val().replace('-','');
	placa = placa.toUpperCase();
	 $("#cargarMensajeTemporal").html("<div id='cargando'>Cargando...</div>");
	  $.post("aplicaciones/general/consultaWebServices.php", 
			  {
				  clasificacion:"AntMatriculaLicencia",
				  numero:placa
			  }, function (data) {
				  $("#cargarMensajeTemporal").html("");
					  if (data.estado === 'exito') {
						  $("#servicio").val(data.valores.tipo_Servicio);
						  llenarCamposVehiculo(data);
					  } else {
						mostrarMensaje("No se encontraron datos para la PLACA..!!", "FALLO");
						limpiarCamposVehiculo();
						$("#placa").addClass("alertaCombo");
						$('#placa').attr('placeholder',$("#placa").val());
						$("#placa").val('');
					  }
		  }, 'json');
		}
	});
	
	function llenarCamposVehiculo(datos){
		$('#marca').val(datos.valores.marca);
		$('#modelo').val(datos.valores.modelo);
		$('#color').val(datos.valores.color);
		$('#tipo').val(datos.valores.tipo);
		$('#clase').val(datos.valores.clase);
		$('#anio').val(datos.valores.anio);
	}

	function limpiarCamposVehiculo(){
		$('#placa').val('');
		$('#marca').val('');
		$('#modelo').val('');
		$('#color').val('');
		$('#tipo').val('');
		$('#clase').val('');
		$('#anio').val('');
		$('#servicio').val('');
	}
	
	$("#placa").click(function(){

	if($('#placa').val()==''){
		limpiarCamposVehiculo();
	}else{
		$('#marca').val('');
		$('#modelo').val('');
		$('#color').val('');
		$('#tipo').val('');
		$('#clase').val('');
		$('#anio').val('');
		$('#servicio').val('');
	}
}); 

function controlarHoras(horaInicio, horaFin){
	if(horaFin > horaInicio){
		error=false;
	}else if($("#horaFinRecoleccion").val()!=''){
	 $("#horaFinRecoleccion").addClass('alertaCombo');
	 $("#estado").html("Los minutos ingresados están incorrectos, por favor actualice la información").addClass('alerta'); 
	 alert("La hora fin no puede ser menor o igual que la de inicio.");
	 error = true;
	}
	return error;
}
	
</script>