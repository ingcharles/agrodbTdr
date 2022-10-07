<?php

require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorUsuarios.php';
require_once '../../../clases/ControladorAplicaciones.php';
require_once '../../../clases/ControladorRegistroOperador.php';

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body style="background:url('img/back.png') repeat;
	text-align: center;">
	<?php

	$usuario = $_GET['U'];
	$clave = $_GET['C'];
	
	$conexion = new Conexion();
	$cu = new ControladorUsuarios();
	$ca = new ControladorAplicaciones();
	$cr = new ControladorRegistroOperador();
	$valor = false;
	
	$qAplicacionOperadores = $ca->obtenerIdAplicacion($conexion,'PRG_REGISTROOPER');
	$aplicacionOperador = pg_fetch_result($qAplicacionOperadores, 0, 'id_aplicacion');
	
	
	
	//$qAplicacionTrazabilidad = $ca->obtenerIdAplicacion($conexion,'PRG_TRAZABILIDAD');
	//$aplicacionTrazabilidad = pg_fetch_result($qAplicacionTrazabilidad, 0, 'id_aplicacion');
	
	$qRazonSocial = $cr->obtenerDatosOperador($conexion, $usuario);
	$razonSocial = pg_fetch_result($qRazonSocial, 0, 'razon_social');
	$nombre = pg_fetch_result($qRazonSocial, 0, 'nombre_representante');
	$apellido = pg_fetch_result($qRazonSocial, 0, 'apellido_representante');
	
	$cu ->activarCuenta($conexion, $usuario, $clave);
	
	$aplicacionOperadorRegistro = $ca -> obtenerAplicacionPerfil($conexion, $aplicacionOperador, $usuario);
	//$aplicacionTrazabilidadRegistro = $ca -> obtenerAplicacionPerfil($conexion, $aplicacionOperador, $usuario);
	
	if (pg_num_rows($aplicacionOperadorRegistro) == 0){
		$valor = true;
		$ca->guardarAplicacionPerfil($conexion, $aplicacionOperador,$usuario, 0, 'notificaciones');
	}
	
	/*if (pg_num_rows($aplicacionTrazabilidadRegistro) == 0){
		$valor = true;
		$ca->guardarAplicacionPerfil($conexion, $aplicacionTrazabilidad,$usuario, 0, 'notificaciones');
	}*/
	
	if($valor){
				
		echo '
		<p>
			<a href="../../../index.php">
				<img src="img/email2.png" width="500" height="609">
			</a>
		</p>';
		
	}else{

		echo '
		<p>
			<p style="color:white;">Este link ya ha sido utilizado para activar la cuenta del operador.</p>
			<a href="../../../index.php">
				<img src="img/email2.png" width="500" height="609">
			</a>
		</p>';
	}
			
	
	//<p style="position: absolute; top: 220px; left: 580px; text-align: center;">'.$razonSocial.'<br/>'.$nombre.' '.$apellido.'</p>'
	?>
</body>

</html>
