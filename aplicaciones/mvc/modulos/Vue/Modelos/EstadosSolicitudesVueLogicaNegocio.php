<?php
/**
 * Lógica del negocio de EstadosSolicitudesVueModelo
 *
 * Este archivo se complementa con el archivo EstadosSolicitudesVueControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-09-16
 * @uses EstadosSolicitudesVueLogicaNegocio
 * @package Vue
 * @subpackage Modelos
 */
namespace Agrodb\Vue\Modelos;

use Agrodb\Core\Constantes;
use Agrodb\Vue\Modelos\IModelo;
use Agrodb\Importaciones\Modelos\ImportacionesLogicaNegocio;
use Agrodb\DestinacionAduanera\Modelos\DestinacionAduaneraLogicaNegocio;
use Agrodb\FitosanitarioExportacion\Modelos\FitoExportacionesLogicaNegocio;
use Agrodb\ZoosanitarioExportacion\Modelos\ZooExportacionesLogicaNegocio;
use Agrodb\CertificadoLibreVenta\Modelos\CertificadoClvLogicaNegocio;
use Agrodb\FinancieroAutomatico\Modelos\FinancieroCabeceraLogicaNegocio;
use Agrodb\Financiero\Modelos\OrdenPagoLogicaNegocio;
use Agrodb\RevisionFormularios\Modelos\AsignacionInspectorLogicaNegocio;

class EstadosSolicitudesVueLogicaNegocio implements IModelo{

