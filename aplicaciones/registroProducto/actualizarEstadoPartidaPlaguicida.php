<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorAuditoria.php';
    require_once '../../clases/ControladorCatalogos.php';
    require_once '../../clases/ControladorRequisitos.php';
    
    $mensaje = array();
    $mensaje['estado'] = 'error';
    $mensaje['mensaje'] = 'Ha ocurrido un error!';
    
    try{
    	
        $estado = htmlspecialchars ($_POST['estadoRequisito'],ENT_NOQUOTES,'UTF-8');
    	$idPartida = htmlspecialchars($_POST['idPartida'],ENT_NOQUOTES,'UTF-8');
    	$idProducto = htmlspecialchars($_POST['idProducto'],ENT_NOQUOTES,'UTF-8');
    	$partidaArancelaria = htmlspecialchars($_POST['partidaArancelaria'],ENT_NOQUOTES,'UTF-8');
    	$identificador = $_SESSION['usuario'];
    	
    	$tipo_aplicacion = ($_SESSION['idAplicacion']);
    	
    	try {
    		$conexion = new Conexion();
    		$ca = new ControladorAuditoria();
    		$cc = new ControladorCatalogos();
    		$cr = new ControladorRequisitos();
    		
    		$cr->actualizarEstadoPartida($conexion, $idPartida, $estado, $identificador);
    		
    		/*AUDITORIA*/
    		
    		$qTransaccion = $ca -> buscarTransaccion($conexion, $idProducto, $_SESSION['idAplicacion']);
    		$transaccion = pg_fetch_assoc($qTransaccion);
    		
    		if($transaccion['id_transaccion'] == ''){
    		    $qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
    		    $qTransaccion = $ca ->guardarTransaccion($conexion, $idProducto, pg_fetch_result($qLog, 0, 'id_log'));
    		}
    		
    		$ca ->guardarUpdate($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha modificado el estado de la partida arancelaria '.$partidaArancelaria.' con ID ' .$idPartida. ' a '.$estado);
    		
    		/*FIN AUDITORIA*/
    		
    		//Verificar todos los registros hijos		
    		$codigoCompSup = $cr->buscarCodigoCompSuplXproductoPartidaPlaguicida($conexion,$idPartida);
    		
    		if(pg_num_rows($codigoCompSup) > 0){
        		while($codigosCompSupl = pg_fetch_assoc($codigoCompSup)){
        		    //Actualizar estado de Códigos complementarios y suplementarios
        		    $cr->actualizarEstadoCodigoCompSupl($conexion, $codigosCompSupl['id_codigo_comp_supl'], $estado, $identificador);
        		    
        		    /*AUDITORIA*/
        		    
        		    $qTransaccion = $ca -> buscarTransaccion($conexion, $idProducto, $_SESSION['idAplicacion']);
        		    $transaccion = pg_fetch_assoc($qTransaccion);
        		    
        		    if($transaccion['id_transaccion'] == ''){
        		        $qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
        		        $qTransaccion = $ca ->guardarTransaccion($conexion, $idProducto, pg_fetch_result($qLog, 0, 'id_log'));
        		    }
        		    
        		    $ca ->guardarUpdate($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha modificado el estado del código complementario y suplementario con ID' .$codigosCompSupl['id_codigo_comp_supl'].' a '.$estado);
        		    
        		    /*FIN AUDITORIA*/
        		    
        		    
        		    //Buscar presentaciones por código complementario y suplementario
        		    $presentaciones = $cr->buscarPresentacionesXCodigoCompSupl($conexion,$codigosCompSupl['id_codigo_comp_supl']);
        		    
        		    if(pg_num_rows($presentaciones) > 0){
            		    while($presentacion = pg_fetch_assoc($presentaciones)){
            		        //Actualizar estado de Presentaciones
            		        $cr->actualizarEstadoPresentacion($conexion, $presentacion['id_presentacion'], $estado, $identificador);
            		        
            		        /*AUDITORIA*/
            		        
            		        $qTransaccion = $ca -> buscarTransaccion($conexion, $idProducto, $_SESSION['idAplicacion']);
            		        $transaccion = pg_fetch_assoc($qTransaccion);
            		        
            		        if($transaccion['id_transaccion'] == ''){
            		            $qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
            		            $qTransaccion = $ca ->guardarTransaccion($conexion, $idProducto, pg_fetch_result($qLog, 0, 'id_log'));
            		        }
            		        
            		        $ca ->guardarUpdate($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha modificado el estado de la presentación con ID' .$presentacion['id_presentacion'].' a '.$estado);
            		        
            		        /*FIN AUDITORIA*/
            		    }
        		    }    						
        		}
    		}
    		
    		$mensaje['estado'] = 'exito';
    		$mensaje['mensaje'] = $idPartida;
    		
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