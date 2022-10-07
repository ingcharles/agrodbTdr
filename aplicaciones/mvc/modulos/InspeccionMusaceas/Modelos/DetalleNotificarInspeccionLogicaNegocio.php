<?php
 /**
 * Lógica del negocio de DetalleNotificarInspeccionModelo
 *
 * Este archivo se complementa con el archivo DetalleNotificarInspeccionControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-18
 * @uses    DetalleNotificarInspeccionLogicaNegocio
 * @package InspeccionMusaceas
 * @subpackage Modelos
 */
  namespace Agrodb\InspeccionMusaceas\Modelos;
  
  use Agrodb\InspeccionMusaceas\Modelos\IModelo;
 
class DetalleNotificarInspeccionLogicaNegocio implements IModelo 
{

	 private $modeloDetalleNotificarInspeccion = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloDetalleNotificarInspeccion = new DetalleNotificarInspeccionModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new DetalleNotificarInspeccionModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDetalleNotificarInspeccion() != null && $tablaModelo->getIdDetalleNotificarInspeccion() > 0) {
		return $this->modeloDetalleNotificarInspeccion->actualizar($datosBd, $tablaModelo->getIdDetalleNotificarInspeccion());
		} else {
		unset($datosBd["id_detalle_notificar_inspeccion"]);
		return $this->modeloDetalleNotificarInspeccion->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloDetalleNotificarInspeccion->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return DetalleNotificarInspeccionModelo
	*/
	public function buscar($id)
	{
		return $this->modeloDetalleNotificarInspeccion->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloDetalleNotificarInspeccion->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloDetalleNotificarInspeccion->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarDetalleNotificarInspeccion()
	{
	$consulta = "SELECT * FROM ".$this->modeloDetalleNotificarInspeccion->getEsquema().". detalle_notificar_inspeccion";
		 return $this->modeloDetalleNotificarInspeccion->ejecutarSqlNativo($consulta);
	}
	/**
	 * Columnas para guardar junto con el formulario
	 * @return string[]
	 */
	public function columnas()
	{
	    $columnas = array(
	        'id_notificar_inspeccion',
	        'correo_productor',
	        'id_detalle_solicitud_inspeccion'
	    );
	    return $columnas;
	}

}
