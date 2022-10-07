<?php
 /**
 * Lógica del negocio de DetalleResultadoInspeccionModelo
 *
 * Este archivo se complementa con el archivo DetalleResultadoInspeccionControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-18
 * @uses    DetalleResultadoInspeccionLogicaNegocio
 * @package InspeccionMusaceas
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionMusaceas\Modelos;
  
  use Agrodb\InspeccionMusaceas\Modelos\IModelo;
 
class DetalleResultadoInspeccionLogicaNegocio implements IModelo 
{

	 private $modeloDetalleResultadoInspeccion = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloDetalleResultadoInspeccion = new DetalleResultadoInspeccionModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new DetalleResultadoInspeccionModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDetalleResultadoInspeccion() != null && $tablaModelo->getIdDetalleResultadoInspeccion() > 0) {
		return $this->modeloDetalleResultadoInspeccion->actualizar($datosBd, $tablaModelo->getIdDetalleResultadoInspeccion());
		} else {
		unset($datosBd["id_detalle_resultado_inspeccion"]);
		return $this->modeloDetalleResultadoInspeccion->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloDetalleResultadoInspeccion->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return DetalleResultadoInspeccionModelo
	*/
	public function buscar($id)
	{
		return $this->modeloDetalleResultadoInspeccion->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloDetalleResultadoInspeccion->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloDetalleResultadoInspeccion->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarDetalleResultadoInspeccion()
	{
	$consulta = "SELECT * FROM ".$this->modeloDetalleResultadoInspeccion->getEsquema().". detalle_resultado_inspeccion";
		 return $this->modeloDetalleResultadoInspeccion->ejecutarSqlNativo($consulta);
	}
	public function columnas()
	{
	    $columnas = array(
	        'id_resultado_inspeccion',
	        'id_detalle_solicitud_inspeccion'
	    );
	    return $columnas;
	}
}
