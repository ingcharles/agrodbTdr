<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProtocolos.php';
require_once '../../clases/ControladorAuditoria.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
//echo $idProtocoloComercio;
try{
	$idProtocoloComercio = htmlspecialchars ($_POST['idProtocoloComercio'],ENT_NOQUOTES,'UTF-8');
	
	$tipo_aplicacion = ($_SESSION['idAplicacion']);
	
	
	try {
		$conexion = new Conexion();
		$cp = new ControladorProtocolos();
		$ca = new ControladorAuditoria();
		
		
		//$datosRequisitoComercio = pg_fetch_assoc($cp->abrirProtocoloComercio($conexion, $idProtocoloComercio));
		
		$cp->quitarPaisProtocolo($conexion, $idProtocoloComercio);
		
		/*AUDOTORIA*/
		
		/*$qTransaccion = $ca -> buscarTransaccion($conexion, $idRequisitoComercio, $_SESSION['idAplicacion']);
		$transaccion = pg_fetch_assoc($qTransaccion);
		
		if($transaccion['id_transaccion'] == ''){
			$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
			$qTransaccion = $ca ->guardarTransaccion($conexion, $idRequisitoComercio, pg_fetch_result($qLog, 0, 'id_log'));
		}/*/
		
		//$ca ->guardarEliminar($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha eliminado el requisito de comercialización con código '.$idRequisitoComercio.' correspondiente al país '.$datosRequisitoComercio['nombre_pais'].' con código '.$datosRequisitoComercio['id_localizacion'].' y producto '.$datosRequisitoComercio['nombre_producto'].' con código '.$datosRequisitoComercio['id_producto'].' del área '.$datosRequisitoComercio['tipo']);
			
		/*FIN AUDITORIA*/
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $idProtocoloComercio;
		
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