<?php
 /**
 * Lógica del negocio de MovilizacionDetalleModelo
 *
 * Este archivo se complementa con el archivo MovilizacionDetalleControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-04-03
 * @uses    MovilizacionDetalleLogicaNegocio
 * @package MovilizacionSueros
 * @subpackage Modelos
 */
  namespace Agrodb\MovilizacionSueros\Modelos;
  
  use Agrodb\MovilizacionSueros\Modelos\IModelo;
 
class MovilizacionDetalleLogicaNegocio implements IModelo 
{

	 private $modeloMovilizacionDetalle = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloMovilizacionDetalle = new MovilizacionDetalleModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new MovilizacionDetalleModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdMovilizacionDetalle() != null && $tablaModelo->getIdMovilizacionDetalle() > 0) {
		return $this->modeloMovilizacionDetalle->actualizar($datosBd, $tablaModelo->getIdMovilizacionDetalle());
		} else {
		unset($datosBd["id_movilizacion_detalle"]);
		return $this->modeloMovilizacionDetalle->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloMovilizacionDetalle->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return MovilizacionDetalleModelo
	*/
	public function buscar($id)
	{
		return $this->modeloMovilizacionDetalle->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloMovilizacionDetalle->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloMovilizacionDetalle->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarMovilizacionDetalle()
	{
	$consulta = "SELECT * FROM ".$this->modeloMovilizacionDetalle->getEsquema().". movilizacion_detalle";
		 return $this->modeloMovilizacionDetalle->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener los productos "industria láctea"
	 *
	 * @return array|ResultSet
	 */
	public function obtenerProductoXIdSubtipoProductoXIdentificadorOperador($arrayParametros)
	{
		$consulta = "SELECT opr.id_producto, opr.nombre_producto from
						g_operadores.operaciones opr
						inner join g_catalogos.productos pr on pr.id_producto=opr.id_producto
						inner join g_catalogos.subtipo_productos spr on spr.id_subtipo_producto = pr.id_subtipo_producto
						inner join g_catalogos.tipo_productos tpr on tpr.id_tipo_producto = spr.id_tipo_producto
						where
						spr.codificacion_subtipo_producto = '" . $arrayParametros['codificacion_subtipoprod'] . "'
						and identificador_operador ='" . $arrayParametros['identificador_operador'] . "'";
		
		$datosTipoQueso =  $this->modeloProduccion->ejecutarConsulta($consulta);
		
		
		return $datosTipoQueso;
	}

}
