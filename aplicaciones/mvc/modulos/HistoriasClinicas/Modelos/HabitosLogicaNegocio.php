<?php
 /**
 * Lógica del negocio de HabitosModelo
 *
 * Este archivo se complementa con el archivo HabitosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-03-16
 * @uses    HabitosLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
  namespace Agrodb\HistoriasClinicas\Modelos;
  
  use Agrodb\HistoriasClinicas\Modelos\IModelo;
 
class HabitosLogicaNegocio implements IModelo 
{

	 private $modeloHabitos = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloHabitos = new HabitosModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new HabitosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdHabitos() != null && $tablaModelo->getIdHabitos() > 0) {
		return $this->modeloHabitos->actualizar($datosBd, $tablaModelo->getIdHabitos());
		} else {
		unset($datosBd["id_habitos"]);
		return $this->modeloHabitos->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloHabitos->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return HabitosModelo
	*/
	public function buscar($id)
	{
		return $this->modeloHabitos->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloHabitos->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloHabitos->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarHabitos()
	{
	$consulta = "SELECT * FROM ".$this->modeloHabitos->getEsquema().". habitos";
		 return $this->modeloHabitos->ejecutarSqlNativo($consulta);
	}

}
