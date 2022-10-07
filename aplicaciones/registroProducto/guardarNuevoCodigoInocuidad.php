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
	$idProductoIncouidad = htmlspecialchars ($_POST['idProductoInocuidad'],ENT_NOQUOTES,'UTF-8');
	$presentacion = htmlspecialchars ($_POST['presentacion'],ENT_NOQUOTES,'UTF-8');
	$unidad = htmlspecialchars ($_POST['unidadPresentacion'],ENT_NOQUOTES,'UTF-8');
	$areaProducto = htmlspecialchars ($_POST['idAreaC'],ENT_NOQUOTES,'UTF-8');
	$nombreUMedPresentacion = htmlspecialchars ($_POST['nombreUMedPresentacion'],ENT_NOQUOTES,'UTF-8');
	
	$tipo_aplicacion = ($_SESSION['idAplicacion']);

	try {
		$conexion = new Conexion();
		$cr = new ControladorRequisitos();
		$cc = new ControladorCatalogos();
		$ca = new ControladorAuditoria();
		
		$qSubcodigo = $cc->obtenerCodigoInocuidad($conexion, $idProductoIncouidad);
		$subcodigo = str_pad(pg_fetch_result($qSubcodigo, 0, 'codigo'), 4, "0", STR_PAD_LEFT);
		
		if(pg_num_rows($cr->buscarCodigoInocuidad($conexion, $idProductoIncouidad, $presentacion,$unidad))==0){
			
			$cr -> guardarProductoInocuidadTMP($conexion, $idProductoIncouidad);
			$codigos = $cr -> guardarNuevoCodigoInocuidad($conexion, $idProductoIncouidad,$subcodigo, $presentacion, $unidad, $nombreUMedPresentacion);
		
			/*AUDITORIA*/
			
			$qTransaccion = $ca -> buscarTransaccion($conexion, $idProductoIncouidad, $_SESSION['idAplicacion']);
			$transaccion = pg_fetch_assoc($qTransaccion);
			
			if($transaccion['id_transaccion'] == ''){
				$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
				$qTransaccion = $ca ->guardarTransaccion($conexion, $idProductoIncouidad, pg_fetch_result($qLog, 0, 'id_log'));
			}
			
			$ca ->guardarInsert($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha asociado al producto con id '.$idProductoIncouidad.' la presentación '.$presentacion);
			
			/*FIN AUDITORIA*/
	
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cr->imprimirCodigoInocuidad($idProductoIncouidad, $subcodigo, $presentacion, $unidad, $nombreUMedPresentacion, $areaProducto);
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'El Presentación ya ha sido ingresado.';
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
