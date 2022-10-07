<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorFinanciero.php';
require_once '../../clases/ControladorAuditoria.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	$exoneracion = htmlspecialchars ($_POST['estadoRequisito'],ENT_NOQUOTES,'UTF-8');
	$idServicioProducto = htmlspecialchars($_POST['idServicioProducto'],ENT_NOQUOTES,'UTF-8');
	
	//$tipo_aplicacion = ($_SESSION['idAplicacion']);
	
	try {
		$conexion = new Conexion();
		$cf = new ControladorFinanciero();
		//$ca = new ControladorAuditoria();
		
		
		
		$cf->actualizarEstadoServicioProducto($conexion, $idServicioProducto, $exoneracion=='activo'?$exoneracion='true':$exoneracion='false');
		
		/*AUDOTORIA*/
			
		/*$qTransaccion = $ca -> buscarTransaccion($conexion, $idRequisitoComercio, $_SESSION['idAplicacion']);
		$transaccion = pg_fetch_assoc($qTransaccion);
			
		if($transaccion['id_transaccion'] == ''){
			$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
			$qTransaccion = $ca ->guardarTransaccion($conexion, $idRequisitoComercio, pg_fetch_result($qLog, 0, 'id_log'));
		}
			
		$ca ->guardarUpdate($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha modificado el estado del requisito de comercio de '.$tipoRequisito.' ha '.$estadoRequisito.' con código '.$idRequisitoComercio);
		*/
		/*FIN AUDITORIA*/
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $idServicioProducto;
		
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