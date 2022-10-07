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

	$idMaterialPeligroso = htmlspecialchars ( $_POST ['materialPeligroso'], ENT_NOQUOTES, 'UTF-8' );
	$idLaboratorioMaterialPeligroso = htmlspecialchars ( $_POST['laboratorioNuevo'], ENT_NOQUOTES, 'UTF-8' );
		
	$usuarioReponsable = htmlspecialchars ( $_POST['usuario'], ENT_NOQUOTES, 'UTF-8' );
		
	try {
		$conexion->ejecutarConsulta("begin;");

		$qManejoMaterialPeligroso=$so->buscarManejoMaterialPeligroso($conexion, $idMaterialPeligroso,  $idLaboratorioMaterialPeligroso);

		if(pg_num_rows($qManejoMaterialPeligroso)==0){
			$so->guardarManejoMaterialPeligroso($conexion, $idMaterialPeligroso,  $idLaboratorioMaterialPeligroso, $usuarioReponsable);
			$mensaje ['estado'] = 'exito';
			$mensaje ['mensaje'] = 'Los datos han sido ingresados satisfactoriamente';
		}else{
			$mensaje ['mensaje'] = 'Ya existe el material peligroso asignado al laboratorio y guía';
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