<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRequisitos.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAuditoria.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	$nombreRequisito = htmlspecialchars ($_POST['nombreRequisito'],ENT_NOQUOTES,'UTF-8');
	$tipo = htmlspecialchars ($_POST['tipo'],ENT_NOQUOTES,'UTF-8');
	$documento = htmlspecialchars ($_POST['documento'],ENT_NOQUOTES,'UTF-8');
	$area = htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8');
	$detalle = htmlspecialchars ($_POST['detalle'],ENT_NOQUOTES,'UTF-8');
	$detalleImpresion = htmlspecialchars ($_POST['detalleImpresion'],ENT_NOQUOTES,'UTF-8');
	$codigo = htmlspecialchars ($_POST['codigo'],ENT_NOQUOTES,'UTF-8');
	$identificadorCreacionRequisito = $_SESSION['usuario'];
	
	$tipo_aplicacion = ($_SESSION['idAplicacion']);
	
	try {
		$conexion = new Conexion();
		$cr = new ControladorRequisitos();
		$cc = new ControladorCatalogos();
		$ca = new ControladorAuditoria();
		
		$qRequisito = $cr->guardarNuevoRequisito($conexion, $nombreRequisito, $documento, $area, $tipo, $detalle, $detalleImpresion, $codigo, $identificadorCreacionRequisito);
		
		/**AUDITORIA***/
			
		$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
		$qTransaccion = $ca ->guardarTransaccion($conexion,pg_fetch_result($qRequisito, 0, 'id_requisito') , pg_fetch_result($qLog, 0, 'id_log'));
		$ca ->guardarInsert($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha creado el requisito con nombre '.$nombreRequisito.' código '.pg_fetch_result($qRequisito, 0, 'id_requisito'));
			
		/**FIN AUDITORIA***/
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'El registro se ha guardado con éxito';
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