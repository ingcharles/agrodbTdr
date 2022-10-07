<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRequisitos.php';
require_once '../../clases/ControladorAuditoria.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	
	$idComposicion = htmlspecialchars ($_POST['idComposicion'],ENT_NOQUOTES,'UTF-8');
	$idProducto = htmlspecialchars ($_POST['idProducto'],ENT_NOQUOTES,'UTF-8');
	
	$tipo_aplicacion = ($_SESSION['idAplicacion']);
	
	try {
		$conexion = new Conexion();
		$cr = new ControladorRequisitos();
		$ca = new ControladorAuditoria();
		
		$cr->quitarComposicionProducto($conexion, $idComposicion);
		
		$nuevoComposicion = $cr->listarNuevaComposicionProductosInocuidad($conexion, $idProducto);
		
		$ingredienteActivoPI = '';
		
		while($fila = pg_fetch_assoc($nuevoComposicion)){
				$ingredienteActivoPI .= ' + '.$fila['ingrediente_quimico'];
		}
		
		$cr -> actualizarComposicionProducto($conexion,$idProducto,$ingredienteActivoPI);
		
		//AUDOTORIA
		
		$qTransaccion = $ca -> buscarTransaccion($conexion, $idProducto, $_SESSION['idAplicacion']);
		$transaccion = pg_fetch_assoc($qTransaccion);
		
		if($transaccion['id_transaccion'] == ''){
			$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
			$qTransaccion = $ca ->guardarTransaccion($conexion, $idProducto, pg_fetch_result($qLog, 0, 'id_log'));
		}
		
		$ca ->guardarEliminar($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha eliminado la composición '.$idComposicion.' del producto '.$idProducto);
		
		//FIN AUDITORIA
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $idProducto.'-'.$idComposicion;
		
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
