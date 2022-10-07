<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
/*require_once '../../clases/ControladorAuditoria.php';*/

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

	$idVariedad = htmlspecialchars ($_POST['idVariedad'],ENT_NOQUOTES,'UTF-8');
	$idOperacion = htmlspecialchars ($_POST['idOperacion'],ENT_NOQUOTES,'UTF-8');
//	$codigoVariedad = htmlspecialchars ($_POST['codigoVariedad'],ENT_NOQUOTES,'UTF-8');


	/*$tipo_aplicacion = ($_SESSION['idAplicacion']);*/

	try {
		$conexion = new Conexion();
		$cro = new ControladorRegistroOperador();
		/*$ca = new ControladorAuditoria();*/

		$cro->quitarVariedadOperacion($conexion, $idOperacion, $idVariedad);

		/*AUDOTORIA*/

		/*	$qTransaccion = $ca -> buscarTransaccion($conexion, $idProducto,  $_SESSION['idAplicacion']);
		 $transaccion = pg_fetch_assoc($qTransaccion);

		if($transaccion['id_transaccion'] == ''){
		$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
		$qTransaccion = $ca ->guardarTransaccion($conexion, $idProducto, pg_fetch_result($qLog, 0, 'id_log'));
		}

		$ca ->guardarEliminar($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha eliminado el código complementario '.$idCodigoComplementario.' y código suplementario '.$idCodigoSuplementario.' del producto con codigo '.$idProducto);
			
		/*FIN AUDITORIA*/


		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $idOperacion.'-'.$idVariedad;

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

