<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorServiciosInformacionTecnica.php';

$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {
	$conexion = new Conexion ();
	$csit = new ControladorServiciosInformacionTecnica();
	$cc = new ControladorCatalogos();
	try {		
		$idZona = $_POST['idZona'];
		$idPais = $_POST['pais'];
		$usuarioResponsable =$_POST['usuarioResponsable'];
		$conexion->ejecutarConsulta("begin;");
		if(pg_num_rows($csit->buscarRegistroPaisesZonas($conexion,$idZona, $idPais))== 0){
			$qPais=pg_fetch_row($cc->obtenerNombreLocalizacion($conexion, $idPais));
			$idZonaPais=pg_fetch_row($cc->guardarZonasPaises($conexion, $idZona, $qPais[2],$idPais,$usuarioResponsable));
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $csit->imprimirLineaPaisesZona($idZonaPais[0], $qPais[2],$usuarioResponsable);
		}else{
			$mensaje ['estado'] = 'error';
			$mensaje['mensaje'] = 'El Pais ya han sido ingresada para la Zona.';
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