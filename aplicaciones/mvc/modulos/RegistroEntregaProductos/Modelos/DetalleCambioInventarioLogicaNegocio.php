<?php
 /**
 * Lógica del negocio de DetalleCambioInventarioModelo
 *
 * Este archivo se complementa con el archivo DetalleCambioInventarioControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-01-03
 * @uses    DetalleCambioInventarioLogicaNegocio
 * @package RegistroEntregaProductos
 * @subpackage Modelos
 */
  namespace Agrodb\RegistroEntregaProductos\Modelos;
  
  use Agrodb\RegistroEntregaProductos\Modelos\IModelo;
 
class DetalleCambioInventarioLogicaNegocio implements IModelo 
{

	 private $modeloDetalleCambioInventario = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloDetalleCambioInventario = new DetalleCambioInventarioModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new DetalleCambioInventarioModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDetalleInventario() != null && $tablaModelo->getIdDetalleInventario() > 0) {
		return $this->modeloDetalleCambioInventario->actualizar($datosBd, $tablaModelo->getIdDetalleInventario());
		} else {
		unset($datosBd["id_detalle_inventario"]);
		return $this->modeloDetalleCambioInventario->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloDetalleCambioInventario->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return DetalleCambioInventarioModelo
	*/
	public function buscar($id)
	{
		return $this->modeloDetalleCambioInventario->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloDetalleCambioInventario->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloDetalleCambioInventario->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarDetalleCambioInventario()
	{
	$consulta = "SELECT * FROM ".$this->modeloDetalleCambioInventario->getEsquema().". detalle_cambio_inventario";
		 return $this->modeloDetalleCambioInventario->ejecutarSqlNativo($consulta);
	}

}
