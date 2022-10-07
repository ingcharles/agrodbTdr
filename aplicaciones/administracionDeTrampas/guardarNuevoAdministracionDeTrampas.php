<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAdministracionDeTrampas.php';

$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

/*$idAreaTrampa = $_POST['areaTrampa'];
$etapaTrampa = $_POST['etapaTrampa'];
$fechaInstalacion = $_POST['fechaInstalacion'];
$codigoTrampa = $_POST['codigoTrampa'];
$idProvincia = $_POST['provincia'];
$idCanton = $_POST['canton'];
$idParroquia = $_POST['parroquia'];
$coordenadaX = $_POST['coordenadaX'];
$coordenadaY = $_POST['coordenadaY'];
$coordenadaZ = $_POST['coordenadaZ'];
$idLugarInstalacion = $_POST['lugarInstalacion'];
$numeroLugarInstalacion = $_POST['numeroLugarInstalacion'];
$idPlagaMonitoreada = $_POST['plagaMonitoreada'];
$idTipoTrampa = $_POST['tipoTrampa'];
$idTipoAtrayente = $_POST['tipoAtrayente'];
$estadoTrampa = $_POST['estadoTrampa'];
$observacion = $_POST['observacion'];*/



try {
	$conexion = new Conexion ();
	$cc = new ControladorCatalogos();
	$cat = new ControladorAdministracionDeTrampas();
	
	$idAreaTrampa = $_POST['areaTrampa'];
	$etapaTrampa = $_POST['etapaTrampa'];
	$fechaInstalacion = $_POST['fechaInstalacion'];
	//$codigoTrampa = $_POST['codigoTrampa'];
	$idProvincia = $_POST['provincia'];
	$idCanton = $_POST['canton'];
	$idParroquia = $_POST['parroquia'];
	$coordenadaX = $_POST['coordenadaX'];
	$coordenadaY = $_POST['coordenadaY'];
	$coordenadaZ = $_POST['coordenadaZ'];
	$idLugarInstalacion = $_POST['lugarInstalacion'];
	//$numeroLugarInstalacion = ($_POST['numeroLugarInstalacion']== "") ? 'null' : $_POST['numeroLugarInstalacion'];
	$numeroLugarInstalacion = $_POST['numeroLugarInstalacion'];
	$idPlagaMonitoreada = $_POST['plagaMonitoreada'];
	$idTipoTrampa = $_POST['tipoTrampa'];
	$idTipoAtrayente = $_POST['tipoAtrayente'];
	$estadoTrampa = $_POST['estadoTrampa'];
	$observacion = $_POST['observacion'];
	$codigoProgramacionEspecifica = $_POST['codigoProgramacionEspecifica'];	
	$identficadorTecnico = $_POST['identficadorTecnico'];
	
	$opcion = $_POST['opcion'];
	
	try {		
		
		$conexion->ejecutarConsulta("begin;");
		
		switch ($opcion){
		
			case '1':
				$res = $cat -> generarCodigoTrampa($conexion, '%VF-%');
				$documento = pg_fetch_assoc($res);
				$tmp= explode("-", $documento['codigo_trampa']);
				$incremento = end($tmp)+1;
				$codigoTrampa = 'VF-'.str_pad($incremento, 8, "0", STR_PAD_LEFT);
				break;
		
			case '2':
				$res = $cat -> generarCodigoTrampa($conexion, '%MF-%');
				$documento = pg_fetch_assoc($res);
				$tmp= explode("-", $documento['codigo_trampa']);
				$incremento = end($tmp)+1;
				$codigoTrampa = 'MF-'.str_pad($incremento, 8, "0", STR_PAD_LEFT);
				break;
		}
		
		$qAdmistracionTrampa = $cat -> guardarNuevoAdminintracionTrampas($conexion, $codigoTrampa, $idAreaTrampa, $etapaTrampa, $fechaInstalacion, $idProvincia, $idCanton, $idParroquia, $coordenadaX, $coordenadaY, $coordenadaZ, $idLugarInstalacion, $numeroLugarInstalacion, $idPlagaMonitoreada, $idTipoTrampa, $idTipoAtrayente, $estadoTrampa, $observacion, $identficadorTecnico, $codigoProgramacionEspecifica);
		$idAdministracionTrampa = pg_fetch_result($qAdmistracionTrampa, 0, 'id_administracion_trampa');
		
		$cat -> guardarNuevoHistoriaAdminintracionTrampas($conexion, $idAdministracionTrampa, $codigoTrampa, $idAreaTrampa, $etapaTrampa, $fechaInstalacion, $idProvincia, $idCanton, $idParroquia, $coordenadaX, $coordenadaY, $coordenadaZ, $idLugarInstalacion, $numeroLugarInstalacion, $idPlagaMonitoreada, $idTipoTrampa, $idTipoAtrayente, $estadoTrampa, $observacion, $identficadorTecnico, $codigoProgramacionEspecifica);
		
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