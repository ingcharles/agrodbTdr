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
		$tipoDocumentoUtilizarProcesoConciliacion = $_POST['tipoDocumentoUtilizarProcesoConciliacion'];
		$documentoEntradaUtilizarProcesoConciliacion = $_POST['documentoEntradaUtilizarProcesoConciliacion'];	
		$nombreDocumentoEntradaUtilizadoProcesoConciliacion = $_POST['nombreDocumentoEntradaUtilizadoProcesoConciliacion'];
	
	try {
	
		$conexion->ejecutarConsulta("begin;");
		
		$qVerificarDocumentosUtilizar = $cb -> verificarDocumentosUtilizarProcesoConciliacionBancaria ($conexion, $idRegistroProcesoConciliacion, $tipoDocumentoUtilizarProcesoConciliacion, $documentoEntradaUtilizarProcesoConciliacion);
		
		if(pg_num_rows($qVerificarDocumentosUtilizar) > 0){
		
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "No se puede registrar el campo, el registro ya ha sido ingresado";
		
				
		}else{
		
			$qIdDocumentosUtilizarProcesoConciliacion = $cb -> guardarDocumentosUtilizarProcesoConciliacionBancaria ($conexion, $idRegistroProcesoConciliacion, $tipoDocumentoUtilizarProcesoConciliacion, $documentoEntradaUtilizarProcesoConciliacion);
			$idDocumentoUtilizarProcesoConciliacion = pg_fetch_result($qIdDocumentosUtilizarProcesoConciliacion, 0, 'id_documento_proceso_conciliacion');	
		
			$conexion->ejecutarConsulta("commit;");	
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cb -> imprimirListaDocumentosUtilizarProcesoConciliacion($idDocumentoUtilizarProcesoConciliacion, $tipoDocumentoUtilizarProcesoConciliacion, $documentoEntradaUtilizarProcesoConciliacion, $nombreDocumentoEntradaUtilizadoProcesoConciliacion);	
		
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