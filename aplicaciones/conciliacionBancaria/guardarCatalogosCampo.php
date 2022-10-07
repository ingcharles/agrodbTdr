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
		case "catalogoCampoCabeceraTrama":			
			$idCampoCabeceraTrama = $_POST['idCampoCabeceraTrama'];
			$codigoCatalogoCabeceraTrama = $_POST['codigoCatalogoCabeceraTrama'];
			$nombreCatalogoCabeceraTrama = $_POST['nombreCatalogoCabeceraTrama'];			
		break;
		
		case "catalogoDetalleCabeceraTrama":
			$idCampoDetalleTrama = $_POST['idCampoDetalleTrama'];
			$codigoCatalogoDetalleTrama = $_POST['codigoCatalogoDetalleTrama'];
			$nombreCatalogoDetalleTrama = $_POST['nombreCatalogoDetalleTrama'];
		break;
			
	}
	

	try {
	
		$conexion->ejecutarConsulta("begin;");
	
		switch ($opcion){
			
			case "catalogoCampoCabeceraTrama":
				
				$qCatalogoCampoCabecera = $cb -> verificarCatalogoCampoCabeceraTrama($conexion, $idCampoCabeceraTrama, $codigoCatalogoCabeceraTrama, $nombreCatalogoCabeceraTrama);
				
				if(pg_num_rows($qCatalogoCampoCabecera) > 0){
				
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = "No se puede registrar el campo, el registro ya ha sido ingresado";
				
				}else{
				
					$qIdCatalogoCampoCabeceraTrama = $cb -> guardarCatalogoCampoCabeceraTrama($conexion, $idCampoCabeceraTrama, $codigoCatalogoCabeceraTrama, $nombreCatalogoCabeceraTrama);
					$idCatalogoCampoCabeceraTrama = pg_fetch_result($qIdCatalogoCampoCabeceraTrama, 0, 'id_catalogo_campo_cabecera');
					
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = $cb -> imprimirLineaCatalogoCampoCabecera($idCatalogoCampoCabeceraTrama, $codigoCatalogoCabeceraTrama, $nombreCatalogoCabeceraTrama);
				
				}
				
			break;
						
			case "catalogoDetalleCabeceraTrama":
				
				$qCatalogoCampoDetalle = $cb -> verificarCatalogoCampoDetalleTrama($conexion, $idCampoDetalleTrama, $codigoCatalogoDetalleTrama, $nombreCatalogoDetalleTrama);
				
				if(pg_num_rows($qCatalogoCampoDetalle) > 0){
				
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = "No se puede registrar el campo, el registro ya ha sido ingresado";
				
				}else{
				
					$qIdCatalogoCampoDetalleTrama = $cb -> guardarCatalogoCampoDetalleTrama($conexion, $idCampoDetalleTrama, $codigoCatalogoDetalleTrama, $nombreCatalogoDetalleTrama);
					$idCatalogoCampoDetalleTrama = pg_fetch_result($qIdCatalogoCampoDetalleTrama, 0, 'id_catalogo_campo_detalle');				
			
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = $cb -> imprimirLineaCatalogoCampoDetalle($idCatalogoCampoDetalleTrama, $codigoCatalogoDetalleTrama, $nombreCatalogoDetalleTrama);
						
				}
				
			break;
			
		}

		$conexion->ejecutarConsulta("commit;");
	
		/*switch ($opcion){
			case "catalogoCampoCabeceraTrama":
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = $cb -> imprimirLineaCatalogoCampoCabecera($idCatalogoCampoCabeceraTrama, $codigoCatalogoCabeceraTrama, $nombreCatalogoCabeceraTrama);
			break;
			case "catalogoDetalleCabeceraTrama":
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = $cb -> imprimirLineaCatalogoCampoDetalle($idCatalogoCampoDetalleTrama, $codigoCatalogoDetalleTrama, $nombreCatalogoDetalleTrama);
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