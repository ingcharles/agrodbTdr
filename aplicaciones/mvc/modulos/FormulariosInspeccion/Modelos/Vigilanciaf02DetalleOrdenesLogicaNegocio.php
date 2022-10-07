<?php
 /**
 * Lógica del negocio de Vigilanciaf02DetalleOrdenesModelo
 *
 * Este archivo se complementa con el archivo Vigilanciaf02DetalleOrdenesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021/08/23
 * @uses    Vigilanciaf02DetalleOrdenesLogicaNegocio
 * @package AplicacionMovilInternos
 * @subpackage Modelos
 */
  namespace Agrodb\FormulariosInspeccion\Modelos;
  
  use Agrodb\FormulariosInspeccion\Modelos\IModelo;
 
class Vigilanciaf02DetalleOrdenesLogicaNegocio implements IModelo 
{

	 private $modeloVigilanciaf02DetalleOrdenes = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloVigilanciaf02DetalleOrdenes = new Vigilanciaf02DetalleOrdenesModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new Vigilanciaf02DetalleOrdenesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getId() != null && $tablaModelo->getId() > 0) {
		return $this->modeloVigilanciaf02DetalleOrdenes->actualizar($datosBd, $tablaModelo->getId());
		} else {
		unset($datosBd["id"]);
		return $this->modeloVigilanciaf02DetalleOrdenes->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloVigilanciaf02DetalleOrdenes->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return Vigilanciaf02DetalleOrdenesModelo
	*/
	public function buscar($id)
	{
		return $this->modeloVigilanciaf02DetalleOrdenes->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloVigilanciaf02DetalleOrdenes->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloVigilanciaf02DetalleOrdenes->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarVigilanciaf02DetalleOrdenes()
	{
	$consulta = "SELECT * FROM ".$this->modeloVigilanciaf02DetalleOrdenes->getEsquema().". vigilanciaf02_detalle_ordenes";
		 return $this->modeloVigilanciaf02DetalleOrdenes->ejecutarSqlNativo($consulta);
	}

}