	private $modeloEstadosSolicitudesVue = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloEstadosSolicitudesVue = new EstadosSolicitudesVueModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new EstadosSolicitudesVueModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdEstadoSolicitudVue() != null && $tablaModelo->getIdEstadoSolicitudVue() > 0){
			return $this->modeloEstadosSolicitudesVue->actualizar($datosBd, $tablaModelo->getIdEstadoSolicitudVue());
		}else{
			unset($datosBd["id_estado_solicitud_vue"]);
			return $this->modeloEstadosSolicitudesVue->guardar($datosBd);
		}
	}

	/**
	 * Borra el registro actual
	 *
	 * @param
	 *        	string Where|array $where
	 * @return int
	 */
	public function borrar($id){
		$this->modeloEstadosSolicitudesVue->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return EstadosSolicitudesVueModelo
	 */
	public function buscar($id){
		return $this->modeloEstadosSolicitudesVue->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloEstadosSolicitudesVue->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloEstadosSolicitudesVue->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarEstadosSolicitudesVue(){
		$consulta = "SELECT * FROM " . $this->modeloEstadosSolicitudesVue->getEsquema() . ". estados_solicitudes_vue";
		return $this->modeloEstadosSolicitudesVue->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Proceso de verificacion de eestado de solicitudes VUE para cambio de estado decreto 68.
	 *
	 * @return array|ResultSet
	 */
	public function procesoGenerarCambioEstadoVueTiempoRepuesta(){
		
		echo Constantes::IN_MSG .'Obtención de registros en estado de subsanacion y pago\n';
		
		$solicitudes = $this->buscarLista("estado_solicitud IN ('verificacionVUE','subsanacion')");

		$fechaActual = new \DateTime();
		
		foreach ($solicitudes as $solicitud){
			
			$idEstadoSolicitudVue = $solicitud['id_estado_solicitud_vue'];
			$estadoSolicitud = $solicitud['estado_solicitud'];
			$tipoSolicitud = $solicitud['tipo_solicitud'];
			$idVue = $solicitud['id_vue'];
			$observacion = 'Cambio de estado a solicitud rechazada por motivo de no ser atendida en estado de pago autorizado o subsanación en base a resolución 0185, decreto 68';
			
			echo Constantes::IN_MSG .'Procesamiento de solicitud '.$idVue.'\n';
			
			$arrayParametros = array(
				'id_estado_solicitud_vue' => $idEstadoSolicitudVue,
				'estado_procesamiento' => 'W'
			);
			
			echo Constantes::IN_MSG .'Actualización de estado a W tabla de verificacion estado de VUE \n';
			
			$this->guardar($arrayParametros);
			
			$fechaSolicitud = new \DateTime($solicitud['fecha_registro']);
			$resultado = $fechaSolicitud->diff($fechaActual);
			$dias = $resultado->days;
			
			if($estadoSolicitud == 'verificacionVUE'){
				$diasTranscurridos = 5;
			}else if($estadoSolicitud == 'subsanacion'){
				$diasTranscurridos = 15;
			}
			
			if($dias > $diasTranscurridos){
				
				$arrayParametros = array(
					'id_vue' => $idVue
				);
				
				$actualizacionDatos = array(
					'estado' => 'rechazado',
					'observacion_eliminacion' => $observacion
				);
				
				echo Constantes::IN_MSG .'Proceso de actualización de solicitud \n';
				
				$ingresoRevisionFormularios = false;
				
				switch ($tipoSolicitud){
					
					case 'Operadores':
						
						$formulario = '101-001-REQ';
						
					break;
					
					case 'Importación':
						
						$formulario = '101-002-REQ';
						
						$lNegocioImportaciones = new ImportacionesLogicaNegocio();
						
						$importacion = $lNegocioImportaciones->buscarLista($arrayParametros);
						
						if(!empty($importacion->current())){
							$ingresoRevisionFormularios = true;
							
							$idSolicitud = $importacion->current()->id_importacion;
							
							$actualizacionDatos += array(
								'id_importacion' => $idSolicitud
							);
							
							$lNegocioImportaciones->actualizar($actualizacionDatos);
						}
						
					break;
					
					case 'DDA':
						
						$formulario = '101-024-REQ';
						
						$lNegocioDestinacionAduanera = new DestinacionAduaneraLogicaNegocio();
						
						$dda = $lNegocioDestinacionAduanera->buscarLista($arrayParametros);
						
						if(!empty($dda->current())){
							$ingresoRevisionFormularios = true;
							
							$idSolicitud = $dda->current()->id_destinacion_aduanera;
							
							$actualizacionDatos += array(
								'id_destinacion_aduanera' => $idSolicitud
							);
							
							$lNegocioDestinacionAduanera->guardar($actualizacionDatos);
						}
						
					break;
					
					case 'Fitosanitario':
						
						$formulario = '101-031-REQ';
						
						$lNegocioFitosanitarioExportacion = new FitoExportacionesLogicaNegocio();
						
						$fitosanitarioExportacion = $lNegocioFitosanitarioExportacion->buscarLista($arrayParametros);
						
						if(!empty($fitosanitarioExportacion->current())){
							$ingresoRevisionFormularios = true;
							
							$idSolicitud = $fitosanitarioExportacion->current()->id_fito_exportacion;
							
							$actualizacionDatos += array(
								'id_fito_exportacion' => $idSolicitud
							);
							
							$lNegocioFitosanitarioExportacion->guardar($actualizacionDatos);
						}
						
					break;
					
					case 'Zoosanitario':
						
						$formulario = '101-008-REQ';
						
						$lNegocioZoosanitarioExportacion = new ZooExportacionesLogicaNegocio();
						
						$zoosanitarioExportacion = $lNegocioZoosanitarioExportacion->buscarLista($arrayParametros);
						
						if(!empty($zoosanitarioExportacion->current())){
							$ingresoRevisionFormularios = true;
							
							$idSolicitud = $zoosanitarioExportacion->current()->id_zoo_exportacion;
							
							$actualizacionDatos += array(
								'id_zoo_exportacion' => $idSolicitud
							);
							
							$lNegocioZoosanitarioExportacion->guardar($actualizacionDatos);
						}
						
					break;
					
					case 'CLV':
						
						$formulario = '101-047-REQ';
						
						$lNegocioCertificadoLibreVenta = new CertificadoClvLogicaNegocio();
						
						$certificadoLibreVenta = $lNegocioCertificadoLibreVenta->buscarLista($arrayParametros);
						
						if(!empty($certificadoLibreVenta->current())){
							$ingresoRevisionFormularios = true;
							
							$idSolicitud = $certificadoLibreVenta->current()->id_clv;
							
							$actualizacionDatos += array(
								'id_clv' => $idSolicitud
							);
							
							$lNegocioCertificadoLibreVenta->guardar($actualizacionDatos);
						}
						
					break;
					
				}
				
				echo Constantes::IN_MSG .'Ingreso de información a esquema de revisión de formularios \n';
				
				if($ingresoRevisionFormularios){
					
					$lNegocioAsignacionInspector = new AsignacionInspectorLogicaNegocio();
					
					$datosRevision = array('identificador_inspector' => 'G.U.I.A',
						'fecha_asignacion' => 'now()',
						'identificador_asignante' => 'G.U.I.A',
						'tipo_solicitud' => $tipoSolicitud,
						'tipo_inspector' => 'Documental',
						'id_operador_tipo_operacion' => 0,
						'id_historial_operacion' => 0,
						'id_solicitud' => $idSolicitud,
						'estado' => 'Documental',
						'fecha_inspeccion' => 'now()',
						'observacion' => $observacion,
						'estado_siguiente' => 'rechazado',
						'orden'=> 1
					);
					
					$lNegocioAsignacionInspector->guardar($datosRevision);
					
				}
				
				if($estadoSolicitud == 'verificacionVUE'){
					
					echo Constantes::IN_MSG .'Ingreso de información a esquema de facturación automático  y financiero\n';
					
					$lNegocioFinancieroAutomatico = new FinancieroCabeceraLogicaNegocio();
					$lNegocioOrdenPago= new OrdenPagoLogicaNegocio();
					
					$ordenesPago = $lNegocioFinancieroAutomatico->buscarLista("id_vue = '$idVue' and tipo_solicitud = '$tipoSolicitud' and estado_factura is null");
					
					if(!empty($ordenesPago->count())){
						foreach ($ordenesPago as $ordenPago) {
							
							$arrayFinancieroAutomatico = array(
								'estado_factura' => 'Atendida',
								'observacion' => $observacion,
								'id_financiero_cabecera' => $ordenPago['id_financiero_cabecera']
							);
							
							$lNegocioFinancieroAutomatico->guardar($arrayFinancieroAutomatico);
							
							$arrayOrdenPago = array(
								'id_pago' => $ordenPago['id_orden_pago'],
								'estado' => '9',
								'estado_sri' => 'FINALIZADO',
								'observacion_eliminacion' => $observacion
							);
							
							$lNegocioOrdenPago->guardar($arrayOrdenPago);
						}
					}
					
				}
				
				echo Constantes::IN_MSG .'Actualización de información a esquema de revisión de solicitudes proceso de guardado VUE\n';
				
				$arrayDatosEstadoVue = array(
					'id_estado_solicitud_vue' => $idEstadoSolicitudVue,
					'cantidad_dia' => '0',
					'estado_solicitud' => 'rechazado',
					'estado_solicitud_anterior' => $estadoSolicitud,
					'observacion' => $observacion
				);
				
				$this->guardar($arrayDatosEstadoVue);
				
				echo Constantes::IN_MSG .'Transmisión de estado rechazado a VUE\n';
				
				$lNegocioSolicitudesAtender = new SolicitudesAtenderLogicaNegocio();
				
				$datosRectificacion = array('formulario'=> $formulario,
					'codigo_procesamiento'=> '310',
					'codigo_verificacion'=> '21',
					'solicitud'=> $idVue,
					'estado'=> 'Por atender',
					'observacion'=> $observacion,
					'fecha'=> 'now()'
				);
				
				$lNegocioSolicitudesAtender->guardar($datosRectificacion);
				
			}else{
				
				echo Constantes::IN_MSG .'Actualización de días en tabla de procesamiento de VUE\n';
				
				$arrayDatosEstadoVue = array(
					'id_estado_solicitud_vue' => $idEstadoSolicitudVue,
					'cantidad_dia' => $dias
				);
				
				$this->guardar($arrayDatosEstadoVue);
				
			}
			
			echo Constantes::IN_MSG .'Actualización de estado a Atendida tabla de verificacion estado de VUE \n';
			
			$arrayParametros = array(
				'id_estado_solicitud_vue' => $idEstadoSolicitudVue,
				'estado_procesamiento' => 'Atendida'
			);
			
			$this->guardar($arrayParametros);
		}
		
	}
}
