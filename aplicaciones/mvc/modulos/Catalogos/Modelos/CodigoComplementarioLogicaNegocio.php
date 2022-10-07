<?php
 /**
 * Lógica del negocio de CodigoComplementarioModelo
 *
 * Este archivo se complementa con el archivo CodigoComplementarioControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-02
 * @uses    CodigoComplementarioLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
  namespace Agrodb\Catalogos\Modelos;
  
  use Agrodb\Catalogos\Modelos\IModelo;
 
class CodigoComplementarioLogicaNegocio implements IModelo 
{

	 private $modeloCodigoComplementario = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloCodigoComplementario = new CodigoComplementarioModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new CodigoComplementarioModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdCodComplementario() != null && $tablaModelo->getIdCodComplementario() > 0) {
		return $this->modeloCodigoComplementario->actualizar($datosBd, $tablaModelo->getIdCodComplementario());
		} else {
		unset($datosBd["id_cod_complementario"]);
		return $this->modeloCodigoComplementario->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloCodigoComplementario->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return CodigoComplementarioModelo
	*/
	public function buscar($id)
	{
		return $this->modeloCodigoComplementario->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloCodigoComplementario->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloCodigoComplementario->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarCodigoComplementario()
	{
	$consulta = "SELECT * FROM ".$this->modeloCodigoComplementario->getEsquema().". codigo_complementario";
		 return $this->modeloCodigoComplementario->ejecutarSqlNativo($consulta);
	}

}
