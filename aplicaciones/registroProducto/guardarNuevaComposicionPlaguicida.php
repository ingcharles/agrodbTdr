<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorRequisitos.php';
	require_once '../../clases/ControladorAuditoria.php';
	
	$mensaje = array();
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$idProducto = htmlspecialchars ($_POST['idProducto'],ENT_NOQUOTES,'UTF-8');
	$idElementoComposicion = htmlspecialchars ($_POST['comboComposicion'],ENT_NOQUOTES,'UTF-8');
	$nombreElementoComposicion = htmlspecialchars ($_POST['nombreComposicion'],ENT_NOQUOTES,'UTF-8');
	$concentracion = htmlspecialchars ($_POST['concentracion'],ENT_NOQUOTES,'UTF-8');
	$unidadMedida = htmlspecialchars ($_POST['unidadMedida'],ENT_NOQUOTES,'UTF-8');
	$idTipoComponente = htmlspecialchars ($_POST['idTipoComponente'],ENT_NOQUOTES,'UTF-8');
	$tipoComponente = htmlspecialchars ($_POST['tipoComponente'],ENT_NOQUOTES,'UTF-8');
	$nombreUMedConcentracion = htmlspecialchars ($_POST['nombreUMedConcentracion'],ENT_NOQUOTES,'UTF-8');
	$areaProducto = htmlspecialchars ($_POST['idAreaC'],ENT_NOQUOTES,'UTF-8');
		
	$tipo_aplicacion = ($_SESSION['idAplicacion']);
	
	try {
		$conexion = new Conexion();
		$cc = new ControladorCatalogos();
		$cr = new ControladorRequisitos();
		$ca = new ControladorAuditoria();
		
		if(pg_num_rows($cr->buscarComposicion($conexion, $idProducto, $idElementoComposicion, $idTipoComponente))==0){
			
			if ($idTipoComponente == '' || $idTipoComponente == null){
				$idTipoComponente = 'null';
				$tipoComponente = null;
			}
			
			$composicion = pg_fetch_result($cr -> guardarComposicion($conexion, $idProducto, $idElementoComposicion, $nombreElementoComposicion, $concentracion, $unidadMedida, $idTipoComponente, $tipoComponente, $nombreUMedConcentracion), 0 , 'id_composicion');
		
    		/*AUDITORIA*/
    			
    			$qTransaccion = $ca -> buscarTransaccion($conexion, $idProducto, $_SESSION['idAplicacion']);
    			$transaccion = pg_fetch_assoc($qTransaccion);
    				
    			if($transaccion['id_transaccion'] == ''){
    				$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
    				$qTransaccion = $ca ->guardarTransaccion($conexion, $idProducto, pg_fetch_result($qLog, 0, 'id_log'));
    			}
    			
    			$ca ->guardarInsert($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha asociado el producto con id '.$idProducto.' con la composición '.$tipoComponente.' con nombre '.$nombreElementoComposicion);
    			
    		/*FIN AUDITORIA*/
	
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cr->imprimirLineaComposicion( $idProducto, $composicion, $tipoComponente, $nombreElementoComposicion, $concentracion, $unidadMedida, $areaProducto);
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'La composición ya ha sido ingresada.';
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