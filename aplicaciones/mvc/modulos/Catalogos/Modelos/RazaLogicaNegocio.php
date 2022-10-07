<?php
 /**
 * Lógica del negocio de RazaModelo
 *
 * Este archivo se complementa con el archivo RazaControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-03-22
 * @uses    RazaLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
  namespace Agrodb\Catalogos\Modelos;
  
  use Agrodb\Catalogos\Modelos\IModelo;
 
class RazaLogicaNegocio implements IModelo 
{

	 private $modeloRaza = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloRaza = new RazaModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new RazaModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdRaza() != null && $tablaModelo->getIdRaza() > 0) {
		return $this->modeloRaza->actualizar($datosBd, $tablaModelo->getIdRaza());
		} else {
		unset($datosBd["id_raza"]);
		return $this->modeloRaza->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloRaza->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return RazaModelo
	*/
	public function buscar($id)
	{
		return $this->modeloRaza->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloRaza->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloRaza->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarRaza()
	{
	$consulta = "SELECT * FROM ".$this->modeloRaza->getEsquema().". raza";
		 return $this->modeloRaza->ejecutarSqlNativo($consulta);
	}

}
