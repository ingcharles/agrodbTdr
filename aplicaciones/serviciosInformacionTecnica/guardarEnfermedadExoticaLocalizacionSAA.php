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
		$idEnfermedadExotica=$_POST['idEnfermedadExotica'];
		$idZona = $_POST['zona'];
		$nombreZona = $_POST['nombreZona'];
		$idPais = $_POST['pais'];
		$nombrePais = $_POST['nombrePais'];
		$usuarioResponsable = $_POST['usuarioResponsable'];
		$conexion->ejecutarConsulta("begin;");
		$qLocalizacionPaisesZonas=$csit->buscarEnfermedadLocalizacionPaisesZonas($conexion,$idZona,$idPais, $idEnfermedadExotica);
		if(pg_num_rows($qLocalizacionPaisesZonas)==0){
			$idEnfermedadLocalizacion=pg_fetch_row($csit->guardarEnfermedadesLocalizacion($conexion, $idZona, $nombreZona, $idPais, $nombrePais, $idEnfermedadExotica,$usuarioResponsable));
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $csit->imprimirLineaEnfermedadesExoticasLocalizacion($idEnfermedadLocalizacion[0], $nombreZona,$nombrePais,$usuarioResponsable);
		}else{
			$mensaje['mensaje'] ='La zona ('.$nombreZona.') y el país ('.$nombrePais.') ya han sido ingresados previamente.';
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