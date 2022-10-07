<?php
 /**
 * Lógica del negocio de DetalleEstadoSolicitudModelo
 *
 * Este archivo se complementa con el archivo DetalleEstadoSolicitudControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-18
 * @uses    DetalleEstadoSolicitudLogicaNegocio
 * @package InspeccionMusaceas
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionMusaceas\Modelos;
  
  use Agrodb\InspeccionMusaceas\Modelos\IModelo;
 
class DetalleEstadoSolicitudLogicaNegocio implements IModelo 
{

	 private $modeloDetalleEstadoSolicitud = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloDetalleEstadoSolicitud = new DetalleEstadoSolicitudModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new DetalleEstadoSolicitudModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDetalleEstadoSolicitud() != null && $tablaModelo->getIdDetalleEstadoSolicitud() > 0) {
		return $this->modeloDetalleEstadoSolicitud->actualizar($datosBd, $tablaModelo->getIdDetalleEstadoSolicitud());
		} else {
		unset($datosBd["id_detalle_estado_solicitud"]);
		return $this->modeloDetalleEstadoSolicitud->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloDetalleEstadoSolicitud->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return DetalleEstadoSolicitudModelo
	*/
	public function buscar($id)
	{
		return $this->modeloDetalleEstadoSolicitud->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloDetalleEstadoSolicitud->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloDetalleEstadoSolicitud->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarDetalleEstadoSolicitud()
	{
	$consulta = "SELECT * FROM ".$this->modeloDetalleEstadoSolicitud->getEsquema().". detalle_estado_solicitud";
		 return $this->modeloDetalleEstadoSolicitud->ejecutarSqlNativo($consulta);
	}

}
