<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';

$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {

	$conexion = new Conexion();
	$cro = new ControladorRegistroOperador();
	 	
	$capacidadInstalada = $_POST['capacidadInstalada'];
	$codigoUnidadMedida = $_POST['unidadMedida'];
	$numeroTrabajadores = $_POST['numeroTrabajadores'];
	$laboratorio = $_POST['laboratorio'];
	$numeroProveedores = $_POST['numeroProveedores'];
	
	$idCentroAcopio = $_POST['idCentroAcopio'];
	$idArea = $_POST['idArea'];
	$idTipoOperacion = $_POST['idTipoOperacion'];
	$perteneceMag = $_POST['perteneceMag'];
	$horaRecoleccionManiana = $_POST['horaRecoleccionManiana'];
	$horaRecoleccionTarde = $_POST['horaRecoleccionTarde'];
	
	try {
	
		$conexion->ejecutarConsulta("begin;");	
		
		$cro->actualizarDatosCentroAcopioXidAreaXidTipoOperacion($conexion, $idCentroAcopio, $idArea, $idTipoOperacion, $capacidadInstalada, $codigoUnidadMedida, $numeroTrabajadores, $laboratorio, $numeroProveedores, $horaRecoleccionManiana, $horaRecoleccionTarde, $perteneceMag);
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = "Los datos han sido actualizados satisfactoriamente";
		
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
			$mensaje['error'] = $conexion->mensajeError.$ex;
		} finally {
			echo json_encode($mensaje);
		}
?>