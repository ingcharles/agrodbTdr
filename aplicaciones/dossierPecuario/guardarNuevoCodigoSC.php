<?php
session_start();
require_once '../../clases/Conexion.php';

require_once '../../clases/ControladorDossierPecuario.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{


	$id_solicitud = htmlspecialchars ($_POST['id_solicitud'],ENT_NOQUOTES,'UTF-8');
	$codigoComplementario = htmlspecialchars ($_POST['codigoComplementario'],ENT_NOQUOTES,'UTF-8');
	$codigoSuplementario = htmlspecialchars ($_POST['codigoSuplementario'],ENT_NOQUOTES,'UTF-8');

	$tipo_aplicacion = ($_SESSION['idAplicacion']);

	try {
		$conexion = new Conexion();
		
		$cp = new ControladorDossierPecuario();


		if(pg_num_rows($cp->buscarCodigoComplementarioSuplementario($conexion, $id_solicitud, $codigoComplementario, $codigoSuplementario))==0){
			$cp -> guardarNuevoCodigoComplementarioSuplementario($conexion, $id_solicitud, $codigoComplementario, $codigoSuplementario);

			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cp-> imprimirCodigoComplementarioSuplementario($id_solicitud, $codigoComplementario, $codigoSuplementario);
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'El código complementario y suplementario ya ha sido asignado.';
		}



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