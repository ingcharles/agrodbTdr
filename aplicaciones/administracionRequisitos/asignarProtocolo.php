<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProtocolos.php';
require_once '../../clases/ControladorAuditoria.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	
	$idProtocoloComercio = htmlspecialchars ($_POST['idProtocoloComercio'],ENT_NOQUOTES,'UTF-8');
	//$tipoRequisito = htmlspecialchars ($_POST['tipoRequisito'],ENT_NOQUOTES,'UTF-8');
	$protocolo = htmlspecialchars ($_POST['protocolo'],ENT_NOQUOTES,'UTF-8');
	$nombreProtocolo = htmlspecialchars ($_POST['nombreProtocolo'],ENT_NOQUOTES,'UTF-8');
	$identificadorCreacionProtocoloAsignado = $_SESSION['usuario'];
	
	$tipo_aplicacion = ($_SESSION['idAplicacion']);
	
	
	try {
		$conexion = new Conexion();
		$cp = new ControladorProtocolos();
		$ca = new ControladorAuditoria();
		
		if(pg_num_rows($cp->buscarProductoProtocolo($conexion, $idProtocoloComercio, $protocolo)) == 0){
			//echo "entoncesss";
			$protocoloAsignado = pg_fetch_row($cp->guardarNuevoProtocoloAsignado($conexion, $idProtocoloComercio, $protocolo, 'activo', $identificadorCreacionProtocoloAsignado));
			
			/*AUDOTORIA*/
			/*	
			$qTransaccion = $ca -> buscarTransaccion($conexion, $idRequisitoComercio, $_SESSION['idAplicacion']);
			$transaccion = pg_fetch_assoc($qTransaccion);
				
			if($transaccion['id_transaccion'] == ''){
				$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
				$qTransaccion = $ca ->guardarTransaccion($conexion, $idRequisitoComercio, pg_fetch_result($qLog, 0, 'id_log'));
			}
				
			$ca ->guardarUpdate($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha asignado el requisito de '.$tipoRequisito.' '.$nombreRequisito.' con código '.$requisito);
			*/
			/*FIN AUDITORIA*/
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cp->imprimirLineaProtocolo($idProtocoloComercio, $protocolo, $nombreProtocolo, 'activo');
		}else{
			$mensaje['mensaje'] = 'El requisito elegido ya ha sido asignado.';
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