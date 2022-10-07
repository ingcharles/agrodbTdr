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
	$valorTiempoVigencia = $_POST['valorTiempoVigencia'];
	$tipoTiempoVigencia = $_POST['tipoTiempoVigencia'];
	$observacionVigencia = $_POST['observacionVigencia'];
	$identificadorModificacion = $_SESSION['usuario'];
	
	try {
		
		$conexion = new Conexion();
		$cc = new ControladorCatalogos();
		$cvd = new ControladorVigenciaDocumentos();

		$vigenciaDeclarada = $cvd->buscarVigenciaDeclarada($conexion, $idVigenciaDocumento, $valorTiempoVigencia, $tipoTiempoVigencia);
		
		if(pg_num_rows($vigenciaDeclarada) == 0){			
			
			$idVigenciaDeclarada = pg_fetch_result($cvd->guardarVigenciaDeclarada($conexion, $idVigenciaDocumento, $valorTiempoVigencia, $tipoTiempoVigencia, $observacionVigencia, $identificadorModificacion), 0, 'id_vigencia_declarada');
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cvd->imprimirLineaDeclararVigenciaDocumento($idVigenciaDocumento, $idVigenciaDeclarada, $valorTiempoVigencia, $tipoTiempoVigencia, $observacionVigencia, 'activo');
			
		}else{

			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "El tiempo de vigencia ya ha sido declarado.";
			
		}
		
		
		$conexion->desconectar();
		echo json_encode($mensaje);
		} catch (Exception $ex){
			pg_close($conexion);
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "Error al ejecutar sentencia".$ex;
			echo json_encode($mensaje);
		}
		} catch (Exception $ex) {
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'Error de conexión a la base de datos'.$ex;
			echo json_encode($mensaje);
		}
?>