<?php

/**
 * Lógica del negocio de PeriodoCronogramaVacacionesModelo
 *
 * Este archivo se complementa con el archivo PeriodoCronogramaVacacionesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-10-22
 * @uses    PeriodoCronogramaVacacionesLogicaNegocio
 * @package VacacionesPermisos
 * @subpackage Modelos
 */

namespace Agrodb\VacacionesPermisos\Modelos;

use Agrodb\VacacionesPermisos\Modelos\IModelo;
use Agrodb\Core\Excepciones\GuardarExcepcion;
use Agrodb\Core\JasperReport;

class PeriodoCronogramaVacacionesLogicaNegocio implements IModelo
{

	private $modeloPeriodoCronogramaVacaciones = null;


	/**
	 * Constructor
	 * 
	 * @retorna void
	 */
	public function __construct()
	{
		$this->modeloPeriodoCronogramaVacaciones = new PeriodoCronogramaVacacionesModelo();
	}

	/**
	 * Guarda el registro actual
	 * @param array $datos
	 * @return int
	 */
	public function guardar(array $datos)
	{
		$tablaModelo = new PeriodoCronogramaVacacionesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdPeriodoCronogramaVacacion() != null && $tablaModelo->getIdPeriodoCronogramaVacacion() > 0) {
			return $this->modeloPeriodoCronogramaVacaciones->actualizar($datosBd, $tablaModelo->getIdPeriodoCronogramaVacacion());
		} else {
			unset($datosBd["id_periodo_cronograma_vacacion"]);
			return $this->modeloPeriodoCronogramaVacaciones->guardar($datosBd);
		}
	}

	/**
	 * Actualiza el estado de los periodos validados
	 * @param array $datos
	 * @return int
	 */
	public function guardarValidarPeriodo(array $datos)
	{
		//print_r($datos);

		try {


			$procesoIngreso = $this->modeloPeriodoCronogramaVacaciones->getAdapter()
				->getDriver()
				->getConnection();
			$procesoIngreso->beginTransaction();


			$statement = $this->modeloPeriodoCronogramaVacaciones->getAdapter()
				->getDriver()
				->createStatement();

			$idCronogramaVacacion = $datos['id_cronograma_vacacion'];

			foreach ($datos['hCerrarPeriodo'] as $key => $value) {
				// echo ($datos['hNumeroPeriodo'][$key] );
				if (isset($datos['hCerrarPeriodo'][$key])) {


					$numeroPeriodo = $key;
					$estadoPeriodo = $datos['hCerrarPeriodo'][$key];

					$datosCronogramaVacacion = array(
						'numero_periodo' => $numeroPeriodo, 'estado_registro' => $estadoPeriodo
					);

					$sqlActualizar = $this->modeloPeriodoCronogramaVacaciones->actualizarSql('periodo_cronograma_vacaciones', $this->modeloPeriodoCronogramaVacaciones->getEsquema());
					$sqlActualizar->set($datosCronogramaVacacion);
					$sqlActualizar->where(array('id_cronograma_vacacion' => $idCronogramaVacacion, 'numero_periodo' => $numeroPeriodo, 'estado_reprogramacion' => null));
					$sqlActualizar->prepareStatement($this->modeloPeriodoCronogramaVacaciones->getAdapter(), $statement);
					$statement->execute();
				}
			}


			$procesoIngreso->commit();

			return $idCronogramaVacacion;
		} catch (GuardarExcepcion $ex) {
			$procesoIngreso->rollback();
			throw new \Exception($ex->getMessage());
		}
	}


	/**
	 * Borra el registro actual
	 * @param string Where|array $where
	 * @return int
	 */
	public function borrar($id)
	{
		$this->modeloPeriodoCronogramaVacaciones->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param  int $id
	 * @return PeriodoCronogramaVacacionesModelo
	 */
	public function buscar($id)
	{
		return $this->modeloPeriodoCronogramaVacaciones->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo()
	{
		return $this->modeloPeriodoCronogramaVacaciones->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null)
	{
		return $this->modeloPeriodoCronogramaVacaciones->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarPeriodoCronogramaVacaciones()
	{
		$consulta = "SELECT * FROM " . $this->modeloPeriodoCronogramaVacaciones->getEsquema() . ". periodo_cronograma_vacaciones";
		return $this->modeloPeriodoCronogramaVacaciones->ejecutarSqlNativo($consulta);
	}

	public function obtenerNumeroPeriodos($numeroPeriodos = null, $habilitar = false)
	{

		$readOnly = "disabled";
		if ($habilitar) {
			$readOnly = "";
		}

		$arrayEstados = array(
			'1' => 'Un periodo', '2' => 'Dos periodos', '3' => 'Tres periodos', '4' => 'Cuatro periodos'
		);
		$comboNumeroPeriodos = '<select ' . $readOnly . ' name="numero_periodos" id="numero_periodos"><option value="">Seleccionar...</option>';
		foreach ($arrayEstados as $llaveEstado => $valorEstado) {
			if ($numeroPeriodos == $llaveEstado) {
				$comboNumeroPeriodos .= '<option value="' . $llaveEstado . '" selected>' . $valorEstado . '</option>';
			} else {
				$comboNumeroPeriodos .= '<option value="' . $llaveEstado . '" >' . $valorEstado . '</option>';
			}
		}
		$comboNumeroPeriodos .= '</select>';
		return $comboNumeroPeriodos;
	}


	public function guardarReprogramacionPeriodo(array $datos)
	{


		try {

			$proceso = $this->modeloPeriodoCronogramaVacaciones->getAdapter()
				->getDriver()
				->getConnection();
			if (!$proceso->beginTransaction()) {
				throw new \Exception('No se pudo iniciar la transacción: Guardar destinatario');
			}

			$idCronogramaVacacion = $datos['id_cronograma_vacacion'];
			$anioCronogramaVacacion = $datos['anio_cronograma_vacacion'];

			$statement = $this->modeloPeriodoCronogramaVacaciones->getAdapter()->getDriver()->createStatement();

			$nombreArchivo = $idCronogramaVacacion . "_" . $anioCronogramaVacacion . '_' . date('Y-m-d_H-i-s');

			$rutaArchivoPdf = VACA_PER_DOC_ADJ . 'pdf/reprogramacion/' . $nombreArchivo . '.pdf';

			$statementUpdate = $this->modeloPeriodoCronogramaVacaciones->getAdapter()->getDriver()->createStatement();

			// Actualizo el campo ultima_reprogramacion todos los registros a false con codigo id_cronograma_vacaciones
			$arrayParametrosUltimaReprogramacion = array(
				'ultima_reprogramacion' => false
			);

			$sqlActualizar = $this->modeloPeriodoCronogramaVacaciones->actualizarSql('periodo_cronograma_vacaciones', $this->modeloPeriodoCronogramaVacaciones->getEsquema());
			$sqlActualizar->set($arrayParametrosUltimaReprogramacion);
			$sqlActualizar->where(array('id_cronograma_vacacion' => $idCronogramaVacacion));
			$sqlActualizar->prepareStatement($this->modeloPeriodoCronogramaVacaciones->getAdapter(), $statementUpdate);
			$statementUpdate->execute();


			foreach ($datos['hReprogramado'] as $key => $value) {
				// print_r($datos['hReprogramado'][$key]);
				// print_r($datos['hIdPeriodoCronogramaVacacion'][$key]);
				$idPeriodoCronogramaVacacion = $datos['hIdPeriodoCronogramaVacacion'][$key];
				$arrayDatosDetalle = array(
								'id_cronograma_vacacion' => (int) $idCronogramaVacacion,
								'numero_periodo' => $datos['hNumeroPeriodo'][$key],
								'fecha_inicio' => $datos['hFechaInicio'][$key],
								'fecha_fin' => $datos['hFechaFin'][$key],
								'total_dias' => $datos['hNumeroDias'][$key],
								'estado_reprogramacion' => $datos['hReprogramado'][$key],
								'ultima_reprogramacion' => true,
								'ruta_archivo_reprogramacion' => $rutaArchivoPdf
				);
				// print_r($arrayDatosDetalle);
			
			// for ($i = 0; $i < count($datos['hReprogramado']); $i++) {

			// 	if ($datos['hReprogramado'][$i] == 'Si') {
			// 		$idPeriodoCronogramaVacacion = $datos['hIdPeriodoCronogramaVacacion'][$i];
			// 		$arrayDatosDetalle = array(
			// 			'id_cronograma_vacacion' => (int) $idCronogramaVacacion,
			// 			'numero_periodo' => $datos['hNumeroPeriodo'][$i],
			// 			'fecha_inicio' => $datos['hFechaInicio'][$i],
			// 			'fecha_fin' => $datos['hFechaFin'][$i],
			// 			'total_dias' => $datos['hNumeroDias'][$i],
			// 			'estado_reprogramacion' => $datos['hReprogramado'][$i],
			// 			'ultima_reprogramacion' => true
			// 		);

					// $statementUpdate = $this->modeloPeriodoCronogramaVacaciones->getAdapter()
					// 	->getDriver()
					// 	->createStatement();
					
					$statementUpdate = $this->modeloPeriodoCronogramaVacaciones->getAdapter()->getDriver()->createStatement();

					// Actualizo el estado a Inactivo de los periodos anteriores(periodos reprogramados)
					$arrayParametrosCertificado = array(
						'estado_registro' => 'Inactivo',
						'ultima_reprogramacion' => true
					);

					$sqlActualizar = $this->modeloPeriodoCronogramaVacaciones->actualizarSql('periodo_cronograma_vacaciones', $this->modeloPeriodoCronogramaVacaciones->getEsquema());
					$sqlActualizar->set($arrayParametrosCertificado);
					$sqlActualizar->where(array('id_periodo_cronograma_vacacion' => $idPeriodoCronogramaVacacion));
					$sqlActualizar->prepareStatement($this->modeloPeriodoCronogramaVacaciones->getAdapter(), $statementUpdate);
					$statementUpdate->execute();

					// Inserto los nuevos periodos reprogramados
					$sqlInsertar = $this->modeloPeriodoCronogramaVacaciones->guardarSql('periodo_cronograma_vacaciones', $this->modeloPeriodoCronogramaVacaciones->getEsquema());
					$sqlInsertar->columns(array_keys($arrayDatosDetalle));
					$sqlInsertar->values($arrayDatosDetalle, $sqlInsertar::VALUES_MERGE);
					$sqlInsertar->prepareStatement($this->modeloPeriodoCronogramaVacaciones->getAdapter(), $statement);
					$statement->execute();
				}
			
				$proceso->commit();
			// Genero el Pdf de Reprogramación
			 
			try {
				//$generarPdf = true;
				$jasper = new JasperReport();
				$datosReporte = array();

				$ruta = VACA_PER_URL_TCPDF . 'pdf/reprogramacion/';

				if (!file_exists($ruta)) {
					mkdir($ruta, 0777, true);
				}

				$datosReporte = array(
					'rutaReporte' => 'VacacionesPermisos/vistas/reportes/reprogramacionVacacion.jasper',
					'rutaSalidaReporte' => 'VacacionesPermisos/archivos/pdf/reprogramacion/' . $nombreArchivo,
					'tipoSalidaReporte' => array('pdf'),
					'parametrosReporte' => array(
						'idCronogramaVacacion' => (int)$idCronogramaVacacion,
						'rutaFondoCertificado' => RUTA_IMG_GENE . 'fondoCertificado.png'
					),
					'conexionBase' => 'SI'
				);
				//print_r($datosReporte);
				$jasper->generarArchivo($datosReporte);

				// // Actualizo la ruta del archivo de reprogramacion
				// $arrayParametrosRutaArchivo = array(
				// 	'ruta_archivo_reprogramacion' => $rutaArchivoPdf,
				// );

				// $statementUpdate = $this->modeloPeriodoCronogramaVacaciones->getAdapter()->getDriver()->createStatement();

				// foreach ($datos['hReprogramado'] as $key => $value) {
				// 	// print_r($datos['hReprogramado'][$key]);
				// 	// print_r($datos['hIdPeriodoCronogramaVacacion'][$key]);
					
				// 		$idPeriodoCronogramaVacacion = $datos['hIdPeriodoCronogramaVacacion'][$key];
				// 		$sqlActualizar = $this->modeloPeriodoCronogramaVacaciones->actualizarSql('periodo_cronograma_vacaciones', $this->modeloPeriodoCronogramaVacaciones->getEsquema());
				// 		$sqlActualizar->set($arrayParametrosRutaArchivo);
				// 		$sqlActualizar->where(array('id_periodo_cronograma_vacacion' => $idPeriodoCronogramaVacacion));
				// 		$sqlActualizar->prepareStatement($this->modeloPeriodoCronogramaVacaciones->getAdapter(), $statementUpdate);
				// 		$statementUpdate->execute();
					
				// }
			} catch (GuardarExcepcion $ex) {
				//$generarPdf = false;
				throw new \Exception($ex->getMessage());
			}

			
			return true;
		} catch (\Exception $ex) {
			$proceso->rollback();
			throw new \Exception($ex->getMessage());
			return 0;
		}
	}
}
