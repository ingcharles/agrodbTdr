<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

$conexion = new Conexion();
$cv = new controladorVehiculos();

$identificadorUsuarioRegistro = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<h1>Confirmar habilitación de vehículo</h1>
</header>

<div id="estado"></div>

	<p>El siguiente<b> vehículo</b> va a ser habilitado: </p>
	
	<?php
		
		$siniestros = explode(",",$_POST['elementos']);
		
		$res = $cv->abrirSiniestro($conexion, $siniestros[0]);
		$siniestro = pg_fetch_assoc($res);
	
		if($siniestro['fecha_habilitacion_vehiculo'] != ''){
			$habilitado = 1;
		}else{
			$habilitado = 0;
		}
	?>
	
 

<form id="notificarHabilitarVehiculo" data-rutaAplicacion="transportes" data-opcion="habilitarVehiculoSiniestro" data-accionEnExito="ACTUALIZAR" >
		<fieldset id='datosVehiculo'>
			<legend>Información del vehículo</legend>

			<div data-linea="0">

				<label>ID: </label>
				<?php echo $siniestro['id_siniestro'];?>

			</div>
			
			<div data-linea="1">

				<label>Vehículo: </label>
				<?php echo $siniestro['marca'].' - '.$siniestro['modelo'].' - '.$siniestro['tipo'].'';?>

			</div>
			<div data-linea="2">

				<label>Placa: </label>
				<?php echo $siniestro['placa'];?>

			</div>
			<div data-linea="2">

				<label>Oficina: </label>
				<?php echo $siniestro['localizacion'];?>

			</div>
			<div data-linea="4">

				<label>Fecha siniestro: </label>
				<?php echo date('j/n/Y (G:i)',strtotime($siniestro['fecha_siniestro']));?>

			</div>
			<div data-linea="5">

				<label>Tipo: </label>
				<?php echo $siniestro['tipo_siniestro'];?>

			</div>
			<div data-linea="6">

				<label>Motivo: </label>
				<?php echo $siniestro['observacion_siniestro'];?>

			</div>
		</fieldset>

		<input type="hidden" name="id" value="<?php echo $siniestro['id_siniestro'];?>" /> 
		<input type="hidden" name="placa" value="<?php echo $siniestro['placa'];?>" /> 
		<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
		<input type="hidden" name="km_Actual" id="km_Actual" value="<?php echo $siniestro['kilometraje_inicial'];?>" />
						
		<fieldset id="formHabilitarVehiculo">

			<legend>Habilitación de Vehículo por salida de Taller Mecánico</legend>

				<div data-linea="1">
					<label id="lSalidaTaller">Fecha salida del taller</label> 
						<input type="text" id="salidaTaller" name="salidaTaller" /> 
				</div>
				
				<div data-linea="2">	
					<label id="lKilometrajeFinal">Kilometraje inicial</label> 
						<input type="number" name="kilometrajeInicial" id="kilometrajeInicial" readonly="readonly" value="<?php echo $siniestro['kilometraje_inicial'];?>" />
				</div>
				<div data-linea="2">
					<label id="lKilometrajeFinal">Kilometraje final</label> 
						<input type="number" step="1" name="kilometrajeFinal" id="kilometrajeFinal" required="required" placeholder="Ej: 12345" />
				</div>
				
				<div data-linea="3" id="razonIncrementoKm">	
					<label>Razón incremento kilometraje</label>
						<input type="text" name="razonKilometraje" id="razonKilometraje" /> 
				</div>
		</fieldset>

		<button type="submit" id="botonHabilitar" class="guardar">Guardar habilitación vehículo</button>
	
</form>

</body>

<script type="text/javascript">
var vehiculo= <?php echo json_encode($siniestros); ?>;
var estadoSiniestro= <?php echo json_encode($siniestro['estado']); ?>;
var fechaSalida= <?php echo json_encode($habilitado); ?>;

$("#notificarHabilitarVehiculo").submit(function(event){

	event.preventDefault();
	
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($("#salidaTaller").val()==""){
		error = true;
		$("#salidaTaller").addClass("alertaCombo");
	}

	if($("#kilometrajeFinal").val()==""){
		error = true;
		$("#kilometrajeFinal").addClass("alertaCombo");
	}

	if(!error ){
		if($("#kilometrajeFinal").val() > Number($("#km_Actual").val())){
			ejecutarJson($(this));
		}else{
			$("#estado").html("El kilometraje ingresado en inferior al actual, por favor verificar.").addClass("alerta");
		}
	}
});


$(document).ready(function(){

	distribuirLineas();
	$("#razonIncrementoKm").hide();

	if(vehiculo == ''){
		$("#detalleItem").html('<div class="mensajeInicial">Seleccione un siniestro para continuar.</div>');
	}else{
		if(estadoSiniestro != 2){
			$("#detalleItem").html('<div class="mensajeInicial">Seleccione una orden en estado <b>Por finalizar</b> para continuar.</div>');
		}else{
			if(fechaSalida == 1){
				$("#detalleItem").html('<div class="mensajeInicial">No puede volver a habilitar el vehículo de esta orden.</div>');
			}
		}
	}

	$( "#salidaTaller" ).datepicker({
	      changeMonth: true,
	      changeYear: true
	 });
});

$("#kilometrajeFinal").change(function(event){
	if((Number($("#kilometrajeFinal").val())-Number($("#kilometrajeInicial").val())) >= Number(1000)){
		$("#razonIncrementoKm").show();
		$("#razonKilometraje").attr('required','required');
		$("#estado").html("El kilometraje ingresado supera los 1000 kms de recorrido, por favor verifique el valor o ingrese el motivo del mismo.").addClass("alerta");
	}else{
		$("#razonIncrementoKm").hide();
		$("#razonKilometraje").removeAttr("required");
	}
});
	
</script>

</html>
