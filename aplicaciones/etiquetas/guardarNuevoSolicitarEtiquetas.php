 <?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorEtiquetas.php';
require_once '../../clases/ControladorCatalogos.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$conexion = new Conexion();
	$cc = new ControladorCatalogos();
	$cro = new ControladorRegistroOperador();
	$ce = new ControladorEtiquetas();

	//Datos Etiqueta Cebecera
	$numeroSolicitud = htmlspecialchars ($_POST['numeroSolicitud'],ENT_NOQUOTES,'UTF-8');
	$anio=htmlspecialchars ($_POST['anio'],ENT_NOQUOTES,'UTF-8');
	$secuencial = htmlspecialchars ($_POST['secuencial'],ENT_NOQUOTES,'UTF-8');
	$identificadorOperador = htmlspecialchars ($_POST['identificadorOperador'],ENT_NOQUOTES,'UTF-8');
	$qOperador = $cro->buscarOperador($conexion, $identificadorOperador);
	$nombreOperador=pg_fetch_result($qOperador, 0, 'nombre_representante').' '.pg_fetch_result($qOperador, 0, 'apellido_representante');
	$qIdProvincia=$cc->obtenerIdLocalizacion($conexion, pg_fetch_result($qOperador, 0, 'provincia'), 'PROVINCIAS');
	$idProvincia=pg_fetch_result($qIdProvincia, 0, 'id_localizacion');
	$nombreProvincia= pg_fetch_result($qOperador, 0, 'provincia');
	$saldoEtiqueta=htmlspecialchars ($_POST['totalEtiquetas'],ENT_NOQUOTES,'UTF-8');
	
	//Datos Etiqueta Detalle Sitios
	$idSitio=$_POST['aIdSitio'];
	$codigoSitio=$_POST['aCodigoSitio'];
	$idArea=$_POST['aIdArea'];
	$codigoArea=$_POST['aCodigoArea'];
	$totalEtiqueta=$_POST['aNumeroEtiquetas'];
	
	try {
	
		if($identificadorOperador!=''){
			$conexion->ejecutarConsulta("begin;");
			$qSolicitudEtiqueta=$ce->guardarNuevaSolicitudEtiquetas($conexion, $numeroSolicitud, $anio, $secuencial, $identificadorOperador, $nombreOperador, $idProvincia, $nombreProvincia, $saldoEtiqueta,'Enviado');
			$idEtiqueta=pg_fetch_result($qSolicitudEtiqueta, 0, 'id_etiqueta');
			
			for($i=0; $i<count($idSitio);$i++){
				$ce->guardarNuevaSolicitudEtiquetasSitios($conexion, $idEtiqueta, $idSitio[$i], $codigoSitio[$i],$idArea[$i], $codigoArea[$i], $totalEtiqueta[$i]);
			}
			
			$conexion->ejecutarConsulta("commit;");
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = "Los datos han sido guardado satisfactoriamente";
		
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "Su sesión expiró, por favor ingrese nuevamente al sistema";
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