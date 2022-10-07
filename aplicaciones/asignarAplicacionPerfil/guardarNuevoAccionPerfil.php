<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicacionesPerfiles.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$conexion = new Conexion();
	$cap = new ControladorAplicacionesPerfiles();
	
	$idPerfil=htmlspecialchars ($_POST['idPerfil'],ENT_NOQUOTES,'UTF-8');
	$idOpcion = htmlspecialchars ($_POST['idOpcion'],ENT_NOQUOTES,'UTF-8');
	$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
	$idAccion = htmlspecialchars ($_POST['idAccion'],ENT_NOQUOTES,'UTF-8');
	$accion = htmlspecialchars ($_POST['accion'],ENT_NOQUOTES,'UTF-8');
	$idAplicacion = htmlspecialchars ($_POST['idAplicacion'],ENT_NOQUOTES,'UTF-8');

	try {
		
		if(pg_num_rows($cap->buscarAccionPerfil($conexion, $idAccion, $idPerfil))==0){
			$conexion->ejecutarConsulta("begin;");
			$qDatos=pg_fetch_row($cap->guardarNuevoAccionPerfil($conexion, $idPerfil, $idAccion));
			$conexion->ejecutarConsulta("commit;");
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cap->imprimirLineaAccionesPerfil($qDatos[0], $qDatos[1], $opcion, $accion);
				
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "La acción seleccionada ya está asignada al perfil.";
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