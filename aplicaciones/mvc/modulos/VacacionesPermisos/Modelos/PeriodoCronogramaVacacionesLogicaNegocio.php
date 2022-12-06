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
	public function guardar(Array $datos)
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

			for ($i = 0; $i < count($datos['hNumeroPeriodo']); $i++) {

				if(isset($datos['hCerrarPeriodo'][$i]) && $datos['hCerrarPeriodo'][$i] == "Cerrado"){

					$numeroPeriodo = $datos['hNumeroPeriodo'][$i];
					$estadoPeriodo = $datos['hCerrarPeriodo'][$i];

					$datosCronogramaVacacion = array('numero_periodo' => $numeroPeriodo
													, 'estado_registro' => $estadoPeriodo);

					$sqlActualizar = $this->modeloPeriodoCronogramaVacaciones->actualizarSql('periodo_cronograma_vacaciones', $this->modeloPeriodoCronogramaVacaciones->getEsquema());
					$sqlActualizar->set($datosCronogramaVacacion);
					$sqlActualizar->where(array('id_cronograma_vacacion' => $idCronogramaVacacion, 'numero_periodo' => $numeroPeriodo));
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
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
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
	$consulta = "SELECT * FROM ".$this->modeloPeriodoCronogramaVacaciones->getEsquema().". periodo_cronograma_vacaciones";
		 return $this->modeloPeriodoCronogramaVacaciones->ejecutarSqlNativo($consulta);
	}

}
