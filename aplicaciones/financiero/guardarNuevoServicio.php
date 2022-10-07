<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorFinanciero.php';
require_once '../../clases/ControladorCertificados.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	$area = htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8');
	$concepto = htmlspecialchars ($_POST['nombreDocumento'],ENT_NOQUOTES,'UTF-8');
	
	try {
		$conexion = new Conexion();
		$cf = new ControladorFinanciero();
		$cc = new ControladorCertificados();
		
		$idPadre = pg_fetch_result($cc->obtenerIdServicioXarea($conexion, $area, 'activo'), 0, 'id_servicio');
		
		
		$res = $cf -> generaNumeroDocumento($conexion,$idPadre,2,$area);
		$documento = pg_fetch_assoc($res);
		$tmp= explode(".", $documento['numero']);
		$incremento = end($tmp)+1;
		$nuevoCodigo =  $tmp[0].'.'.str_pad($incremento, 2, "0", STR_PAD_LEFT);
		
		$idServicio = pg_fetch_result($cf-> guardarNuevoDocumento($conexion,$concepto,$area,$nuevoCodigo,$idPadre),0,'id_servicio');
		
		$mensaje['estado'] = 'exito';
		//$mensaje['mensaje'] = $documentoNumero;
		$mensaje['mensaje'] = $cf->imprimirLineaDocumento($idServicio, $nuevoCodigo, $concepto,'financiero',$area);

		$conexion->desconectar();
		echo json_encode($mensaje);
	} catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>