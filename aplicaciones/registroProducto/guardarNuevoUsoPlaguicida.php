<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorRequisitos.php';
    require_once '../../clases/ControladorCatalogos.php';
    require_once '../../clases/ControladorAuditoria.php';
    
    
    $mensaje = array();
    $mensaje['estado'] = 'error';
    $mensaje['mensaje'] = 'Ha ocurrido un error!';
    
    //revisar y hacer q guarde!!! verificar q pa<se parametros
    try{	
        $idProducto = htmlspecialchars ($_POST['idProducto'],ENT_NOQUOTES,'UTF-8');
        $idUsoPlaga = htmlspecialchars ($_POST['plaga'],ENT_NOQUOTES,'UTF-8');
        $plagaComun = htmlspecialchars ($_POST['nombrePlaga'],ENT_NOQUOTES,'UTF-8');
        $plagaCientifico = htmlspecialchars ($_POST['nombreCientificoPlaga'],ENT_NOQUOTES,'UTF-8');
        $idCultivo = htmlspecialchars ($_POST['cultivo'],ENT_NOQUOTES,'UTF-8');
        $cultivoComun = htmlspecialchars ($_POST['nombreCultivo'],ENT_NOQUOTES,'UTF-8');
        $cultivoCientifico = htmlspecialchars ($_POST['nombreCientificoCultivo'],ENT_NOQUOTES,'UTF-8');
        $dosis = htmlspecialchars ($_POST['dosis'],ENT_NOQUOTES,'UTF-8');
    	$unidadDosis = htmlspecialchars ($_POST['unidadMedidaDosis'],ENT_NOQUOTES,'UTF-8');
    	$periodoCarencia = htmlspecialchars ($_POST['periodoCarencia'],ENT_NOQUOTES,'UTF-8');
    	$gastoAgua = htmlspecialchars ($_POST['gastoAgua'],ENT_NOQUOTES,'UTF-8');
    	$unidadAgua = htmlspecialchars ($_POST['unidadMedidaAgua'],ENT_NOQUOTES,'UTF-8');
    	
    	$tipo_aplicacion = ($_SESSION['idAplicacion']);
    	
    	try {
    		$conexion = new Conexion();
    		$cr = new ControladorRequisitos();
    		$ca = new ControladorAuditoria();
    		
    		if(pg_num_rows($cr->buscarUsoPlaguicida($conexion, $idProducto, $idUsoPlaga, $idCultivo))==0){
    		    
    		    $idUso = pg_fetch_result($cr->guardarNuevoUsoPlaguicida($conexion, $idProducto, $idUsoPlaga, $plagaComun, $plagaCientifico, $idCultivo, $cultivoComun, $cultivoCientifico, $dosis, $unidadDosis, $periodoCarencia, $gastoAgua, $unidadAgua), 0, 'id_uso');
    			
    			/*AUDITORIA*/
    			
    		    $qTransaccion = $ca -> buscarTransaccion($conexion, $idProducto, $_SESSION['idAplicacion']);
    			$transaccion = pg_fetch_assoc($qTransaccion);
    			
    			if($transaccion['id_transaccion'] == ''){
    				$qLog = $ca -> guardarLog($conexion,$tipo_aplicacion);
    				$qTransaccion = $ca ->guardarTransaccion($conexion, $idProducto, pg_fetch_result($qLog, 0, 'id_log'));
    			}
    			
    			$ca ->guardarInsert($conexion, pg_fetch_result($qTransaccion, 0, 'id_transaccion'),$_SESSION['usuario'],'El usuario <b>' . $_SESSION['datosUsuario'] . '</b> ha asociado al producto con id '.
    			                                                 $idProducto.' el cultivo '.$cultivoCientifico .' y la plaga ' . $plagaCientifico);
    			
    			/*FIN AUDITORIA*/
    			
    			$mensaje['estado'] = 'exito';
    			$mensaje['mensaje'] = $cr->imprimirLineaUsoPlaguicida($idUso, $idProducto, $plagaComun, $plagaCientifico, $cultivoComun, $cultivoCientifico, $dosis, $unidadDosis, $periodoCarencia, $gastoAgua, $unidadAgua, 'registroProducto');
    		}else{
    			$mensaje['estado'] = 'error';
    			$mensaje['mensaje'] = 'El uso ya ha sido asignado.';
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