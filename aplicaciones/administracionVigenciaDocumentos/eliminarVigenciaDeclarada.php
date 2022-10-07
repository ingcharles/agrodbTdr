<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorVigenciaDocumentos.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{

	$idVigenciaDocumento = $_POST['idVigenciaDocumento'];
	$idVigenciaDeclarada = $_POST['idVigenciaDeclarada'];
	$valorTiempoVigencia = $_POST['valorTiempoVigencia'];
	$tipoTiempoVigencia = $_POST['tipoTiempoVigencia'];
	
	try {
		
		$conexion = new Conexion();
		$cc = new ControladorCatalogos();
		$cvd = new ControladorVigenciaDocumentos();		
					
		$cvd->eliminarVigenciaDeclarada($conexion, $idVigenciaDeclarada);
			
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = $idVigenciaDeclarada;		
		
		$conexion->desconectar();
		echo json_encode($mensaje);
		} catch (Exception $ex){
			pg_close($conexion);
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "Error al ejecutar sentencia";
			echo json_encode($mensaje);
		}
		} catch (Exception $ex) {
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'Error de conexión a la base de datos';
			echo json_encode($mensaje);
		}
?>