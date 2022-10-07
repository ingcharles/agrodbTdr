<?php
 /**
 * Lógica del negocio de CatastroPredioEquidosCatastroModelo
 *
 * Este archivo se complementa con el archivo CatastroPredioEquidosCatastroControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-02-16
 * @uses    CatastroPredioEquidosCatastroLogicaNegocio
 * @package ProgramasControlOficial
 * @subpackage Modelos
 */
  namespace Agrodb\ProgramasControlOficial\Modelos;
  
  use Agrodb\ProgramasControlOficial\Modelos\IModelo;
 
class CatastroPredioEquidosCatastroLogicaNegocio implements IModelo 
{

	 private $modeloCatastroPredioEquidosCatastro = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloCatastroPredioEquidosCatastro = new CatastroPredioEquidosCatastroModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new CatastroPredioEquidosCatastroModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdCatastroPredioEquidosCatastro() != null && $tablaModelo->getIdCatastroPredioEquidosCatastro() > 0) {
		return $this->modeloCatastroPredioEquidosCatastro->actualizar($datosBd, $tablaModelo->getIdCatastroPredioEquidosCatastro());
		} else {
		unset($datosBd["id_catastro_predio_equidos_catastro"]);
		return $this->modeloCatastroPredioEquidosCatastro->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloCatastroPredioEquidosCatastro->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return CatastroPredioEquidosCatastroModelo
	*/
	public function buscar($id)
	{
		return $this->modeloCatastroPredioEquidosCatastro->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloCatastroPredioEquidosCatastro->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloCatastroPredioEquidosCatastro->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarCatastroPredioEquidosCatastro()
	{
	$consulta = "SELECT * FROM ".$this->modeloCatastroPredioEquidosCatastro->getEsquema().". catastro_predio_equidos_catastro";
		 return $this->modeloCatastroPredioEquidosCatastro->ejecutarSqlNativo($consulta);
	}

}
