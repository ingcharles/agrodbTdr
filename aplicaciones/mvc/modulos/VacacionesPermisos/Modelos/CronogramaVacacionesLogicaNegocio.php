<?php
 /**
 * Lógica del negocio de CronogramaVacacionesModelo
 *
 * Este archivo se complementa con el archivo CronogramaVacacionesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-10-22
 * @uses    CronogramaVacacionesLogicaNegocio
 * @package VacacionesPermisos
 * @subpackage Modelos
 */
  namespace Agrodb\VacacionesPermisos\Modelos;
  
  use Agrodb\VacacionesPermisos\Modelos\IModelo;
 
class CronogramaVacacionesLogicaNegocio implements IModelo 
{

	 private $modeloCronogramaVacaciones = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloCronogramaVacaciones = new CronogramaVacacionesModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new CronogramaVacacionesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdCronogramaVacacion() != null && $tablaModelo->getIdCronogramaVacacion() > 0) {
		return $this->modeloCronogramaVacaciones->actualizar($datosBd, $tablaModelo->getIdCronogramaVacacion());
		} else {
		unset($datosBd["id_cronograma_vacacion"]);
		return $this->modeloCronogramaVacaciones->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloCronogramaVacaciones->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return CronogramaVacacionesModelo
	*/
	public function buscar($id)
	{
		return $this->modeloCronogramaVacaciones->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloCronogramaVacaciones->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloCronogramaVacaciones->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarCronogramaVacaciones()
	{
	$consulta = "SELECT * FROM ".$this->modeloCronogramaVacaciones->getEsquema().". cronograma_vacaciones";
		 return $this->modeloCronogramaVacaciones->ejecutarSqlNativo($consulta);
	}

}
