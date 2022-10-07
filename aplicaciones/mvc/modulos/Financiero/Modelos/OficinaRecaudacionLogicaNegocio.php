<?php
 /**
 * Lógica del negocio de OficinaRecaudacionModelo
 *
 * Este archivo se complementa con el archivo OficinaRecaudacionControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-10-10
 * @uses    OficinaRecaudacionLogicaNegocio
 * @package Financiero
 * @subpackage Modelos
 */
  namespace Agrodb\Financiero\Modelos;
  
  use Agrodb\Financiero\Modelos\IModelo;
 
class OficinaRecaudacionLogicaNegocio implements IModelo 
{

	 private $modeloOficinaRecaudacion = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloOficinaRecaudacion = new OficinaRecaudacionModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new OficinaRecaudacionModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdOficinaRecaudacion() != null && $tablaModelo->getIdOficinaRecaudacion() > 0) {
		return $this->modeloOficinaRecaudacion->actualizar($datosBd, $tablaModelo->getIdOficinaRecaudacion());
		} else {
		unset($datosBd["id_oficina_recaudacion"]);
		return $this->modeloOficinaRecaudacion->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloOficinaRecaudacion->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return OficinaRecaudacionModelo
	*/
	public function buscar($id)
	{
		return $this->modeloOficinaRecaudacion->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloOficinaRecaudacion->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloOficinaRecaudacion->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarOficinaRecaudacion()
	{
	$consulta = "SELECT * FROM ".$this->modeloOficinaRecaudacion->getEsquema().". oficina_recaudacion";
		 return $this->modeloOficinaRecaudacion->ejecutarSqlNativo($consulta);
	}

}
