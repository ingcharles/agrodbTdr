<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';

$idCatalogo= htmlspecialchars($_POST['txtIdCatalogo'], ENT_NOQUOTES, 'UTF-8');
$nombre = htmlspecialchars ($_POST['txtNombreCatalogo'],ENT_NOQUOTES,'UTF-8');
$codigo = $_POST['txtCodigo'];

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try {
	
	$conexion = new Conexion();
	$cac = new ControladorAdministrarCatalogos();
	
	try{		
	    $valor=pg_fetch_row($cac->obtenerCatalogoXnombre($conexion, $nombre,$idCatalogo));
	    
	    if($valor==0){
	       
    		$valor2=pg_fetch_row($cac->obtenerCodigoCatalogo($conexion, $codigo,$idCatalogo));
    		
    		if($valor2==0){    		    
    		    $cac->actualizarNombreCatalogo($conexion,$idCatalogo,$nombre,$codigo);    		
    		    
    		    $mensaje['estado'] = 'exito';
    		    $mensaje['mensaje'] = "Los datos han sido guardados satisfactoriamente";
    		} else{
    		    $mensaje['estado'] = 'fallo';
    		    $mensaje['mensaje'] = "Ya se encuentra un catálogo registrado con el código ($codigo)";
    		}
    		
	    }  else{
	        $mensaje['estado'] = 'fallo';
	        $mensaje['mensaje'] = "Ya se encuentra un catálogo registrado con el nombre ($nombre)";
	    }

	} catch (Exception $ex) {
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['mensaje'] = $ex->getMessage();
		$mensaje['error'] = $conexion->mensajeError;
	} finally {
		$conexion->desconectar();
	}
} catch (Exception $ex) {
	$mensaje['mensaje'] = $ex->getMessage();
	$mensaje['error'] = $conexion->mensajeError;
} finally {
	echo json_encode($mensaje);
}



?>

