<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCertificadoCalidad.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	//Datos generales
	
	$inspector = htmlspecialchars ($_POST['inspector'],ENT_NOQUOTES,'UTF-8');
	$idSolicitud = htmlspecialchars ($_POST['idSolicitud'],ENT_NOQUOTES,'UTF-8');	
	$fechaAnalisis = htmlspecialchars ($_POST['fechaAnalisis'],ENT_NOQUOTES,'UTF-8');	
	$vapor = htmlspecialchars ($_POST['vapor'],ENT_NOQUOTES,'UTF-8');		
	$muestraInspector = htmlspecialchars ($_POST['muestraInspector'],ENT_NOQUOTES,'UTF-8');	
	$contraMuestra = htmlspecialchars ($_POST['contraMuestra'],ENT_NOQUOTES,'UTF-8');	
	$tipoInspeccion = htmlspecialchars ($_POST['tipoInspeccion'],ENT_NOQUOTES,'UTF-8');	
	$tipoCacaoVerificado = htmlspecialchars ($_POST['nTipoCacaoVerificado'],ENT_NOQUOTES,'UTF-8');	
	$higiene = htmlspecialchars ($_POST['higiene'],ENT_NOQUOTES,'UTF-8');	
	$seguridadAlimenticia = htmlspecialchars ($_POST['seguridadAlimenticia'],ENT_NOQUOTES,'UTF-8');	
	$buenaFermentacion = htmlspecialchars ($_POST['buenaFermentacion'],ENT_NOQUOTES,'UTF-8');	
	$ligeramenteFermentado = htmlspecialchars ($_POST['ligeramenteFermentado'],ENT_NOQUOTES,'UTF-8');	
	$granoVioleta = htmlspecialchars ($_POST['granoVioleta'],ENT_NOQUOTES,'UTF-8');	
	$granoPizarroso = htmlspecialchars ($_POST['granoPizarroso'],ENT_NOQUOTES,'UTF-8');	
	$mohos = htmlspecialchars ($_POST['mohos'],ENT_NOQUOTES,'UTF-8');	
	$danioInsecto = htmlspecialchars ($_POST['danioInsecto'],ENT_NOQUOTES,'UTF-8');	
	$vulnerado = htmlspecialchars ($_POST['vulnerado'],ENT_NOQUOTES,'UTF-8');	
	$total = htmlspecialchars ($_POST['total'],ENT_NOQUOTES,'UTF-8');	
	$multiple = htmlspecialchars ($_POST['multiple'],ENT_NOQUOTES,'UTF-8');	
	$partido = htmlspecialchars ($_POST['partido'],ENT_NOQUOTES,'UTF-8');	
	$planoGranza = htmlspecialchars ($_POST['planoGranza'],ENT_NOQUOTES,'UTF-8');	
	$impureza = htmlspecialchars ($_POST['impureza'],ENT_NOQUOTES,'UTF-8');	
	$materiaExtrania = htmlspecialchars ($_POST['materiaExtrania'],ENT_NOQUOTES,'UTF-8');	
	$cacaoTrinitario = htmlspecialchars ($_POST['cacaoTrinitario'],ENT_NOQUOTES,'UTF-8');	
	$pesoCacao = htmlspecialchars ($_POST['pesoCacao'],ENT_NOQUOTES,'UTF-8');	
	$numeroPepasCacao = htmlspecialchars ($_POST['numeroPepasCacao'],ENT_NOQUOTES,'UTF-8');	
	$humedad = htmlspecialchars ($_POST['humedad'],ENT_NOQUOTES,'UTF-8');	
	$observacion = htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8');	
	
		
	try {
		$conexion = new Conexion();
		$cc = new ControladorCertificadoCalidad();
		
				
		$cc->guardarInspeccionVerificadora($conexion, $idSolicitud, $fechaAnalisis, $vapor, $muestraInspector, $contraMuestra, $tipoInspeccion, $tipoCacaoVerificado, $higiene,
										   $seguridadAlimenticia, $buenaFermentacion, $ligeramenteFermentado, $granoVioleta, $granoPizarroso, $mohos, $danioInsecto, 
										   $vulnerado, $total, $multiple, $partido, $planoGranza, $impureza, $materiaExtrania, $cacaoTrinitario, $pesoCacao, $numeroPepasCacao, 
										   $humedad, $observacion, $inspector);

		$cc->actualizarEstadoLote($conexion, $idSolicitud, 'inspeccionResponsable');
		
			
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = "Se ha ingresado la información correctamente";
		

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


		