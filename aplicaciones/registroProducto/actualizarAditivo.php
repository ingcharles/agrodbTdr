<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
    $idAditivo = trim(htmlspecialchars ($_POST['idAditivo'],ENT_NOQUOTES,'UTF-8'));
    $idArea = trim(htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8'));
    $nombreComun = trim(htmlspecialchars ($_POST['nombreComun'],ENT_NOQUOTES,'UTF-8'));
    $nombreQuimico = trim(htmlspecialchars ($_POST['nombreQuimico'],ENT_NOQUOTES,'UTF-8'));
    $cas = trim(htmlspecialchars ($_POST['cas'],ENT_NOQUOTES,'UTF-8'));
    $formulaQuimica = trim(htmlspecialchars ($_POST['formulaQuimica'],ENT_NOQUOTES,'UTF-8'));
    $grupoQuimico = trim(htmlspecialchars ($_POST['grupoQuimico'],ENT_NOQUOTES,'UTF-8'));
		
	try {
		$conexion = new Conexion();
		$cc = new ControladorCatalogos();
		
		$cc->actualizarAditivo($conexion, $idAditivo, $idArea, $nombreComun, $nombreQuimico, $cas, $formulaQuimica, $grupoQuimico, $_SESSION['usuario']);
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos fueron actualizados';
		
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
