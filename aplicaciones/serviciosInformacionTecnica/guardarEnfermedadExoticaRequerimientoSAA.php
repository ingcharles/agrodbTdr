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
		$tipoRequerimiento = $_POST['tipoRequerimiento'];
		$nombreTipoRequerimiento = $_POST['nombreTipoRequerimiento'];
		$idElementoRevision = $_POST['elementoRevision'];
		$nombreElementoRevision = $_POST['nombreElementoRevision'];
		$usuarioResponsable = $_POST['usuarioResponsable'];
		$seleccionarProducto = true;
		$conexion->ejecutarConsulta("begin;");
		$qRequerimiento=$csit->buscarEnfermedadRequerimientoTipoRevision($conexion,$tipoRequerimiento,$idElementoRevision, $idEnfermedadExotica);
		if(pg_num_rows($qRequerimiento)==0){
			$idEnfermedadRequerimiento=pg_fetch_row($csit->guardarEnfermedadesRequerimiento($conexion, $tipoRequerimiento, $nombreTipoRequerimiento, $idElementoRevision, $nombreElementoRevision, $idEnfermedadExotica,$usuarioResponsable));
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $csit->imprimirLineaEnfermedadesExoticasRequerimiento($idEnfermedadRequerimiento[0], $nombreTipoRequerimiento, $nombreElementoRevision,$usuarioResponsable);
		}else{
			$mensaje['mensaje'] ='La tipo ('.$nombreTipoRequerimiento.') y el elemento ('.$nombreElementoRevision.') del requerimiento ya han sido ingresados previamente.';
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