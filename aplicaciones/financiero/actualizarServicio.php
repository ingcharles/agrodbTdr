<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCertificados.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	
	$datos = array('idServicio' => htmlspecialchars ($_POST['id_servicio'],ENT_NOQUOTES,'UTF-8'),
	               'concepto' => htmlspecialchars ($_POST['tipo_certificado'],ENT_NOQUOTES,'UTF-8'),
		       'unidad' => htmlspecialchars ($_POST['unidad'],ENT_NOQUOTES,'UTF-8'),
		       'valor' => htmlspecialchars ($_POST['valor'],ENT_NOQUOTES,'UTF-8'),
		       'area' =>  htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8'),
		       'iva' =>  htmlspecialchars ($_POST['iva'],ENT_NOQUOTES,'UTF-8'));
	
	try {
		$conexion = new Conexion();
		$cc = new ControladorCertificados();
				
		$cc->actualizarValorServicio($conexion,$datos['idServicio'],$datos['concepto'],$datos['unidad'],$datos['valor'],$datos['area'],$datos['iva']);		
		//$cc->actualizarServicio($conexion, $datos['idCertificado'], $datos['idArea'], $datos['tipoCertificado']);
		
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
		
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
