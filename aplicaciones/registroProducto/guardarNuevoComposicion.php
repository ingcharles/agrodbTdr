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
	$idProductoInocuidad = htmlspecialchars ($_POST['idProductoInocuidad'],ENT_NOQUOTES,'UTF-8');
	$idIngredienteActivo = htmlspecialchars ($_POST['ingredienteActivo'],ENT_NOQUOTES,'UTF-8');
	$nombreIngredienteActivo = htmlspecialchars ($_POST['nombreIngredienteActivo'],ENT_NOQUOTES,'UTF-8');
	$concentracion = htmlspecialchars ($_POST['concentracion'],ENT_NOQUOTES,'UTF-8');
	$unidadMedida = htmlspecialchars ($_POST['unidadMedidaConcentracion'],ENT_NOQUOTES,'UTF-8');
	$ingredienteQuimico = htmlspecialchars ($_POST['ingredienteQuimico'],ENT_NOQUOTES,'UTF-8');
	$idTipoComponente = htmlspecialchars ($_POST['idTipoComponente'],ENT_NOQUOTES,'UTF-8');
	$tipoComponente = htmlspecialchars ($_POST['tipoComponente'],ENT_NOQUOTES,'UTF-8');
	$areaProducto = htmlspecialchars ($_POST['idAreaC'],ENT_NOQUOTES,'UTF-8');
	$nombreUMedConcentracion = htmlspecialchars ($_POST['nombreUMedConcentracion'],ENT_NOQUOTES,'UTF-8');
		
	$tipo_aplicacion = ($_SESSION['idAplicacion']);
	
	try {
		$conexion = new Conexion();
		$cr = new ControladorRequisitos();
		$ca = new ControladorAuditoria();
		
		if ($idTipoComponente == '' || $idTipoComponente == null || !isset($idTipoComponente)){
		    $idTipoComponente = null;
		    $tipoComponente = null;
		}
		
		if(pg_num_rows($cr->buscarComposicion($conexion, $idProductoInocuidad, $idIngredienteActivo, $idTipoComponente))==0){		
			
		    $cr -> guardarProductoInocuidadTMP($conexion, $idProductoInocuidad);
		    $composicion = pg_fetch_result($cr->guardarComposicion($conexion, $idProductoInocuidad, $idIngredienteActivo, $nombreIngredienteActivo, $concentracion, $unidadMedida, $idTipoComponente, $tipoComponente, $nombreUMedConcentracion) , 0 , 'id_composicion');
			$nuevoIngredienteActivo = $cr ->buscarProductoInocuidad($conexion, $idProductoInocuidad);
			
			$ingredienteActivo = '';
			$fila = pg_fetch_assoc($nuevoIngredienteActivo);
			$ingredienteActivo = $fila['ingrediente_activo'].' + '. $ingredienteQuimico;
	
			$cr -> actualizarComposicionProducto($conexion,$idProductoInocuidad,$ingredienteActivo);
		
		/*AUDITORIA*/
			
			$qTransaccion = $ca -> buscarTransaccion($conexion, $idProductoInocuidad, $_SESSION['idAplicacion']);
			$transaccion = pg_fetch_assoc($qTransaccion);
				
			if($transaccion['id_transaccion'] == ''){
				$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
				$qTransaccion = $ca ->guardarTransaccion($conexion, $idProductoInocuidad, pg_fetch_result($qLog, 0, 'id_log'));
			}
			
			$ca ->guardarInsert($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha asociado el producto con id '.$idProductoInocuidad.' con la composición '.$tipoComponente.' con nombre '.$concentracion);
		/*FIN AUDITORIA*/
	
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cr->imprimirLineaComposicion( $idProductoInocuidad, $composicion, $tipoComponente, $nombreIngredienteActivo, $concentracion, $unidadMedida, $areaProducto);
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'El composición ya ha sido ingresado.';
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