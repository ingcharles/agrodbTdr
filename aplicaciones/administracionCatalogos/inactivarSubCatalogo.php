<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$idCatalogoPadre = htmlspecialchars($_POST['idCatalogoPadre'], ENT_NOQUOTES, 'UTF-8');
$idItemPadre = htmlspecialchars($_POST['idItemPadre'], ENT_NOQUOTES, 'UTF-8');
$idCatalogoHijo = htmlspecialchars($_POST['idCatalogoHijo'], ENT_NOQUOTES, 'UTF-8');
$nivel = htmlspecialchars($_POST['nivel'], ENT_NOQUOTES, 'UTF-8');
$idSubitemCatalogoPadre = $_POST['idSubitemCatalogoPadre'];
$identificador = $_SESSION['usuario'];

try{
	
	try {
		$conexion = new Conexion();
		$cac = new ControladorAdministrarCatalogos();
		
		$conexion->ejecutarConsulta("begin;");
		
		$cantidadSubItems = $cac->obtenerSubCatalogosPorIdentificador($conexion, $idCatalogoPadre, $idCatalogoHijo, $idItemPadre, $nivel, $idSubitemCatalogoPadre);
		
		if(pg_num_rows($cantidadSubItems) == 0){
		    
		    $cac->inactivarSubCatalogo($conexion, $idCatalogoPadre, $idCatalogoHijo, $idItemPadre, $nivel, $idSubitemCatalogoPadre, $identificador);
		    
		    $mensaje['estado'] = 'exito';
		    $mensaje['mensaje'] = $idCatalogoPadre .$idCatalogoHijo.$idItemPadre;
		}else{
		    $mensaje['mensaje'] = "Por favor eliminar todos los subitem para proceder con la eliminación";
        }
		
		
		
		$conexion->ejecutarConsulta("commit;");
		
		
		
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