<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAdministracionDeTrampas.php';

$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {
	$conexion = new Conexion ();
	$cc = new ControladorCatalogos();
	$cat = new ControladorAdministracionDeTrampas();

	$idAdministracionTrampa = $_POST['idAdministracionTrampa'];
	
	$estadoTrampa = $_POST['estadoTrampa'];
	$observacion = $_POST['observacion'];
	
	$coordenadax = $_POST['coordenadax'];
	$coordenaday = $_POST['coordenaday'];
	$coordenadaz = $_POST['coordenadaz'];
	$idTipoAtrayente = $_POST['id_tipo_atrayente'];

	$identificadorTecnico = $_SESSION['usuario'];
	
	$qAdministracionTrampa = $cat->obtenerAdministracionTrampaPorIdAdministracion($conexion, $idAdministracionTrampa) ;
	$administracionTrampa = pg_fetch_assoc($qAdministracionTrampa);

try {

	$conexion->ejecutarConsulta("begin;");
	
	if(pg_num_rows($cat->buscarEstadoObservacionTrampas($conexion, $idAdministracionTrampa, $estadoTrampa, $observacion))==0){
		$cat -> modificarNuevoAdminintracionTrampas($conexion, $idAdministracionTrampa, $estadoTrampa, $observacion,$coordenadax,$coordenaday,$coordenadaz,$idTipoAtrayente);
		$cat -> guardarNuevoHistoriaAdminintracionTrampas($conexion, $idAdministracionTrampa, $administracionTrampa['codigo_trampa'], $administracionTrampa['id_area_trampa'], $administracionTrampa['etapa_trampa'], $administracionTrampa['fecha_instalacion_trampa'], $administracionTrampa['id_provincia'], $administracionTrampa['id_canton'], $administracionTrampa['id_parroquia'], $administracionTrampa['coordenadax'], $administracionTrampa['coordenaday'], $administracionTrampa['coordenadaz'], $administracionTrampa['id_lugar_instalacion'], $numeroLugarInstalacion = ($administracionTrampa['numero_lugar_instalacion']== "") ? 'null' : $administracionTrampa['numero_lugar_instalacion'], $administracionTrampa['id_plaga'], $administracionTrampa['id_tipo_trampa'], $administracionTrampa['id_tipo_atrayente'], $estadoTrampa, $observacion, $identificadorTecnico, $administracionTrampa['codigo_programacion_especifica']);
	}
	
	$conexion->ejecutarConsulta("commit;");
	
	$mensaje['estado'] = 'exito';
	$mensaje['mensaje'] = "Los datos se han guardado correctamente";
	
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