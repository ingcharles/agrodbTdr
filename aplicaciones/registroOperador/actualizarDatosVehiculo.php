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
	$cc = new ControladorCatalogos();	
	 	
	$marca = $_POST['marca'];
	$modelo = $_POST['modelo'];
	$tipo = $_POST['tipo'];
	$color = $_POST['color'];
	$clase = $_POST['clase'];
	$placa = strtoupper($_POST['placa']);
	$tipoTanque = $_POST['tipoTanque'];
	$anio = $_POST['anio'];
	$capacidadInstalada = $_POST['capacidadInstalada'];
	$codigoUnidadMedida = $_POST['unidadMedidaVehiculo'];
		
	$idDatoVehiculo = $_POST['idDatoVehiculo'];
	$idArea = $_POST['idArea'];
	$idTipoOperacion = $_POST['idTipoOperacion'];
	$horaInicioRecoleccion = $_POST['horaInicioRecoleccion'];
	$horaFinRecoleccion = $_POST['horaFinRecoleccion'];
	
	if(isset($_POST['carnicos'])){
	    $tipoContenedor = $_POST['tipoContenedor'];
	    $caracteristicaContenedor = $_POST['caracteristicaContenedor'];
	    $servicio = $_POST['servicio'];
	    $horaInicioRecoleccion = '';
	    $horaFinRecoleccion = '';
	}else{
	    $horaInicioRecoleccion = $_POST['horaInicioRecoleccion'];
	    $horaFinRecoleccion = $_POST['horaFinRecoleccion'];
	    $tipoContenedor = '';
	    $caracteristicaContenedor = '';
	    $servicio = '';
	}
	
	
	
	try {
	
		$conexion->ejecutarConsulta("begin;");	
		
		$cro->actualizarDatosVehiculoXIdAreaXidTipoOperacion($conexion, $idDatoVehiculo, $idArea, $idTipoOperacion, $capacidadInstalada, $codigoUnidadMedida, $horaInicioRecoleccion, $horaFinRecoleccion,$tipoContenedor,$caracteristicaContenedor,$servicio);
		
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