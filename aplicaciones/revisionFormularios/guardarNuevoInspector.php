<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';

//Controladores por solicitud

require_once '../../clases/ControladorClv.php';
require_once '../../clases/ControladorImportaciones.php';
require_once '../../clases/ControladorFitosanitario.php';
require_once '../../clases/ControladorTramitesInocuidad.php';
require_once '../../clases/ControladorCertificadoCalidad.php';
require_once '../../clases/ControladorDestinacionAduanera.php';
require_once '../../clases/ControladorZoosanitarioExportacion.php';
require_once '../../clases/ControladorImportacionesFertilizantes.php';
require_once '../../clases/ControladorMercanciasSinValorComercial.php';
require_once '../../clases/ControladorTransitoInternacional.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	//ids de solicitudes
	$solicitud = array_keys(array_count_values($_POST['id'])); 
	$idCoordinador = $_POST['idCoordinador'];
	$identificadorInspector = $_POST['inspector'];
	$tipoSolicitud = $_POST['tipoSolicitud'];
	$procesoRevision = $_POST['procesoRevision'];
	$formularioGeneral = $_POST['formularioGeneral'];
	
	try {
		$conexion = new Conexion();
		$crs = new ControladorRevisionSolicitudesVUE();
		
		switch ($procesoRevision){
			case 'Documental' :
				$estado = 'asignadoDocumental';
				break;
		
			case 'Financiero' : 
				$estado = 'asignadoFinanciero';
				break;
		
			case 'Técnico' :
				$estado = 'asignadoInspeccion';
				break;

		}
		
		//Solicitudes - Inspectores
		for ($i = 0; $i < count ($solicitud); $i++) {
		    
		    $inspectorAsignado = $crs->buscarInspectorAsignadoCoordinador($conexion, $solicitud[$i], $identificadorInspector, $formularioGeneral, $procesoRevision);
		    
		    if(pg_num_rows($inspectorAsignado)==0){
		        $inspectorAsignado= $crs->guardarNuevoInspectorCoordinador($conexion, $identificadorInspector, $idCoordinador, $formularioGeneral,  $solicitud[$i], $procesoRevision);
		    }

			switch ($tipoSolicitud){

            case 'operadoresSV':
            case 'operadoresSA':
            case 'operadoresAGR':
            case 'operadoresFER':
            case 'operadoresPEC':
            case 'operadoresLT':
            case 'operadoresAI':
			case 'operadoresALM':
					$cr = new ControladorRegistroOperador();
					
					$operacion = pg_fetch_assoc($cr->abrirOperacionXid($conexion, $solicitud[$i]));
					$idOperadorTipoOperacion = $operacion['id_operador_tipo_operacion'];
					
					$qHistorialOperacion = $cr->obtenerMaximoIdentificadorHistoricoOperacion($conexion, $idOperadorTipoOperacion);
					$historialOperacion = pg_fetch_assoc($qHistorialOperacion);
					
					//$cr->enviarOperacion($conexion, $solicitud[$i], $estado);
					$cr->actualizarEstadoPorOperadorTipoOperacionHistorial($conexion, $idOperadorTipoOperacion, $historialOperacion['id_historial_operacion'], $estado);
			break;
			
			case 'Importación':
				$ci = new ControladorImportaciones();
				$ci->enviarImportacion($conexion, $solicitud[$i], $estado);
			break;
			
			case 'importacionMuestras' :
				$cif = new ControladorImportacionesFertilizantes();
				$cif->enviarImportacionFertilizantes($conexion, $solicitud[$i], $estado);
			break;
			
			case 'DDA' :
				$cdda = new ControladorDestinacionAduanera();
				$cdda->enviarDDA($conexion, $solicitud[$i], $estado);
			break;
		
			case 'Fitosanitario' :
				$cf = new ControladorFitosanitario();
				$cf->enviarFito($conexion, $solicitud[$i], $estado);
			break;
			
			case 'Zoosanitario' :
				$cz = new ControladorZoosanitarioExportacion();
				$cz->enviarZoo($conexion, $solicitud[$i], $estado);
			break;
							
			case 'CLV' :
				$clv = new ControladorClv();
				$clv->enviarClv($conexion, $solicitud[$i], $estado);
			break;
		
			case 'tramitesInocuidad' :
				$cti = new ControladorTramitesInocuidad();
				$cti->actualizarEstadoTramite($conexion, $solicitud[$i], $estado);
				$fechaDespacho = date('Y-m-d h:m:s');
				$cti-> guardarSeguimientoTramite($conexion, $solicitud[$i], $idCoordinador, $fechaDespacho, 'Asignación a inspector '. $identificadorInspector);
			 break;
			 
			case 'certificadoCalidad':
				$cca = new ControladorCertificadoCalidad();
				$cca->actualizarEstadoLote($conexion,  $solicitud[$i], $estado);
			break;
			
			case 'mercanciasSinValorComercialExportacion':
			case 'mercanciasSinValorComercialImportacion':
				$cme = new ControladorMercanciasSinValorComercial();
				$cme->actualizarEstadoMercanciaSV($conexion, $estado, $solicitud[$i]);
			break;
			
			case 'transitoInternacional' :
			    $cti = new ControladorTransitoInternacional();
			    $cti->enviarTransitoInternacional($conexion, $solicitud[$i], $estado);
			break;
			    
			default :
				break;
			}
		}

		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
		
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