<?php
 /**
 * Lógica del negocio de VacunasEquinoModelo
 *
 * Este archivo se complementa con el archivo VacunasEquinoControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-02-18
 * @uses    VacunasEquinoLogicaNegocio
 * @package PasaporteEquino
 * @subpackage Modelos
 */
  namespace Agrodb\PasaporteEquino\Modelos;
  
  use Agrodb\PasaporteEquino\Modelos\IModelo;
 
class VacunasEquinoLogicaNegocio implements IModelo 
{

	 private $modeloVacunasEquino = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloVacunasEquino = new VacunasEquinoModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new VacunasEquinoModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdVacunaEquino() != null && $tablaModelo->getIdVacunaEquino() > 0) {
		return $this->modeloVacunasEquino->actualizar($datosBd, $tablaModelo->getIdVacunaEquino());
		} else {
		unset($datosBd["id_vacuna_equino"]);
		return $this->modeloVacunasEquino->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloVacunasEquino->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return VacunasEquinoModelo
	*/
	public function buscar($id)
	{
		return $this->modeloVacunasEquino->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloVacunasEquino->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloVacunasEquino->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarVacunasEquino()
	{
	$consulta = "SELECT * FROM ".$this->modeloVacunasEquino->getEsquema().". vacunas_equino";
		 return $this->modeloVacunasEquino->ejecutarSqlNativo($consulta);
	}

}
