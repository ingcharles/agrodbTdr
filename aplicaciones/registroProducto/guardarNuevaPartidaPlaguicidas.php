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
    	$idProducto = htmlspecialchars ($_POST['idProductoInocuidad'],ENT_NOQUOTES,'UTF-8');
    	$partidaArancelaria = htmlspecialchars ($_POST['partidaArancelaria'],ENT_NOQUOTES,'UTF-8');
    	$areaProducto = htmlspecialchars ($_POST['areaProducto'],ENT_NOQUOTES,'UTF-8');
    		
    	$tipo_aplicacion = ($_SESSION['idAplicacion']);
    	
    	try {
    		$conexion = new Conexion();
    		$cc = new ControladorCatalogos();
    		$cr = new ControladorRequisitos();
    		$ca = new ControladorAuditoria();
    		
    		if(pg_num_rows($cr->buscarPartidaArancelaria($conexion, $idProducto, $partidaArancelaria))==0){
    		    
    		    //Generación del Código de Producto
    		    if($partidaArancelaria != '' || $partidaArancelaria != 0){
    		        $qCodigoProducto = $cc->obtenerCodigoProducto($conexion, $partidaArancelaria);
    		        $codigoProductoAnterior = pg_fetch_result($qCodigoProducto, 0, 'codigo');
    		        
    		        $qCodigoProductoPlaguicida = $cr->obtenerCodigoProductoPlaguicidas($conexion, $partidaArancelaria);
    		        $codigoProductoPlaguicida = pg_fetch_result($qCodigoProductoPlaguicida, 0, 'codigo');
    		        
    		        if($codigoProductoAnterior > $codigoProductoPlaguicida){
    		            $codigoProducto = str_pad(pg_fetch_result($qCodigoProducto, 0, 'codigo'), 4, "0", STR_PAD_LEFT);
    		        }else{
    		            $codigoProducto = str_pad(pg_fetch_result($qCodigoProductoPlaguicida, 0, 'codigo'), 4, "0", STR_PAD_LEFT);
    		        }
    		    }else{
    		        $codigoProducto = 0;
    		    }		    
    		    
    		    $idPartida = pg_fetch_result($cr -> guardarPartidaArancelaria($conexion, $idProducto, $partidaArancelaria, $codigoProducto), 0, 'id_partida_arancelaria');
    		
    		    /*AUDITORIA*/
    		    $qTransaccion = $ca -> buscarTransaccion($conexion, $idProducto, $_SESSION['idAplicacion']);
    		    $transaccion = pg_fetch_assoc($qTransaccion);
    		    
    		    if($transaccion['id_transaccion'] == ''){
    		        $qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
    		        $qTransaccion = $ca ->guardarTransaccion($conexion, $idProducto, pg_fetch_result($qLog, 0, 'id_log'));
    		    }
    		    
    		    $ca ->guardarUpdate($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha asociado la partida arancelaria '.$partidaArancelaria.' con el código de producto '.$codigoProducto.' al producto con Id '.$idProducto);
    		    /*FIN AUDITORIA*/
    	
    			$mensaje['estado'] = 'exito';
    			$mensaje['mensaje'] = $cr->imprimirLineaPartidasArancelarias( $idPartida, $idProducto, $partidaArancelaria, $codigoProducto, 'activo', 'registroProducto', $areaProducto);
    		}else{
    			$mensaje['estado'] = 'error';
    			$mensaje['mensaje'] = 'La partida arancelaria ya ha sido ingresada.';
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