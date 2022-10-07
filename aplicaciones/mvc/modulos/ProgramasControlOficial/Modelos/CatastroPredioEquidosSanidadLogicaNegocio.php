<?php
 /**
 * Lógica del negocio de CatastroPredioEquidosSanidadModelo
 *
 * Este archivo se complementa con el archivo CatastroPredioEquidosSanidadControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-02-16
 * @uses    CatastroPredioEquidosSanidadLogicaNegocio
 * @package ProgramasControlOficial
 * @subpackage Modelos
 */
  namespace Agrodb\ProgramasControlOficial\Modelos;
  
  use Agrodb\ProgramasControlOficial\Modelos\IModelo;
 
class CatastroPredioEquidosSanidadLogicaNegocio implements IModelo 
{

	 private $modeloCatastroPredioEquidosSanidad = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloCatastroPredioEquidosSanidad = new CatastroPredioEquidosSanidadModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new CatastroPredioEquidosSanidadModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdCatastroPredioEquidosSanidad() != null && $tablaModelo->getIdCatastroPredioEquidosSanidad() > 0) {
		return $this->modeloCatastroPredioEquidosSanidad->actualizar($datosBd, $tablaModelo->getIdCatastroPredioEquidosSanidad());
		} else {
		unset($datosBd["id_catastro_predio_equidos_sanidad"]);
		return $this->modeloCatastroPredioEquidosSanidad->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloCatastroPredioEquidosSanidad->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return CatastroPredioEquidosSanidadModelo
	*/
	public function buscar($id)
	{
		return $this->modeloCatastroPredioEquidosSanidad->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloCatastroPredioEquidosSanidad->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloCatastroPredioEquidosSanidad->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarCatastroPredioEquidosSanidad()
	{
	$consulta = "SELECT * FROM ".$this->modeloCatastroPredioEquidosSanidad->getEsquema().". catastro_predio_equidos_sanidad";
		 return $this->modeloCatastroPredioEquidosSanidad->ejecutarSqlNativo($consulta);
	}

}
