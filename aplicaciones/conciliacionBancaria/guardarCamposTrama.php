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

	$opcion = $_POST['opcion'];
	
	switch ($opcion){
		case "campoCabeceraTrama":
			$idTrama = $_POST['idTrama'];
			$idCabeceraTrama = $_POST['idCabeceraTrama'];
			$nombreCampoCabeceraTrama = $_POST['nombreCampoCabeceraTrama'];
			$posicionInicialCampoCabeceraTrama = $_POST['posicionInicialCampoCabeceraTrama'];
			$posicionFinalCampoCabeceraTrama = $_POST['posicionFinalCampoCabeceraTrama'];
			$longitudSegmentoCampoCabeceraTrama = $_POST['longitudSegmentoCampoCabeceraTrama'];
			$tipoCampoCabeceraTrama = $_POST['tipoCampoCabeceraTrama'];				
		break;
		
		case "campoDetalleTrama":
			$idDetalleTrama = $_POST['idDetalleTrama'];
			$nombreCampoDetalleTrama = $_POST['nombreCampoDetalleTrama'];
			$posicionInicialCampoDetalleTrama = $_POST['posicionInicialCampoDetalleTrama'];
			$posicionFinalCampoDetalleTrama = $_POST['posicionFinalCampoDetalleTrama'];
			$longitudSegmentoCampoDetalleTrama = $_POST['longitudSegmentoCampoDetalleTrama'];
			$tipoCampoDetalleTrama = $_POST['tipoCampoDetalleTrama'];
			$campoFormaPagoCampoCabeceraTrama = $_POST['campoFormaPagoCampoCabeceraTrama'];
		break;
	}
	

	try {
	
		$conexion->ejecutarConsulta("begin;");
	
		switch ($opcion){
			
			case "campoCabeceraTrama":
				
				$qVerificarCampoCabeceraTrama = $cb -> verificarCampoCabeceraTrama ($conexion, $nombreCampoCabeceraTrama, $posicionInicialCampoCabeceraTrama, $posicionFinalCampoCabeceraTrama);
				
				if(pg_num_rows($qVerificarCampoCabeceraTrama) > 0){
				
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = "No se puede registrar el campo, el registro ya ha sido ingresado";
				
				}else{
				
					$qIdCampoCabeceraTrama = $cb -> guardarCampoCabeceraTrama($conexion, $idCabeceraTrama, $nombreCampoCabeceraTrama, $posicionInicialCampoCabeceraTrama, $posicionFinalCampoCabeceraTrama, $longitudSegmentoCampoCabeceraTrama, $tipoCampoCabeceraTrama);
					$idCampoCabeceraTrama = pg_fetch_result($qIdCampoCabeceraTrama, 0, 'id_campo_cabecera');
				
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = $cb -> imprimirLineaCampoCabecera($idCampoCabeceraTrama, $nombreCampoCabeceraTrama, $longitudSegmentoCampoCabeceraTrama, $posicionInicialCampoCabeceraTrama, $posicionFinalCampoCabeceraTrama, $tipoCampoCabeceraTrama, $idTrama);
					
				}
				
			break;	
					
			case "campoDetalleTrama":
				
				$qVerificarCampoDetalleTrama = $cb -> verificarCampoDetalleTrama ($conexion, $nombreCampoDetalleTrama, $posicionInicialCampoDetalleTrama, $posicionFinalCampoDetalleTrama, $campoFormaPagoCampoCabeceraTrama);
				
				if(pg_num_rows($qVerificarCampoDetalleTrama) > 0){
				
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = "No se puede registrar el campo, el registro ya ha sido ingresado";
				
				}else{
				
					$qIdCampoDetalleTrama = $cb -> guardarCampoDetalleTrama($conexion, $idDetalleTrama, $nombreCampoDetalleTrama, $posicionInicialCampoDetalleTrama, $posicionFinalCampoDetalleTrama, $longitudSegmentoCampoDetalleTrama, $tipoCampoDetalleTrama, $campoFormaPagoCampoCabeceraTrama);
					$idCampoDetalleTrama = pg_fetch_result($qIdCampoDetalleTrama, 0, 'id_campo_detalle');
					
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = $cb -> imprimirLineaCampoDetalle($idCampoDetalleTrama, $nombreCampoDetalleTrama, $longitudSegmentoCampoDetalleTrama, $posicionInicialCampoDetalleTrama, $posicionFinalCampoDetalleTrama, $tipoCampoDetalleTrama, $idCampoCabeceraTrama);
				}
				
			break;
			
		}

		$conexion->ejecutarConsulta("commit;");
	
		/*switch ($opcion){
			case "campoCabeceraTrama":
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = $cb -> imprimirLineaCampoCabecera($idCampoCabeceraTrama, $nombreCampoCabeceraTrama, $longitudSegmentoCampoCabeceraTrama, $posicionInicialCampoCabeceraTrama, $posicionFinalCampoCabeceraTrama, $tipoCampoCabeceraTrama, $idTrama);
			break;
			case "campoDetalleTrama":
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = $cb -> imprimirLineaCampoDetalle($idCampoDetalleTrama, $nombreCampoDetalleTrama, $longitudSegmentoCampoDetalleTrama, $posicionInicialCampoDetalleTrama, $posicionFinalCampoDetalleTrama, $tipoCampoDetalleTrama, $idCampoCabeceraTrama);
			break;
		}*/
				
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