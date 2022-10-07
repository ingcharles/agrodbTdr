<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAdministrarCaracteristicas.php';

$etiqueta= htmlspecialchars($_POST['txtEtiqueta'], ENT_NOQUOTES, 'UTF-8');
$tipo= htmlspecialchars ($_POST['cbTipoElemento'],ENT_NOQUOTES,'UTF-8');
$idCatalogo=  htmlspecialchars ($_POST['cbCatalogo'],ENT_NOQUOTES,'UTF-8');
$idElemento=  htmlspecialchars ($_POST['idItem'],ENT_NOQUOTES,'UTF-8');
$formulario = $_POST['idFormulario'];
$producto = $_POST['idProducto'];

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try {
	
	$conexion = new Conexion();
	$cac = new controladorAdministrarCaracteristicas();
	
	try{		
	    
	    $valor = pg_fetch_row($cac->verificarCaracteristicaXnombreYformulario($conexion,$etiqueta,$producto, $formulario));
		
	    if($valor==0){
	    
		$cac->actualizarCaracteristica($conexion,$etiqueta,$tipo,$idCatalogo,$idElemento);
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = "Los datos han sido guardados satisfactoriamente";
		
	    } else{
	        $mensaje['estado'] = 'vacio';
	        $mensaje['mensaje'] = "Ya se encuentra una característica agregada con el nombre ($etiqueta) ";
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