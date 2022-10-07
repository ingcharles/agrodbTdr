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
		
		$idRegistroProcesoConciliacion = $_POST['idRegistroProcesoConciliacion'];
		$entidadBancariaUtilizarProcesoConciliacion = $_POST['entidadBancariaUtilizarProcesoConciliacion'];
		$nombreEntidadBancariaUtilizarProcesoConciliacion = $_POST['nombreEntidadBancariaUtilizarProcesoConciliacion'];		
		
	try {
	
		$conexion->ejecutarConsulta("begin;");
	
		$qVerificarBancosUtilizar = $cb -> verificarBancosUtilizarProcesoConciliacionBancaria ($conexion, $idRegistroProcesoConciliacion, $entidadBancariaUtilizarProcesoConciliacion);
		
		if(pg_num_rows($qVerificarBancosUtilizar) > 0){
		
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "No se puede registrar el campo, el registro ya ha sido ingresado";
		
			
		}else{

			$qIdBancosUtilizarProcesoConciliacion = $cb -> guardarBancosUtilizarProcesoConciliacionBancaria ($conexion, $idRegistroProcesoConciliacion, $entidadBancariaUtilizarProcesoConciliacion);
			$idBancoUtilizarProcesoConciliacion = pg_fetch_result($qIdBancosUtilizarProcesoConciliacion, 0, 'id_banco_proceso_conciliacion');
			
			$conexion->ejecutarConsulta("commit;");	
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cb -> imprimirListaBancosProcesoConciliacion ($idBancoUtilizarProcesoConciliacion, $nombreEntidadBancariaUtilizarProcesoConciliacion);	

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