<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorConciliacionBancaria.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try {
	$conexion = new Conexion ();
	$cc = new ControladorCatalogos();
	$cb = new ControladorConciliacionBancaria();
		
		$idDocumento = $_POST["idDocumento"];
		$nombreDocumento = $_POST["nombreDocumento"];
		$tipoColumna = $_POST["tipoColumna"];
		$nombreColumna = $_POST["nombreColumna"];
		$sistemaGuiaCamposComparar = $_POST['sistemaGuiaCamposComparar'];
		$datosColumnaGuiaCamposComparar = $_POST['datosColumnaGuiaCamposComparar'];
		$documentoReporteCamposComparar = $_POST['documentoReporteCamposComparar'];
		$datosColumnaDocumentosCamposComparar = $_POST['datosColumnaDocumentosCamposComparar'];
		$actividadEjecutarCamposComparar = $_POST['actividadEjecutarCamposComparar'];		
			
	try {
	
		$conexion->ejecutarConsulta("begin;");
	
		$qVerificarCamposComparar = $cb -> verificarCampoDocumentoCompararProcesoConciliacion ($conexion, $documentoReporteCamposComparar, $sistemaGuiaCamposComparar, $datosColumnaGuiaCamposComparar, $idDocumento, $datosColumnaDocumentosCamposComparar);
		
		if(pg_num_rows($qVerificarCamposComparar) > 0){

			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "No se puede registrar el campo, el registro ya ha sido ingresado";						
			
		}else{
			
			$qIdCampoDocumentoCompararProcesoConciliacion = $cb -> guardarCampoDocumentoCompararProcesoConciliacion($conexion, $documentoReporteCamposComparar, $sistemaGuiaCamposComparar, $datosColumnaGuiaCamposComparar, $idDocumento, $datosColumnaDocumentosCamposComparar, $actividadEjecutarCamposComparar, $tipoColumna);
			$idCampoDocumentoCompararProcesoConciliacion = pg_fetch_result($qIdCampoDocumentoCompararProcesoConciliacion, 0, 'id_campo_comparar_proceso_conciliacion');
			
			$conexion->ejecutarConsulta("commit;");
				
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cb -> imprimirLineaCampoDocumentoCompararProcesoConciliacion($idCampoDocumentoCompararProcesoConciliacion, $sistemaGuiaCamposComparar, $datosColumnaGuiaCamposComparar, $nombreDocumento, $nombreColumna, $actividadEjecutarCamposComparar);
			
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