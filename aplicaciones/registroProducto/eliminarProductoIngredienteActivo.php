<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRequisitos.php';
require_once '../../clases/ControladorAuditoria.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	
	$idProductoInocuidad = htmlspecialchars ($_POST['idProductoInocuidad'],ENT_NOQUOTES,'UTF-8');
	$idPIngredienteActivo = htmlspecialchars ($_POST['idPIngredienteActivo'],ENT_NOQUOTES,'UTF-8');
	
	$tipo_aplicacion = ($_SESSION['idAplicacion']);
	
	try {
		$conexion = new Conexion();
		$cr = new ControladorRequisitos();
		$ca = new ControladorAuditoria();
		
		$cr->quitarProductoIngredienteActivo($conexion, $idProductoInocuidad, $idPIngredienteActivo);
		
		//AUDOTORIA
		
		$qTransaccion = $ca -> buscarTransaccion($conexion, $idProductoInocuidad, $_SESSION['idAplicacion']);
		$transaccion = pg_fetch_assoc($qTransaccion);
		
		if($transaccion['id_transaccion'] == ''){
			$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
			$qTransaccion = $ca ->guardarTransaccion($conexion, $idProductoInocuidad, pg_fetch_result($qLog, 0, 'id_log'));
		}
		
		$ca ->guardarEliminar($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha eliminado el código '.$idProductoInocuidad.' del producto '.$idPIngredienteActivo);
			
		//FIN AUDITORIA
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $idProductoInocuidad.'-'.$idPIngredienteActivo;
		
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