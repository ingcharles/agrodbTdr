<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

try{
	$idSolicitud= ($_POST['idSolicitud']);
	$idTipoOperacion= ($_POST['idTipoOperacion']);
	$aprobado = 0;
	$rechazado = 0;
	$estadoSolicitud = 0;
		
	try {
		$conexion = new Conexion();
		$cr = new ControladorRegistroOperador();
		
		$evaluacionAreas = $cr->obtenerResultadoAreasSolicitud($conexion, $idSolicitud);
		$requerimientos = $cr->listarRequerimientosOperaciones($conexion, $idTipoOperacion);
		$revision[]='';
		
		for($i=0;$i<count($evaluacionAreas);$i++){	
			for($j=0;$j<count($requerimiento);$j++){
				if($evaluacionAreas[$i]['estado'] == 'aprobado' && $evaluacionAreas[$i]['tipoArea'] == $requerimiento[$j]['nombreArea']){
					$revision[$j] = 'aprobado';
				}
			}
		}
		
		$i=0;
		for($j=0;$j<count($revision);$j++){
			if($requerimiento[$j] == 'aprobado'){
				$i=0;
			}else{
				$i=1;
				break;
			}
		}
		
		if($i==0){
			$cr->evaluarSolicitud($conexion, $idSolicitud, 'aprobado');
		}else{
			$cr->evaluarSolicitud($conexion, $idSolicitud, 'rechazado');
		}
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'La solicitud se ha enviado satisfactoriamente';
		
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