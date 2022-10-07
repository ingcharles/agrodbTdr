<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';

$catalogo= htmlspecialchars($_POST['idCatalogo'], ENT_NOQUOTES, 'UTF-8');
$subCatalogo = htmlspecialchars ($_POST['cbSubCatalogo'],ENT_NOQUOTES,'UTF-8');
$item = htmlspecialchars ($_POST['idItem'],ENT_NOQUOTES,'UTF-8');
$subitem = $_POST['producto'];

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try {
	
	$conexion = new Conexion();
	$controladorCatalogo = new ControladorAdministrarCatalogos();
	
	try{	
	    
	    $conexion->ejecutarConsulta("begin;");
	    
	    $idSubitem=pg_fetch_row($controladorCatalogo->guardarItemCatalogo($conexion, $item, $subCatalogo));
	    
	    $guardarDetalle="";
	    for($i=0; $i<count($subitem);$i++){
	        $guardarDetalle.= "('".$idSubitem[0] ."','".$subitem[$i] ."'),";
	    }
	    
	    $trim = rtrim($guardarDetalle,",");
	    $controladorCatalogo->guardarDetalleItemCatalogo($conexion,$trim);
	    
	    $conexion->ejecutarConsulta("commit;");
	    
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = "Los datos han sido guardados satisfactoriamente";

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

