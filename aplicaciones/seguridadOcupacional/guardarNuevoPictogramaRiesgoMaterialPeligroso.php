<?php
session_start ();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorSeguridadOcupacional.php';

$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {
	$conexion = new Conexion ();
	$so = new ControladorSeguridadOcupacional ();

	$opcion = htmlspecialchars ( $_POST['opcion'], ENT_NOQUOTES, 'UTF-8' );
	$idClasificacionRiesgoMaterialPeligroso = htmlspecialchars ( $_POST ['idPictogramaRiesgoMaterialPeligroso'], ENT_NOQUOTES, 'UTF-8' );
	$nombreClasificacionRiesgoMaterialPeligroso = htmlspecialchars ( $_POST ['nombrePictogramaUno'], ENT_NOQUOTES, 'UTF-8' );
	$rutaClasificacionRiesgoMaterialPeligroso = htmlspecialchars ( $_POST['archivo'], ENT_NOQUOTES, 'UTF-8' );

	try {
		$conexion->ejecutarConsulta("begin;");

		if($opcion=='Nuevo'){
			if(pg_num_rows($so->buscarClasificacionRiesgoMaterialPeligrosoGuardar($conexion,mb_strtoupper($nombreClasificacionRiesgoMaterialPeligroso)))==0){
				$so->guardarClasificacionRiesgoMaterialPeligroso($conexion, mb_strtoupper($nombreClasificacionRiesgoMaterialPeligroso), $rutaClasificacionRiesgoMaterialPeligroso);
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Los datos han sido ingresados satisfactoriamente';
			}else{
				$mensaje['mensaje'] = 'Ya existe el pictograma de riesgo con el nombre '.$nombreClasificacionRiesgoMaterialPeligroso;
			}
		}

		if($opcion=='Actualizar'){
			$so->actualizarClasificacionRiesgoMaterialPeligroso($conexion, $idClasificacionRiesgoMaterialPeligroso, mb_strtoupper($nombreClasificacionRiesgoMaterialPeligroso), $rutaClasificacionRiesgoMaterialPeligroso);
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
		}
			
		$conexion->ejecutarConsulta("commit;");
			
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