<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try {
	
	$conexion = new Conexion();
	$cac = new ControladorAdministrarCatalogos();
	
	$nombre= htmlspecialchars ($_POST['txtNombreCatalogo'],ENT_NOQUOTES,'UTF-8');
	$item = $_POST['txtItem'];
	$descripcion = $_POST['txtDescripcion'];
	$idCatalogo = $_POST['txtIdCatalogo'];
	$estadoCatalogo = $_POST['estadoCatalogo'];
	
	try{		
		
		$conexion->ejecutarConsulta("begin");
		
		$valor = pg_fetch_row($cac->obtenerItemXnombre($conexion, $item, $idCatalogo));
		
		if($valor==0){
		
    		$guardarDetalle.= "('".$item ."','".$descripcion ."',1,".$idCatalogo.")";
    		
    		$idItem=pg_fetch_row($cac->guardarDetalle($conexion,$guardarDetalle));		
    		$mensaje['estado'] = 'exito';    		
    		$mensaje['mensaje'] = $cac->imprimirItem($conexion,$idItem[0],'1',$item,$idCatalogo,'1',$estadoCatalogo );
		
		} else{
		    $mensaje['estado'] = 'vacio';
		    $mensaje['mensaje'] = "Ya se encuentra un ítem registrado con el nombre ($item) para este catálogo";
		}
		
		$conexion->ejecutarConsulta("commit");

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