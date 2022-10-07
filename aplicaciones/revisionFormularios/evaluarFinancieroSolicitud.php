<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVUE.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';
require_once '../../clases/ControladorFitosanitarioExportacion.php';

try{
	$inspector = htmlspecialchars ($_POST['inspector'],ENT_NOQUOTES,'UTF-8');
	$idSolicitud = ($_POST['idSolicitud']);
	$tipoSolicitud = htmlspecialchars ($_POST['tipoSolicitud'],ENT_NOQUOTES,'UTF-8');
	$tipoInspector = htmlspecialchars ($_POST['tipoInspector'],ENT_NOQUOTES,'UTF-8');
	$resultadoDocumento = htmlspecialchars ($_POST['resultadoFinanciero'],ENT_NOQUOTES,'UTF-8');
	$observacionesDocumento = htmlspecialchars ($_POST['observacionFinanciero'],ENT_NOQUOTES,'UTF-8');
	$idVue = htmlspecialchars($_POST['idVue'],ENT_NOQUOTES,'UTF-8');
	
	$idGrupoSolicitudes = explode(",",$idSolicitud);

	try {
		$conexion = new Conexion();
		$crs = new ControladorRevisionSolicitudesVUE();
		$cVUE = new ControladorVUE();


		$inspectorAsignado= $crs->guardarNuevoInspector($conexion, $inspector, $inspector, $tipoSolicitud, $tipoInspector);
			
		foreach ($idGrupoSolicitudes as $solicitud){
			$crs->guardarGrupo($conexion, $solicitud, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $tipoInspector);
		}
			
		$ordenInspeccion = $crs->buscarSerialOrden($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $tipoInspector);

		//Guarda inspector, calificación y fecha para inspeccion documental
		$crs->guardarDatosInspeccionDocumental($conexion, pg_fetch_result($inspectorAsignado, 0, 'id_grupo'), $inspector, $observacionesDocumento, $resultadoDocumento,pg_fetch_result($ordenInspeccion, 0, 'orden'));

		//Guardar resultado solicitud (cambio de estado)
		switch ($tipoSolicitud){
				
			case 'FitosanitarioExportacion' :
				$cfe = new ControladorFitosanitarioExportacion();

				$cfe->actualizarEstadoFitosanitarioExportacion($conexion, $idSolicitud, $resultadoDocumento, 'pago', $observacionesDocumento);
					
				if($resultadoDocumento =='subsanacion' && $idVue!=''){
					$cVUE->ingresarSolicitudesXatenderGUIA('101-034-REQ','410','21',$idVue, 'Por atender', $observacionesDocumento);
				}

				break;
		}


		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'La operación se ha guardado satisfactoriamente';

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