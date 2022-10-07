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
        $idPartida = htmlspecialchars ($_POST['idPartida'],ENT_NOQUOTES,'UTF-8');
        $idProducto = htmlspecialchars ($_POST['idProducto'],ENT_NOQUOTES,'UTF-8');
    	$partidaArancelaria = htmlspecialchars ($_POST['partidaArancelaria'],ENT_NOQUOTES,'UTF-8');
    	$codigoComplementario = htmlspecialchars ($_POST['codigoComplementario'],ENT_NOQUOTES,'UTF-8');
    	$codigoSuplementario = htmlspecialchars ($_POST['codigoSuplementario'],ENT_NOQUOTES,'UTF-8');
    	$areaProducto = htmlspecialchars ($_POST['areaProducto'],ENT_NOQUOTES,'UTF-8');
    	
    	$tipo_aplicacion = ($_SESSION['idAplicacion']);
    	
    	try {
    		$conexion = new Conexion();
    		$cr = new ControladorRequisitos();
    		$cc = new ControladorCatalogos();
    		$ca = new ControladorAuditoria();
    		
    		if(pg_num_rows($cr->buscarCodigoCompSuplPlaguicida($conexion, $idPartida, $codigoComplementario, $codigoSuplementario))==0){
    		    $codigoCompSupl = pg_fetch_result($cr -> guardarNuevoCodigoCompSuplPlaguicida($conexion, $idPartida, $codigoComplementario, $codigoSuplementario), 0, 'id_codigo_comp_supl');
    			
    			/*AUDITORIA*/
    			
    		    $qTransaccion = $ca -> buscarTransaccion($conexion, $idProducto, $_SESSION['idAplicacion']);
    			$transaccion = pg_fetch_assoc($qTransaccion);
    			
    			if($transaccion['id_transaccion'] == ''){
    				$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
    				$qTransaccion = $ca ->guardarTransaccion($conexion, $idProducto, pg_fetch_result($qLog, 0, 'id_log'));
    			}
    			
    			$ca ->guardarInsert($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha asociado al producto con id '.
    			                                                 $idProducto.' y partida presupuestaria '. $partidaArancelaria .' el código complementario '.$codigoComplementario.
    			                                                 ' y el código suplementario '.$codigoSuplementario );
    			
    			/*FIN AUDITORIA*/
    			
    			$mensaje['estado'] = 'exito';
    			$mensaje['mensaje'] = $cr-> imprimirCodigoCompSuplPlaguicida($codigoCompSupl, $idProducto, $idPartida, $partidaArancelaria, $codigoComplementario, $codigoSuplementario, $areaProducto, 'activo', 'registroProducto');
    		}else{
    			$mensaje['estado'] = 'error';
    			$mensaje['mensaje'] = 'El código complementario y suplementario ya ha sido asignado.';
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