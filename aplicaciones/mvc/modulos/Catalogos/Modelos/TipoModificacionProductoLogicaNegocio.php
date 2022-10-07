<?php
 /**
 * Lógica del negocio de TipoModificacionProductoModelo
 *
 * Este archivo se complementa con el archivo TipoModificacionProductoControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-13
 * @uses    TipoModificacionProductoLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
  namespace Agrodb\Catalogos\Modelos;
  
  use Agrodb\Catalogos\Modelos\IModelo;
 
class TipoModificacionProductoLogicaNegocio implements IModelo 
{

	 private $modeloTipoModificacionProducto = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloTipoModificacionProducto = new TipoModificacionProductoModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new TipoModificacionProductoModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdTipoModificacionProducto() != null && $tablaModelo->getIdTipoModificacionProducto() > 0) {
		return $this->modeloTipoModificacionProducto->actualizar($datosBd, $tablaModelo->getIdTipoModificacionProducto());
		} else {
		unset($datosBd["id_tipo_modificacion_producto"]);
		return $this->modeloTipoModificacionProducto->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloTipoModificacionProducto->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return TipoModificacionProductoModelo
	*/
	public function buscar($id)
	{
		return $this->modeloTipoModificacionProducto->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloTipoModificacionProducto->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloTipoModificacionProducto->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarTipoModificacionProducto()
	{
	$consulta = "SELECT * FROM ".$this->modeloTipoModificacionProducto->getEsquema().". tipo_modificacion_producto";
		 return $this->modeloTipoModificacionProducto->ejecutarSqlNativo($consulta);
	}

}
