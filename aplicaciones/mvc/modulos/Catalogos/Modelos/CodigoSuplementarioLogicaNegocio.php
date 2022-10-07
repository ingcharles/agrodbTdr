<?php
 /**
 * Lógica del negocio de CodigoSuplementarioModelo
 *
 * Este archivo se complementa con el archivo CodigoSuplementarioControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-02
 * @uses    CodigoSuplementarioLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
  namespace Agrodb\Catalogos\Modelos;
  
  use Agrodb\Catalogos\Modelos\IModelo;
 
class CodigoSuplementarioLogicaNegocio implements IModelo 
{

	 private $modeloCodigoSuplementario = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloCodigoSuplementario = new CodigoSuplementarioModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new CodigoSuplementarioModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdCodSuplementario() != null && $tablaModelo->getIdCodSuplementario() > 0) {
		return $this->modeloCodigoSuplementario->actualizar($datosBd, $tablaModelo->getIdCodSuplementario());
		} else {
		unset($datosBd["id_cod_suplementario"]);
		return $this->modeloCodigoSuplementario->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloCodigoSuplementario->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return CodigoSuplementarioModelo
	*/
	public function buscar($id)
	{
		return $this->modeloCodigoSuplementario->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloCodigoSuplementario->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloCodigoSuplementario->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarCodigoSuplementario()
	{
	$consulta = "SELECT * FROM ".$this->modeloCodigoSuplementario->getEsquema().". codigo_suplementario";
		 return $this->modeloCodigoSuplementario->ejecutarSqlNativo($consulta);
	}

}
