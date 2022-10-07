<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorSeguridadOcupacional.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$conexion = new Conexion();
	$so = new ControladorSeguridadOcupacional ();

	$idClasificacionRiesgoMaterialPeligroso = htmlspecialchars ($_POST['clasificacionRiesgoMaterialPeligroso'],ENT_NOQUOTES,'UTF-8');
	$idMaterialPeligroso=htmlspecialchars ($_POST['idMaterialPeligrosoDos'],ENT_NOQUOTES,'UTF-8');
	$qClasificacionRiesgoMaterialPeligroso=$so->buscarClasificacionRiesgoMaterialPeligroso($conexion, $idClasificacionRiesgoMaterialPeligroso);
	$filaClasificacionRiesgoMaterialPeligroso=pg_fetch_assoc($qClasificacionRiesgoMaterialPeligroso);

	try {

		$conexion->ejecutarConsulta("begin;");

		if(pg_num_rows($so->buscarMaterialPeligrosoClasificacionRiesgo($conexion, $idMaterialPeligroso,$idClasificacionRiesgoMaterialPeligroso))==0){
			$idMaterialPeligrosoClasificacionRiesgo = pg_fetch_row($so -> guardarMaterialPeligrosoClasificacionRiesgo($conexion,$idMaterialPeligroso,$idClasificacionRiesgoMaterialPeligroso ));
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $so->imprimirLineaMaterialPeligrosoClasificacionRiesgo($idMaterialPeligrosoClasificacionRiesgo[0], $filaClasificacionRiesgoMaterialPeligroso['nombre_clasificacion_riesgo_material_peligroso'], $filaClasificacionRiesgoMaterialPeligroso['ruta_img_clasificacion_riesgo_material_peligroso']);
		}else{
			$mensaje['mensaje'] = 'El pictograma ya ha sido asignado al material peligroso.';
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