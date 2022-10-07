<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorFitosanitarioExportacion.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
				
		
	$identificadorExportador = $_POST['identificadorExportador'];
	$razonSocial = $_POST['razonSocial'];
	$idTipoProducto = $_POST['idTipoProducto'];
	$idSubtipoProducto = $_POST['idSubtipoProducto'];
	$idProducto = $_POST['idProducto'];
	$idPais = $_POST['idPais'];
	$fechaInicioSancion = $_POST['fechaInicioSancion'];
	$fechaFinSancion = $_POST['fechaFinSancion'];
	$motivoSancion = $_POST['motivoSancion'];
	$observacionSancion = $_POST['observacionSancion'];

	$nombreProducto = $_POST['nombreProducto'];
	$nombrePais = $_POST['nombrePais'];
	
	$estadoSancion = 'estado';

	try {
		$conexion = new Conexion();
		$cfe = new ControladorFitosanitarioExportacion();
		
		$qSanciones = $cfe->guardarSancionesCFE($conexion, $identificadorExportador, $razonSocial, $idTipoProducto, $idSubtipoProducto, $idProducto, $nombreProducto, $idPais, $fechaInicioSancion, $fechaFinSancion, $motivoSancion, 'activo', $observacionSancion, $nombrePais);

			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = "Los datos se han guardad correctamente";
			
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