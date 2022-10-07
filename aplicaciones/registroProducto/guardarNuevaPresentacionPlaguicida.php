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
        $idProducto = htmlspecialchars ($_POST['idProducto'],ENT_NOQUOTES,'UTF-8');
        $idPartida = htmlspecialchars ($_POST['idPartida'],ENT_NOQUOTES,'UTF-8');
        $idCodigoCompSupl = htmlspecialchars ($_POST['idCodigoCompSupl'],ENT_NOQUOTES,'UTF-8');
        $presentacion = htmlspecialchars ($_POST['presentacion'],ENT_NOQUOTES,'UTF-8');
    	$idUnidad = htmlspecialchars ($_POST['idUnidad'],ENT_NOQUOTES,'UTF-8');
    	$unidad = htmlspecialchars ($_POST['unidad'],ENT_NOQUOTES,'UTF-8');
    	$codigoUnidad = htmlspecialchars ($_POST['codigoUnidad'],ENT_NOQUOTES,'UTF-8');
    	$partidaArancelaria = htmlspecialchars ($_POST['partidaArancelaria'],ENT_NOQUOTES,'UTF-8');
    	$codigoComplementario = htmlspecialchars ($_POST['codigoComplementario'],ENT_NOQUOTES,'UTF-8');
    	$codigoSuplementario = htmlspecialchars ($_POST['codigoSuplementario'],ENT_NOQUOTES,'UTF-8');
    	
    	$tipo_aplicacion = ($_SESSION['idAplicacion']);
    	
    	try {
    		$conexion = new Conexion();
    		$cr = new ControladorRequisitos();
    		$cc = new ControladorCatalogos();
    		$ca = new ControladorAuditoria();
    		
    		if(pg_num_rows($cr->buscarPresentacionPlaguicida($conexion, $idCodigoCompSupl, $presentacion, $idUnidad))==0){
    		    
    		    $qCodigo = $cr->obtenerCodigoPresentacionPlaguicida($conexion, $idProducto, $idPartida, $idCodigoCompSupl);
    		    $codigoPresentacion = str_pad(pg_fetch_result($qCodigo, 0, 'codigo'), 4, "0", STR_PAD_LEFT);
    		    
    		    $idPresentacion = pg_fetch_result($cr -> guardarNuevaPresentacionPlaguicida($conexion, $presentacion, $idUnidad, $codigoUnidad, $codigoPresentacion, $idCodigoCompSupl), 0, 'id_presentacion');
    			
    			/*AUDITORIA*/
    			
    		    $qTransaccion = $ca -> buscarTransaccion($conexion, $idProducto, $_SESSION['idAplicacion']);
    			$transaccion = pg_fetch_assoc($qTransaccion);
    			
    			if($transaccion['id_transaccion'] == ''){
    				$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
    				$qTransaccion = $ca ->guardarTransaccion($conexion, $idProducto, pg_fetch_result($qLog, 0, 'id_log'));
    			}
    			
    			$ca ->guardarInsert($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha asociado al producto con id '.
    			                                                 $idProducto.', partida presupuestaria '. $partidaArancelaria .', código complementario '.$codigoComplementario.
    			                                                 ' y el código suplementario '.$codigoSuplementario .' la presentación ' . $presentacion);
    			
    			/*FIN AUDITORIA*/
    			
    			$mensaje['estado'] = 'exito';
    			$mensaje['mensaje'] = $cr-> imprimirLineaPresentacionPlaguicida($idProducto, $idPresentacion, $presentacion, $codigoUnidad, $codigoPresentacion, 'activo', 'registroProducto');
    		}else{
    			$mensaje['estado'] = 'error';
    			$mensaje['mensaje'] = 'La presentación del producto ya ha sido asignada.';
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