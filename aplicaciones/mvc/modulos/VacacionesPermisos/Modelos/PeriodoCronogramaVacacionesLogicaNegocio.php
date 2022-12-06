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
	public function guardarValidarPeriodo(Array $datos)
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
			// echo "<pre>";
			// print_r($datos);
			// echo "</pre>";
			foreach ($datos['hCerrarPeriodo'] as $key => $value) {
				// echo ($datos['hNumeroPeriodo'][$key] );
				if(isset($datos['hCerrarPeriodo'][$key])){

					
					$numeroPeriodo = $key;
					$estadoPeriodo = $datos['hCerrarPeriodo'][$key];

					$datosCronogramaVacacion = array('numero_periodo' => $numeroPeriodo
													, 'estado_registro' => $estadoPeriodo);

					$sqlActualizar = $this->modeloPeriodoCronogramaVacaciones->actualizarSql('periodo_cronograma_vacaciones', $this->modeloPeriodoCronogramaVacaciones->getEsquema());
					$sqlActualizar->set($datosCronogramaVacacion);
					$sqlActualizar->where(array('id_cronograma_vacacion' => $idCronogramaVacacion, 'numero_periodo' => $numeroPeriodo,'estado_reprogramacion' => null));
					$sqlActualizar->prepareStatement($this->modeloPeriodoCronogramaVacaciones->getAdapter(), $statement);
					$statement->execute();

				}

			}
			for ($i = 0; $i < count($datos['hCerrarPeriodo']); $i++) {
				
				// if(isset($datos['hCerrarPeriodo'][$i]) && $datos['hCerrarPeriodo'][$i] == "Cerrado" ){

					
				// 	$numeroPeriodo = $datos['hNumeroPeriodo'][$i];
				// 	$estadoPeriodo = $datos['hCerrarPeriodo'][$i];

				// 	$datosCronogramaVacacion = array('numero_periodo' => $numeroPeriodo
				// 									, 'estado_registro' => $estadoPeriodo);

				// 	$sqlActualizar = $this->modeloPeriodoCronogramaVacaciones->actualizarSql('periodo_cronograma_vacaciones', $this->modeloPeriodoCronogramaVacaciones->getEsquema());
				// 	$sqlActualizar->set($datosCronogramaVacacion);
				// 	$sqlActualizar->where(array('id_cronograma_vacacion' => $idCronogramaVacacion, 'numero_periodo' => $numeroPeriodo,'estado_reprogramacion' => null));
				// 	$sqlActualizar->prepareStatement($this->modeloPeriodoCronogramaVacaciones->getAdapter(), $statement);
				// 	$statement->execute();

				// }
				
			}

			$procesoIngreso->commit();

			return $idCronogramaVacacion;
		} catch (GuardarExcepcion $ex) {
			$procesoIngreso->rollback();
			throw new \Exception($ex->getMessage());
		}

		/*$tablaModelo = new PeriodoCronogramaVacacionesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdPeriodoCronogramaVacacion() != null && $tablaModelo->getIdPeriodoCronogramaVacacion() > 0) {
		return $this->modeloPeriodoCronogramaVacaciones->actualizar($datosBd, $tablaModelo->getIdPeriodoCronogramaVacacion());
		} else {
		unset($datosBd["id_periodo_cronograma_vacacion"]);
		return $this->modeloPeriodoCronogramaVacaciones->guardar($datosBd);
		}*/
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

			$statement = $this->modeloPeriodoCronogramaVacaciones->getAdapter()->getDriver()->createStatement();

			$statementUpdate = $this->modeloPeriodoCronogramaVacaciones->getAdapter()->getDriver()->createStatement();

			for ($i = 0; $i < count($datos['hReprogramado']); $i++) {

				if ($datos['hReprogramado'][$i] == 'Si') {
					$idPeriodoCronogramaVacacion = $datos['hIdPeriodoCronogramaVacacion'][$i];
					$datosDetalle = array(
						'id_cronograma_vacacion' => (int) $idCronogramaVacacion,
						'numero_periodo' => $datos['hNumeroPeriodo'][$i],
						'fecha_inicio' => $datos['hFechaInicio'][$i],
						'fecha_fin' => $datos['hFechaFin'][$i],
						'total_dias' => $datos['hNumeroDias'][$i],
						'estado_reprogramacion' => $datos['hReprogramado'][$i],
					);

					$statementUpdate = $this->modeloPeriodoCronogramaVacaciones->getAdapter()
						->getDriver()
						->createStatement();

					$arrayParametrosCertificado = array(
						'estado_registro' => 'Inactivo'
					);

					$sqlActualizar = $this->modeloPeriodoCronogramaVacaciones->actualizarSql('periodo_cronograma_vacaciones', $this->modeloPeriodoCronogramaVacaciones->getEsquema());
					$sqlActualizar->set($arrayParametrosCertificado);
					$sqlActualizar->where(array('id_periodo_cronograma_vacacion' => $idPeriodoCronogramaVacacion));
					$sqlActualizar->prepareStatement($this->modeloPeriodoCronogramaVacaciones->getAdapter(), $statementUpdate);
					$statementUpdate->execute();

					$sqlInsertar = $this->modeloPeriodoCronogramaVacaciones->guardarSql('periodo_cronograma_vacaciones', $this->modeloPeriodoCronogramaVacaciones->getEsquema());
					$sqlInsertar->columns(array_keys($datosDetalle));
					$sqlInsertar->values($datosDetalle, $sqlInsertar::VALUES_MERGE);
					$sqlInsertar->prepareStatement($this->modeloPeriodoCronogramaVacaciones->getAdapter(), $statement);
					$statement->execute();
				}
			}




			$proceso->commit();
			return true;
		} catch (\Exception $ex) {
			$proceso->rollback();
			throw new \Exception($ex->getMessage());
			return 0;
		}
	}
}
