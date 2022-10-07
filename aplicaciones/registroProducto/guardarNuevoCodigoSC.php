<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRequisitos.php';
require_once '../../clases/ControladorAuditoria.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{

	
	$idProducto = htmlspecialchars ($_POST['idProductoSC'],ENT_NOQUOTES,'UTF-8');
	$codigoComplementario = htmlspecialchars ($_POST['codigoComplementario'],ENT_NOQUOTES,'UTF-8');
	$codigoSuplementario = htmlspecialchars ($_POST['codigoSuplementario'],ENT_NOQUOTES,'UTF-8');
	
	$tipo_aplicacion = ($_SESSION['idAplicacion']);
	
	try {
		$conexion = new Conexion();
		$cr = new ControladorRequisitos();
		$ca = new ControladorAuditoria();
		
		if(pg_num_rows($cr->buscarCodigoComplementarioSuplementario($conexion, $idProducto, $codigoComplementario, $codigoSuplementario))==0){
			$cr -> guardarNuevoCodigoComplementarioSuplementario($conexion, $idProducto, $codigoComplementario, $codigoSuplementario);
			
			/*AUDOTORIA*/
			
			$qTransaccion = $ca -> buscarTransaccion($conexion, $idProducto, $_SESSION['idAplicacion']);
			$transaccion = pg_fetch_assoc($qTransaccion);
			
			if($transaccion['id_transaccion'] == ''){
				$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
				$qTransaccion = $ca ->guardarTransaccion($conexion, $idProducto, pg_fetch_result($qLog, 0, 'id_log'));
			}
			
			$ca ->guardarInsert($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha asociado al producto con id '.$idProducto.' el codigo complementaio '.$codigoComplementario.' y el codigo suplementario '.$codigoSuplementario );
			
			/*FIN AUDITORIA*/
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cr-> imprimirCodigoComplementarioSuplementario($idProducto, $codigoComplementario, $codigoSuplementario);
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