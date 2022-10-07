<?php
 /**
 * Lógica del negocio de ProductosDistribucionModelo
 *
 * Este archivo se complementa con el archivo ProductosDistribucionControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-01-03
 * @uses    ProductosDistribucionLogicaNegocio
 * @package Distribucion_Entrega_Productos
 * @subpackage Modelos
 */
  namespace Agrodb\Catalogos\Modelos;
  
  use Agrodb\Catalogos\Modelos\IModelo;
 
class ProductosDistribucionLogicaNegocio implements IModelo 
{

	 private $modeloProductosDistribucion = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloProductosDistribucion = new ProductosDistribucionModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new ProductosDistribucionModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdProductoDistribucion() != null && $tablaModelo->getIdProductoDistribucion() > 0) {
		return $this->modeloProductosDistribucion->actualizar($datosBd, $tablaModelo->getIdProductoDistribucion());
		} else {
		unset($datosBd["id_producto_distribucion"]);
		return $this->modeloProductosDistribucion->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloProductosDistribucion->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return ProductosDistribucionModelo
	*/
	public function buscar($id)
	{
		return $this->modeloProductosDistribucion->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloProductosDistribucion->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloProductosDistribucion->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarProductosDistribucion()
	{
	$consulta = "SELECT * FROM ".$this->modeloProductosDistribucion->getEsquema().". productos_distribucion";
		 return $this->modeloProductosDistribucion->ejecutarSqlNativo($consulta);
	}

}
