<?php
require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorUsuarios.php';

$conexion = new Conexion();
$cu = new ControladorUsuarios();

$codigo = htmlspecialchars ($_POST['codigo'],ENT_NOQUOTES,'UTF-8');
$claveUno = htmlspecialchars ($_POST['claveUno'],ENT_NOQUOTES,'UTF-8');
$claveDos = htmlspecialchars ($_POST['claveDos'],ENT_NOQUOTES,'UTF-8');
$identificador = htmlspecialchars ($_POST['identificador'],ENT_NOQUOTES,'UTF-8');

$resultadoCodigo = $cu->verificarCodigoTemporal($conexion, $identificador, $codigo);

if(pg_num_rows($resultadoCodigo)==0){
	
	$intento = pg_fetch_assoc($cu->actualizarIntentosCambioClave($conexion, $identificador));
	
	$diferencia = 3-$intento['intento'];
	
	echo '<div class="mensajeErrorClave">El código ingresado es incorrecto</div>';
	
	
	if($intento['intento'] == 3){
		$cu->desactivarCuentaCifrado($conexion, $identificador);
		echo '<script type="text/javascript">$("button").hide();</script>';
		echo '<script type="text/javascript">$("input").attr(disabled,disabled);</script>';
		echo '<div class="mensajeErrorClave">Su cuenta ha sido bloqueada, favor comunicarse al número 23960100 ext. 3203, 3204, 3205.</div>';
	}else{
		echo '<div class="mensajeErrorClave">Recuerde, usted tiene '.$diferencia.' de 3 intentos.</div>';
	}
	
}else{
	$ipUsuario = $cu->obtenerIPUsuario();
	$cu->resetearClaveUsuario($conexion, $identificador, $claveUno, $codigo, $ipUsuario);
	echo "<script type='text/javascript'>alert('Su contraseña ha sido cambiada correctamente.'); $(location).attr('href','http://181.112.155.173/agrodbPrueba');</script>";//https://guia.agrocalidad.gob.ec
}


?>
