<?php
session_start();
require_once '../../clases/Conexion.php';

require_once '../../clases/ControladorDossierPecuario.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

	$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
	$idCodigoComplementario = htmlspecialchars ($_POST['idCodigoComplementario'],ENT_NOQUOTES,'UTF-8');
	$idCodigoSuplementario = htmlspecialchars ($_POST['idCodigoSuplementario'],ENT_NOQUOTES,'UTF-8');

	$tipo_aplicacion = ($_SESSION['idAplicacion']);

	try {
		$conexion = new Conexion();
		
		$cp = new ControladorDossierPecuario();

		$cp->quitarCodigoComplementarioSuplementario($conexion, $id_solicitud, $idCodigoComplementario, $idCodigoSuplementario);

	

		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $id_solicitud.'-'.$idCodigoComplementario.'-'.$idCodigoSuplementario;

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