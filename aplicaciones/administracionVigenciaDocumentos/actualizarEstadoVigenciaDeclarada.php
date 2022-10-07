<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorVigenciaDocumentos.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{

	$idVigenciaDeclarada = $_POST['idVigenciaDeclarada'];
	$estadoRequisito = $_POST['estadoRequisito'];
	
	try {
		
		$conexion = new Conexion();
		$cc = new ControladorCatalogos();
		$cvd = new ControladorVigenciaDocumentos();		
					
		$cvd->actualizarEstadoVigenciaDeclarada($conexion, $idVigenciaDeclarada, $estadoRequisito);
			
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