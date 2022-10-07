<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

$conexion = new Conexion();
$cv = new ControladorVehiculos();
$res = $cv->abrirVehiculo($conexion, $_POST['id']);
$vehiculo= pg_fetch_assoc($res);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
	<link rel='stylesheet' href='/estilos/estiloapp.css' >
</head>
<body>
<fieldset id="fotos">
		<legend>Fotos del vehículo</legend>	
		
		<table id="fotosVehiculo">
			<tr>
				<td>
					
					<iframe name="if_frontal" class="ventanaEmergenteVehiculo" src="aplicaciones/general/fotoInicial.php?titulo=Frontal&img=<?php echo ($vehiculo['imagen_frontal']==''?'../../aplicaciones/transportes/img/frontal.jpg':'../../'.$vehiculo['imagen_frontal']);?>"></iframe>
					<form id="f_frontal" action="aplicaciones/transportes/subirArchivo.php" method="post" enctype="multipart/form-data" target="if_frontal">
						<input type="file" name="archivo" id="archivo" accept="image/jpeg"/>
						<input name="idVehiculo" value="<?php echo $vehiculo['id_vehiculo'];?>" type="hidden"/> 
						<input name="placa" value="<?php echo $vehiculo['placa'];?>" type="hidden"/> 
						<button type="submit" id="b_frontal" name="boton" value="Frontal" class="adjunto" disabled="disabled">Cambiar imagen</button>	
					</form>
				</td>
			</tr>
			<tr>
				<td>	
					<iframe name="if_trasera" class="ventanaEmergenteVehiculo" src="aplicaciones/general/fotoInicial.php?titulo=Posterior&img=<?php echo ($vehiculo['imagen_trasera']==''?'../../aplicaciones/transportes/img/posterior.jpg':'../../'.$vehiculo['imagen_trasera']);?>"></iframe>
					<form id="f_trasera" action="aplicaciones/transportes/subirArchivo.php" method="post" enctype="multipart/form-data" target="if_trasera">
						<input type="file" name="archivo" accept="image/jpeg"/>
						<input name="idVehiculo" value="<?php echo $vehiculo['id_vehiculo'];?>" type="hidden"/>
						<input name="placa" value="<?php echo $vehiculo['placa'];?>" type="hidden"/> 
						<button type="submit" name="boton" value="Posterior" class="adjunto" disabled="disabled">Cambiar imagen</button>	
					</form>
				</td>
			</tr>
			<tr>
				<td>
					<iframe name="if_derecha" class="ventanaEmergenteVehiculo" src="aplicaciones/general/fotoInicial.php?titulo=Derecha&img=<?php echo ($vehiculo['imagen_derecha']==''?'../../aplicaciones/transportes/img/derecha.jpg':'../../'.$vehiculo['imagen_derecha']);?>"></iframe>
					<form id="f_derecha" action="aplicaciones/transportes/subirArchivo.php" method="post" enctype="multipart/form-data" target="if_derecha" >
						<input type="file" name="archivo" accept="image/jpeg"/>
						<input name="idVehiculo" value="<?php echo $vehiculo['id_vehiculo'];?>" type="hidden"/>
						<input name="placa" value="<?php echo $vehiculo['placa'];?>" type="hidden"/> 
						<button type="submit" name="boton" value="Derecha" class="adjunto" disabled="disabled">Cambiar imagen</button>	
					</form>
				</td>
			</tr>
			<tr>
				<td>
					<iframe name="if_izquierda" class="ventanaEmergenteVehiculo" src="aplicaciones/general/fotoInicial.php?titulo=Izquierda&img=<?php echo ($vehiculo['imagen_izquierda']==''?'../../aplicaciones/transportes/img/izquierda.jpg':'../../'.$vehiculo['imagen_izquierda']);?>"></iframe>
					<form id="f_izquierda" action="aplicaciones/transportes/subirArchivo.php" method="post" enctype="multipart/form-data" target="if_izquierda">
						<input type="file" name="archivo" accept="image/jpeg"/>
						<input name="idVehiculo" value="<?php echo $vehiculo['id_vehiculo'];?>" type="hidden"/>
						<input name="placa" value="<?php echo $vehiculo['placa'];?>" type="hidden"/> 
						<button type="submit" name="boton" value="Izquierda" class="adjunto" disabled="disabled">Cambiar imagen</button>	
					</form>
				</td>
			</tr>		
		</table>	
</fieldset>
</body>
<script type="text/javascript">

	$("#f_frontal input").click(function(){
		$("#f_frontal button").removeAttr("disabled");
	});

	$("#f_trasera").click(function(){
		$("#f_trasera button").removeAttr("disabled");
	});
	
	$("#f_derecha").click(function(){
		$("#f_derecha button").removeAttr("disabled");
	});
	
	$("#f_izquierda").click(function(){
		$("#f_izquierda button").removeAttr("disabled");
	});
</script>
</html>
