<?php
 /**
 * Lógica del negocio de DenominacionesVentasModelo
 *
 * Este archivo se complementa con el archivo DenominacionesVentasControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-13
 * @uses    DenominacionesVentasLogicaNegocio
 * @package ModificacionProductoRia
 * @subpackage Modelos
 */
  namespace Agrodb\ModificacionProductoRia\Modelos;
  
  use Agrodb\ModificacionProductoRia\Modelos\IModelo;
 
class DenominacionesVentasLogicaNegocio implements IModelo 
{

	 private $modeloDenominacionesVentas = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloDenominacionesVentas = new DenominacionesVentasModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new DenominacionesVentasModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDenominacionVenta() != null && $tablaModelo->getIdDenominacionVenta() > 0) {
		return $this->modeloDenominacionesVentas->actualizar($datosBd, $tablaModelo->getIdDenominacionVenta());
		} else {
		unset($datosBd["id_denominacion_venta"]);
		return $this->modeloDenominacionesVentas->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloDenominacionesVentas->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return DenominacionesVentasModelo
	*/
	public function buscar($id)
	{
		return $this->modeloDenominacionesVentas->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloDenominacionesVentas->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloDenominacionesVentas->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarDenominacionesVentas()
	{
	$consulta = "SELECT * FROM ".$this->modeloDenominacionesVentas->getEsquema().". denominaciones_ventas";
		 return $this->modeloDenominacionesVentas->ejecutarSqlNativo($consulta);
	}

}
