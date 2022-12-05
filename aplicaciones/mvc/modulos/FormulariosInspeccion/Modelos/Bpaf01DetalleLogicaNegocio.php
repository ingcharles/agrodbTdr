<?php
 /**
 * Lógica del negocio de Bpaf01DetalleModelo
 *
 * Este archivo se complementa con el archivo Bpaf01DetalleControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-09-23
 * @uses    Bpaf01DetalleLogicaNegocio
 * @package AplicacionMovilBPA
 * @subpackage Modelos
 */
  namespace Agrodb\FormulariosInspeccion\Modelos;
  
  use Agrodb\FormulariosInspeccion\Modelos\IModelo;
 
class Bpaf01DetalleLogicaNegocio implements IModelo 
{

	 private $modeloBpaf01Detalle = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloBpaf01Detalle = new Bpaf01DetalleModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new Bpaf01DetalleModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getId() != null && $tablaModelo->getId() > 0) {
		return $this->modeloBpaf01Detalle->actualizar($datosBd, $tablaModelo->getId());
		} else {
		unset($datosBd["id_inspeccion_bpa_detalle"]);
		return $this->modeloBpaf01Detalle->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloBpaf01Detalle->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return Bpaf01DetalleModelo
	*/
	public function buscar($id)
	{
		return $this->modeloBpaf01Detalle->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloBpaf01Detalle->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloBpaf01Detalle->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarBpaf01Detalle()
	{
	$consulta = "SELECT * FROM ".$this->modeloBpaf01Detalle->getEsquema().". bpaf01_detalle";
		 return $this->modeloBpaf01Detalle->ejecutarSqlNativo($consulta);
	}

}
