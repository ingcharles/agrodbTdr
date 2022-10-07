<?php
 /**
 * Lógica del negocio de PeriodosRetirosModelo
 *
 * Este archivo se complementa con el archivo PeriodosRetirosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-21
 * @uses    PeriodosRetirosLogicaNegocio
 * @package ModificacionProductoRia
 * @subpackage Modelos
 */
  namespace Agrodb\ModificacionProductoRia\Modelos;
  
  use Agrodb\ModificacionProductoRia\Modelos\IModelo;
 
class PeriodosRetirosLogicaNegocio implements IModelo 
{

	 private $modeloPeriodosRetiros = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloPeriodosRetiros = new PeriodosRetirosModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new PeriodosRetirosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdPeriodoRetiro() != null && $tablaModelo->getIdPeriodoRetiro() > 0) {
		return $this->modeloPeriodosRetiros->actualizar($datosBd, $tablaModelo->getIdPeriodoRetiro());
		} else {
		unset($datosBd["id_periodo_retiro"]);
		return $this->modeloPeriodosRetiros->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloPeriodosRetiros->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return PeriodosRetirosModelo
	*/
	public function buscar($id)
	{
		return $this->modeloPeriodosRetiros->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloPeriodosRetiros->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloPeriodosRetiros->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarPeriodosRetiros()
	{
	$consulta = "SELECT * FROM ".$this->modeloPeriodosRetiros->getEsquema().". periodos_retiros";
		 return $this->modeloPeriodosRetiros->ejecutarSqlNativo($consulta);
	}

}
