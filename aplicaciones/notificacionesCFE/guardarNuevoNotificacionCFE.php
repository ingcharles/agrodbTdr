<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorFitosanitarioExportacion.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
				
		
		$numeroNotificacion = $_POST['numeroNotificacion'];
		$fechaNotificacion = $_POST['fechaNotificacion'];
		$motivoNotificacion = $_POST ['motivoNotificacion'];
		$observacionNotificacion = $_POST['observacionNotificacion'];
		$numeroCFE = $_POST['numeroCFE'];
		$identificadorExportador = $_POST['idExportador'];
		$razonSocial = $_POST['razonSocial'];
		$pais = $_POST['paisDestino'];
		$idTipoProducto = $_POST['tipoProductoExportador'];
		$idSubtipoProducto = $_POST['subtipoProductoExportador'];
		$idProducto = $_POST['productoExportador'];
		
		$nombreProducto = $_POST['nombreProducto'];
		$idPaisDestino = $_POST['idPaisDestino'];


	try {
		$conexion = new Conexion();
		$cfe = new ControladorFitosanitarioExportacion();
		
		
		///TODO:VALIDAR SI SE PUEDE TENER VARIAS NOtIFICACIONES PARA EL MISMO EXPORTADOR EN LA MISMA SOLICITUD
		
		$qNotificaciones = $cfe->guardarNotificacionesCFE($conexion, $numeroNotificacion, $fechaNotificacion, $motivoNotificacion, $observacionNotificacion, $numeroCFE, $identificadorExportador, $razonSocial, $pais, 'activo', $idTipoProducto, $idSubtipoProducto, $idProducto, $nombreProducto, $idPaisDestino);

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