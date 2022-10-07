<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

$conexion = new Conexion();
$cv = new ControladorVehiculos();

//Identificador Usuario Administrador o Apoyo de Transportes
$identificadorUsuarioRegistro = $_SESSION['usuario'];

$res = $cv->abrirVehiculo($conexion, $_POST['id']);
$vehiculo= pg_fetch_assoc($res);

$movilizacion = pg_fetch_assoc($cv->ultimaMovilizacionVehiculo($conexion, $_POST['id']));
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>

<body>
<header>
	<img src='aplicaciones/general/img/encabezado.png'>
	<h1>Liberar vehículo</h1>
</header>

<div id="estado"></div>

<form id="liberarVehiculo" data-rutaAplicacion="transportes" data-opcion="liberarVehiculo" data-accionenexito="ACTUALIZAR">
	<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
	
	<div id="estado"></div>
	<fieldset>
		<legend>Información del vehículo</legend>
		
		<div data-linea="1">
		
		<label>Placa</label>
			<?php echo $_POST['id'];?>
			<input type="hidden" id="placa" name="placa" value="<?php echo $_POST['id'];?>" />
			
		</div><div data-linea="2">
		
		<label>Marca</label> 
			<?php echo pg_fetch_result($res, 0, 'marca');?> 
			
		</div><div data-linea="2">
			
		<label>Modelo</label> 
			<?php echo pg_fetch_result($res, 0, 'modelo');?> 
			
		</div><div data-linea="3">
				
		<label>Tipo</label> 
			<?php echo pg_fetch_result($res, 0, 'tipo');?>  
			
		</div><div data-linea="3">			
	
		<label>Tipo Combustible</label>
			<?php echo pg_fetch_result($res, 0, 'combustible');?> 
		
		</div>	
		
		<div data-linea="4">			
	
		<label>Localización</label>
			<?php echo pg_fetch_result($res, 0, 'localizacion');?> 
		
		</div>
		
		<div data-linea="4">			
	
		<label>Estado</label>
			<?php echo (pg_fetch_result($res, 0, 'estado')==1?'Vehiculo':(pg_fetch_result($res, 0, 'estado')==2?'Mantenimiento':(pg_fetch_result($res, 0, 'estado')==3?'Movilizacion':(pg_fetch_result($res, 0, 'estado')==4?'Siniestro':'Eliminado'))));?>
		
		</div>	
		
		<input type="hidden" id="estadoVehiculo" name="estadoVehiculo" value="<?php echo $vehiculo['estado'];?> " />
	</fieldset>	

	<fieldset>
		<legend>Última Movilización Generada</legend>
		
		<div data-linea="1">		
			<label>Número</label>
				<?php echo $movilizacion['id_movilizacion'];?>
		</div>
		
		<div data-linea="1">		
			<label>Fecha</label>
				<?php echo $movilizacion['fecha_solicitud'];?>
		</div>
		
		<div data-linea="2">		
			<label>Km. Inicial</label>
				<?php echo $movilizacion['kilometraje_inicial'];?>
		</div>
		
		<div data-linea="2">		
			<label>Km. Final</label>
				<?php echo $movilizacion['kilometraje_final'];?>
				<input type="hidden" id="kmFinal" name="kmFinal" value="<?php echo $movilizacion['kilometraje_final'];?> " />
		</div>
		
		<div data-linea="4">			
	
		<label>Estado</label>
			<?php echo ($movilizacion['estado']==1?'Creado':($movilizacion['estado']==2?'Por Imprimir':($movilizacion['estado']==3?'Por Finalizar':($movilizacion['estado']==4?'Finalizado':'Eliminado'))));?>
			<input type="hidden" id="estadoMovilizacion" name="estadoMovilizacion" value="<?php echo $movilizacion['estado'];?> " />
		</div>		
	</fieldset>
	
	<button type="submit" class="guardar" id="boton" name="boton">Liberar vehículo</button> 
	
</form>
</body>

<script type="text/javascript">
var estadoVehiculo= <?php echo json_encode(pg_fetch_result($res, 0, 'estado')); ?>;
var estadoMovilizacion= <?php echo json_encode($movilizacion['estado']); ?>;

	$(document).ready(function(){
		distribuirLineas();

		if(estadoVehiculo != 3){
			alert("Solamente se pueden liberar vehículos que superaron los 5000 kms de recorrido. Por favor solicitar al usuario elimine la orden activa para liberar el vehículo");
			$("#boton").hide();
		}

		if(estadoMovilizacion != 4){
			alert("La última orden de movilización no se encuentra finalizada, por favor procedan con la finalización de la misma para habilitar el vehículo");
			$("#boton").hide();
		}
	});
				
	$("#liberarVehiculo").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));
	});

</script>
</html>