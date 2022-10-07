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
		
		$mantenimientos = explode(",",$_POST['elementos']);
		
		$res = $cv->abrirMantenimiento($conexion, $mantenimientos[0]);
		$mantenimiento = pg_fetch_assoc($res);
	
		if($mantenimiento['orden_trabajo'] != ''){
			$ordenTrabajo = 1;
		}else{
			$ordenTrabajo = 0;
		}
	?>
	
 

<form id="notificarHabilitarVehiculo" data-rutaAplicacion="transportes" data-opcion="habilitarVehiculo" data-accionEnExito="ACTUALIZAR" >
		<fieldset id='datosVehiculo'>
			<legend>Información del vehículo</legend>

			<div data-linea="1">

				<label>Vehículo: </label>
				<?php echo $mantenimiento['marca'].' - '.$mantenimiento['modelo'].' - '.$mantenimiento['tipo'].'';?>

			</div>
			<div data-linea="2">

				<label>Placa: </label>
				<?php echo $mantenimiento['placa'];?>

			</div>
			<div data-linea="2">

				<label>Oficina: </label>
				<?php echo $mantenimiento['localizacion'];?>

			</div>
			<div data-linea="4">

				<label>Fecha solicitud: </label>
				<?php echo date('j/n/Y (G:i)',strtotime($mantenimiento['fecha_solicitud']));?>

			</div>
			<div data-linea="5">

				<label>Tipo: </label>
				<?php echo $mantenimiento['tipo_mantenimiento'];?>

			</div>
			<div data-linea="6">

				<label>Motivo: </label>
				<?php echo $mantenimiento['motivo'];?>

			</div>
		</fieldset>

		<input type="hidden" name="id" value="<?php echo $mantenimiento['id_mantenimiento'];?>" /> 
		<input type="hidden" name="placa" value="<?php echo $mantenimiento['placa'];?>" /> 
		<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
		<input type="hidden" name="km_Actual" id="km_Actual" value="<?php echo $mantenimiento['kilometraje'];?>" />
						
		<fieldset id="formHabilitarVehiculo">

			<legend>Habilitación de Vehículo con Orden de Trabajo</legend>

				<div data-linea="1">
					<label id="lOrdenTrabajo">Orden de trabajo</label> 
						<input type="text" id="ordenTrabajo" name="ordenTrabajo" /> 
				</div>
				
				<div data-linea="2">	
					<label id="lKilometrajeFinal">Kilometraje inicial</label> 
						<input type="number" name="kilometrajeInicial" id="kilometrajeInicial" readonly="readonly" value="<?php echo $mantenimiento['kilometraje'];?>" />
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
var vehiculo= <?php echo json_encode($mantenimientos); ?>;
var estadoMantenimiento= <?php echo json_encode($mantenimiento['estado']); ?>;
var ordenTrabajo= <?php echo json_encode($ordenTrabajo); ?>;

$("#notificarHabilitarVehiculo").submit(function(event){

	event.preventDefault();
	
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($("#ordenTrabajo").val()==""){
		error = true;
		$("#ordenTrabajo").addClass("alertaCombo");
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
		$("#detalleItem").html('<div class="mensajeInicial">Seleccione un mantenimiento para continuar.</div>');
	}else{
		if(estadoMantenimiento != 2){
			$("#detalleItem").html('<div class="mensajeInicial">Seleccione una orden en estado Por Liquidar para continuar.</div>');
		}else{
			if(ordenTrabajo == 1){
				$("#detalleItem").html('<div class="mensajeInicial">No puede volver a habilitar el vehículo de esta orden.</div>');
			}
		}
	}
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
