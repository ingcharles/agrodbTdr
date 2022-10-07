<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorServiciosLinea.php';

$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {
	$conexion = new Conexion ();
	$csl = new ControladorServiciosLinea();
	
	$idRutaTransporte = htmlspecialchars ( $_POST['idRutaTransporte'], ENT_NOQUOTES, 'UTF-8' );
	$identificadorResponsable = htmlspecialchars ( $_POST['identificadorResponsable'], ENT_NOQUOTES, 'UTF-8' );
	$nombreRuta = htmlspecialchars ( $_POST['nombreRuta'], ENT_NOQUOTES, 'UTF-8' );
	$idProvincia = htmlspecialchars ( $_POST['provincia'], ENT_NOQUOTES, 'UTF-8' );
	$nombreProvincia = htmlspecialchars ( $_POST['nombreProvincia'], ENT_NOQUOTES, 'UTF-8' );
	$idCanton = htmlspecialchars ( $_POST['canton'], ENT_NOQUOTES, 'UTF-8' );
	$nombreCanton = htmlspecialchars ( $_POST['nombreCanton'], ENT_NOQUOTES, 'UTF-8' );
	$idOficina = htmlspecialchars ( $_POST['oficina'], ENT_NOQUOTES, 'UTF-8' );
	$nombreOficina = htmlspecialchars ( $_POST['nombreOficina'], ENT_NOQUOTES, 'UTF-8' );
	$nombreSector = htmlspecialchars ( $_POST['sector'], ENT_NOQUOTES, 'UTF-8' );
	$conductor = htmlspecialchars ( $_POST['conductor'], ENT_NOQUOTES, 'UTF-8' );
	$telefono = htmlspecialchars ( $_POST['telefono'], ENT_NOQUOTES, 'UTF-8' );
	$administradorGrupo = htmlspecialchars ( $_POST['administradorGrupo'], ENT_NOQUOTES, 'UTF-8' );
	$telefonoAdministrador = htmlspecialchars ( $_POST['telefonoAdministrador'], ENT_NOQUOTES, 'UTF-8' );
	$capacidadVehiculo = htmlspecialchars ( $_POST['capacidadVehiculo'], ENT_NOQUOTES, 'UTF-8' );
	$numeroPasajeros = htmlspecialchars ( $_POST['numeroPasajeros'], ENT_NOQUOTES, 'UTF-8' );
	$placaVehiculo = htmlspecialchars ( $_POST['placaVehiculo'], ENT_NOQUOTES, 'UTF-8' );
	$descripcionVehiculo = htmlspecialchars ( $_POST['descripcionVehiculo'], ENT_NOQUOTES, 'UTF-8' );
	$estado=htmlspecialchars ( $_POST['estadoRuta'], ENT_NOQUOTES, 'UTF-8' );


	try {
		$conexion->ejecutarConsulta("begin;");
		$csl->actualizarRutasTransporte($conexion, $identificadorResponsable, $nombreRuta, $idProvincia, $nombreProvincia, $idCanton, $nombreCanton, $idOficina, $nombreOficina, $nombreSector, $conductor, $telefono,$idRutaTransporte,
				$administradorGrupo, $telefonoAdministrador, $capacidadVehiculo, $numeroPasajeros, $placaVehiculo, $descripcionVehiculo, $estado);
		$mensaje ['estado'] = 'exito';
		$mensaje ['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
		$conexion->ejecutarConsulta("commit;");

	} catch (Exception $ex) {
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['mensaje'] = $ex->getMessage();
		$mensaje['error'] = $conexion->mensajeError.$ex;
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