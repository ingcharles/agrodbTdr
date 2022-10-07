<?php
 /**
 * Lógica del negocio de IdiomasModelo
 *
 * Este archivo se complementa con el archivo IdiomasControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-07-04
 * @uses    IdiomasLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
  namespace Agrodb\Catalogos\Modelos;
  
  use Agrodb\Catalogos\Modelos\IModelo;
 
class IdiomasLogicaNegocio implements IModelo 
{

	 private $modeloIdiomas = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloIdiomas = new IdiomasModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new IdiomasModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdIdioma() != null && $tablaModelo->getIdIdioma() > 0) {
		return $this->modeloIdiomas->actualizar($datosBd, $tablaModelo->getIdIdioma());
		} else {
		unset($datosBd["id_idioma"]);
		return $this->modeloIdiomas->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloIdiomas->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return IdiomasModelo
	*/
	public function buscar($id)
	{
		return $this->modeloIdiomas->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloIdiomas->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloIdiomas->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarIdiomas()
	{
	$consulta = "SELECT * FROM ".$this->modeloIdiomas->getEsquema().". idiomas";
		 return $this->modeloIdiomas->ejecutarSqlNativo($consulta);
	}

}
