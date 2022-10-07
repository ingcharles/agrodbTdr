<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';

$nombre= htmlspecialchars($_POST['txtNombreItem'], ENT_NOQUOTES, 'UTF-8');
$descripcion = htmlspecialchars ($_POST['txtDescripcion'],ENT_NOQUOTES,'UTF-8');
$idItem= $_POST['idItem'];
$idCatalogo= $_POST['idCatalogo'];


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try {
	
	$conexion = new Conexion();
	$cac = new ControladorAdministrarCatalogos();
	
	try{		
		
	    $valor = pg_fetch_row($cac->verificarItemXnombreYid($conexion, $nombre, $idItem));
	    
	    if($valor==0){
	    
		$cac->actualizarItemCatalogo($conexion,$idItem,$nombre,$descripcion);
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = "Los datos han sido guardados satisfactoriamente";
		
	    } else{
	        $mensaje['estado'] = 'vacio';
	        $mensaje['mensaje'] = "Ya se encuentra un ítem registrado con el nombre ($nombre) para este catálogo";
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