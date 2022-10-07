<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorReformaPresupuestaria.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	$identificador = $_POST['identificador'];
	$observaciones = $_POST['observaciones'];
	$elementosImportacionPapPac = $_POST['id'];
	
	try {
		$conexion = new Conexion();
		$ca = new ControladorAreas();
		$crp = new ControladorReformaPresupuestaria();

		$idAprobadorDGAF = pg_fetch_result($ca->buscarResponsableSubproceso($conexion,'DGAF' ), 0, 'identificador');
					
		if ($identificador != ''){
			if($idAprobadorDGAF == $identificador){
					$conexion->ejecutarConsulta("begin;");
					
					for ($i = 0; $i < count ($elementosImportacionPapPac); $i++) {
						$crp -> cerrarImportacionPapPac($conexion, $elementosImportacionPapPac[$i], $identificador);
						$crp -> cerrarImportacionPlanificacionAnual($conexion, $elementosImportacionPapPac[$i]);
						$crp -> cerrarImportacionPlanificacionAnualTemporal($conexion, $elementosImportacionPapPac[$i]);
						$crp -> cerrarImportacionPresupuesto($conexion, $elementosImportacionPapPac[$i]);
						$crp -> cerrarImportacionPresupuestoTemporal($conexion, $elementosImportacionPapPac[$i]);
					}
					
					$conexion->ejecutarConsulta("commit;");
					
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'Los registros han sido desactivados satisfactoriamente';
					
			}else{
				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = 'Solamente la Directora General Administrativa Financiera puede ejecutar el proceso.';
			}					
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "Su sesión expiró, por favor ingrese nuevamente al sistema";
		}

		$conexion->desconectar();
		echo json_encode($mensaje);
	
	} catch (Exception $ex){
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['mensaje'] = $ex->getMessage();
		$mensaje['error'] = $conexion->mensajeError;
		$conexion->desconectar();
	}/* finally {
		$conexion->desconectar();
	}*/
	
} catch (Exception $ex) {
	$mensaje['mensaje'] = $ex->getMessage();
	$mensaje['error'] = $conexion->mensajeError;
	$conexion->desconectar();
} /*finally {
	echo json_encode($mensaje);
}*/
?>