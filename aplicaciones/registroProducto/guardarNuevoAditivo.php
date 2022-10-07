<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorCatalogos.php';
    
    $mensaje = array();
    $mensaje['estado'] = 'error';
    $mensaje['mensaje'] = 'Ha ocurrido un error!';
    
    
    try{    
    	
    	$idArea = trim(htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8'));
    	$nombreComun = trim(htmlspecialchars ($_POST['nombreComun'],ENT_NOQUOTES,'UTF-8'));
    	$nombreQuimico = trim(htmlspecialchars ($_POST['nombreQuimico'],ENT_NOQUOTES,'UTF-8'));
    	$cas = trim(htmlspecialchars ($_POST['cas'],ENT_NOQUOTES,'UTF-8'));
    	$formulaQuimica = trim(htmlspecialchars ($_POST['formulaQuimica'],ENT_NOQUOTES,'UTF-8'));
    	$grupoQuimico = trim(htmlspecialchars ($_POST['grupoQuimico'],ENT_NOQUOTES,'UTF-8'));
    			
    	try {
    		$conexion = new Conexion();
    		$cc = new ControladorCatalogos();
    				
    		$cc -> guardarNuevoAditivo($conexion, $idArea, $nombreComun, $nombreQuimico, $cas, $formulaQuimica, $grupoQuimico, $_SESSION['usuario']);
    		
    		$mensaje['estado'] = 'exito';
    		$mensaje['mensaje'] = 'Los datos han sido creados satisfactoriamente';
    				
    		$conexion->desconectar();
    		
    		echo json_encode($mensaje);
    		
		}catch (Exception $ex){
			pg_close($conexion);
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "Error al ejecutar sentencia";
			echo json_encode($mensaje);
		}
		
	}catch (Exception $ex) {
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = 'Error de conexión a la base de datos';
		echo json_encode($mensaje);
	}
?>