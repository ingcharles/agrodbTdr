<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorConciliacionBancaria.php';

$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {
	$conexion = new Conexion ();
	$cc = new ControladorCatalogos();
	$cb = new ControladorConciliacionBancaria();
		
		$idDocumento = $_POST['idDocumento'];
		$nombreCampoDocumento = $_POST['nombreCampoDocumento'];
		$posicionCampoDocumento = $_POST['posicionCampoDocumento'];
		$tipoCampoDocumento = $_POST['tipoCampoDocumento'];	

	try {
	
		$conexion->ejecutarConsulta("begin;");
		
			$qVerificarCampoDocumento = $cb -> verificarCampoDocumento ($conexion, $nombreCampoDocumento, $posicionCampoDocumento);
				
			if(pg_num_rows($qVerificarCampoDocumento) > 0){
			
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = "No se puede registrar el campo, el registro ya ha sido ingresado";
			
			}else{
	
				$qIdCampoDocumento = $cb -> guardarCampoDocumento($conexion, $idDocumento, $nombreCampoDocumento, $posicionCampoDocumento, $tipoCampoDocumento);
				$idCampoDocumento = pg_fetch_result($qIdCampoDocumento, 0, 'id_campo_documento');
			
				$conexion->ejecutarConsulta("commit;");
					
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = $cb -> imprimirLineaCampoDocumento($idCampoDocumento, $nombreCampoDocumento, $posicionCampoDocumento, $tipoCampoDocumento);
			
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