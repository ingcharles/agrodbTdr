<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRequisitos.php';
require_once '../../clases/ControladorAuditoria.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{


	$idProducto = htmlspecialchars ($_POST['idProductoVP'],ENT_NOQUOTES,'UTF-8');
	$idVariedad = htmlspecialchars ($_POST['variedadProducto'],ENT_NOQUOTES,'UTF-8');
	$nombreVariedad = htmlspecialchars ($_POST['nombreVariedad'],ENT_NOQUOTES,'UTF-8');

	$tipo_aplicacion = ($_SESSION['idAplicacion']);

	try {
		$conexion = new Conexion();
		$cr = new ControladorRequisitos();
		$ca = new ControladorAuditoria();
			
		if(pg_num_rows($cr->buscarVariedadProducto($conexion, $idProducto, $idVariedad, $codigoVariedad))==0){

			$cr->guardarProductoVegetal($conexion, $idProducto);
			$secuencialCodigoVariedad=$cr->autogenerarSecuencialCodigoVariedad($conexion, $idProducto);
			$fila = pg_fetch_assoc($secuencialCodigoVariedad);

			$secuencial = ($fila['secuencial'])+1;
			$codigoVariedad = str_pad($secuencial, 4, "0", STR_PAD_LEFT);

			$cr->guardarVariedadProducto($conexion, $idProducto, $idVariedad, $codigoVariedad);
			
			/*AUDITORIA*/
			
			$qTransaccion = $ca -> buscarTransaccion($conexion, $idProducto,  $_SESSION['idAplicacion']);
			$transaccion = pg_fetch_assoc($qTransaccion);
			
			if($transaccion['id_transaccion'] == ''){
				$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
				$qTransaccion = $ca ->guardarTransaccion($conexion, $idProducto, pg_fetch_result($qLog, 0, 'id_log'));
			}
			
			$ca ->guardarInsert($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha asociado al producto la variedad '.$nombreVariedad.' con código '.$codigoVariedad );
			
			/*FIN AUDITORIA*/

			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cr->imprimirVariedad($idProducto,$idVariedad,$nombreVariedad, $codigoVariedad);

		}
		else {
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'La variedad ya ha sido asignada a este producto.';
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