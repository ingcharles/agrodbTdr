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
	$observacionVigencia = $_POST['observacionVigencia'];
	$identificador_modificacion = $_SESSION['usuario'];
	
	try {
		
		$conexion = new Conexion();
		$cc = new ControladorCatalogos();
		$cvd = new ControladorVigenciaDocumentos();	
		
		$verificarVigenciaDeclarada = $cvd->verificarVigenciaDeclarada($conexion, $idVigenciaDeclarada, $idVigenciaDocumento, $valorTiempoVigencia, $tipoTiempoVigencia, $observacionVigencia);		
		
		if(pg_num_rows($verificarVigenciaDeclarada) == 0){			
			
			$vigenciaDeclarada = $cvd->buscarVigenciaDeclarada($conexion, $idVigenciaDeclarada, $valorTiempoVigencia, $tipoTiempoVigencia);
			
			if(pg_num_rows($vigenciaDeclarada) == 0){
				
				$cvd->actualizarVigenciaDeclarada($conexion, $idVigenciaDeclarada, $valorTiempoVigencia, $tipoTiempoVigencia, $observacionVigencia, $identificador_modificacion);
				
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';				
				
			}else{
				
				$vigenciaDeclaradaObservacion = $cvd->buscarVigenciaDeclaradaObservacion($conexion, $idVigenciaDeclarada, $observacionVigencia);
				
				if(pg_num_rows($vigenciaDeclaradaObservacion) == 0){
					
					$cvd->actualizarVigenciaDeclaradaObservacion($conexion, $idVigenciaDeclarada, $observacionVigencia);
					
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
					
				}else{				
					$mensaje['estado'] = 'error';
					$mensaje['mensaje'] = "El tiempo de vigencia ya ha sido declarado.";
				}
				
			}
			
		}else{			
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
			
		}		
			
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