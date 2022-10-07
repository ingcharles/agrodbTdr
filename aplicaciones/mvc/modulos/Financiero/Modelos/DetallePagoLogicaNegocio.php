<?php
 /**
 * Lógica del negocio de DetallePagoModelo
 *
 * Este archivo se complementa con el archivo DetallePagoControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-10-10
 * @uses    DetallePagoLogicaNegocio
 * @package Financiero
 * @subpackage Modelos
 */
  namespace Agrodb\Financiero\Modelos;
  
  use Agrodb\Financiero\Modelos\IModelo;
 
class DetallePagoLogicaNegocio implements IModelo 
{

	 private $modeloDetallePago = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloDetallePago = new DetallePagoModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new DetallePagoModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDetalle() != null && $tablaModelo->getIdDetalle() > 0) {
		return $this->modeloDetallePago->actualizar($datosBd, $tablaModelo->getIdDetalle());
		} else {
		unset($datosBd["id_detalle"]);
		return $this->modeloDetallePago->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloDetallePago->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return DetallePagoModelo
	*/
	public function buscar($id)
	{
		return $this->modeloDetallePago->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloDetallePago->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloDetallePago->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarDetallePago()
	{
	$consulta = "SELECT * FROM ".$this->modeloDetallePago->getEsquema().". detalle_pago";
		 return $this->modeloDetallePago->ejecutarSqlNativo($consulta);
	}

}
