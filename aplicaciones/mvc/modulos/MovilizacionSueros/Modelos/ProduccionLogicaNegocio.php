<?php
 /**
 * Lógica del negocio de ProduccionModelo
 *
 * Este archivo se complementa con el archivo ProduccionControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-04-03
 * @uses    ProduccionLogicaNegocio
 * @package MovilizacionSueros
 * @subpackage Modelos
 */
  namespace Agrodb\MovilizacionSueros\Modelos;
  
  use Agrodb\MovilizacionSueros\Modelos\IModelo;
 
class ProduccionLogicaNegocio implements IModelo 
{

	 private $modeloProduccion = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloProduccion = new ProduccionModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new ProduccionModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdProduccion() != null && $tablaModelo->getIdProduccion() > 0) {
		return $this->modeloProduccion->actualizar($datosBd, $tablaModelo->getIdProduccion());
		} else {
		$datosBd['identificador'] = $_SESSION['usuario'];
		$datosBd['fecha_produccion_suero'] = $datosBd['fecha_produccion_suero'].' '.date('H:i:s');
		unset($datosBd["id_produccion"]);
		return $this->modeloProduccion->guardar($datosBd);
		}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloProduccion->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return ProduccionModelo
	*/
	public function buscar($id)
	{
		return $this->modeloProduccion->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloProduccion->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloProduccion->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarProduccion()
	{
	$consulta = "SELECT * FROM ".$this->modeloProduccion->getEsquema().". produccion";
		 return $this->modeloProduccion->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener los productos "industria láctea" 
	 * 
	 * @return array|ResultSet
	 */
	public function obtenerProductoXIdSubtipoProductoXIdentificadorOperador($arrayParametros)
	{
		$consulta = "SELECT opr.id_producto, opr.nombre_producto,
						spr.codificacion_subtipo_producto as codigo, spr.nombre as sub_tipo
						from
						g_operadores.operaciones opr 
						inner join g_catalogos.productos pr on pr.id_producto=opr.id_producto
						inner join g_catalogos.subtipo_productos spr on spr.id_subtipo_producto = pr.id_subtipo_producto 
						inner join g_catalogos.tipo_productos tpr on tpr.id_tipo_producto = spr.id_tipo_producto
						where 
						spr.codificacion_subtipo_producto IN ('" . $arrayParametros['codificacion_subtipoprod'] . "') 
						and identificador_operador ='" . $arrayParametros['identificador_operador'] . "'";
		
		$datosTipoQueso =  $this->modeloProduccion->ejecutarConsulta($consulta);
		
		
		return $datosTipoQueso;
	}
	
	
	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener los productos "industria láctea"
	 *
	 * @return array|ResultSet
	 */
	public function listarProduccionXIdentificadorOperador($arrayParametros)
	{
		$busqueda = '';
		if (array_key_exists('fechaInicio', $arrayParametros)) {
			$busqueda = " and fecha_produccion_suero between '" . $arrayParametros['fechaInicio'] . " 00:00:00' and '". $arrayParametros['fechaFin'] . " 24:00:00' order by 1";
		}
		if (array_key_exists('idProduccion', $arrayParametros)) {
			$busqueda .= " and p.id_produccion = ".$arrayParametros['idProduccion'];
		}
		$consulta = "
					SELECT 
							p.id_produccion, p.fecha_produccion_suero, p.cantidad_leche_acopio, p.cantidad_leche_produccion, p.cantidad_queso_produccion,					
							p.cantidad_suero_produccion, cantidad_suero_restante, dcs.id_detalle_consumo_suero,
							p.id_producto_queso, p.id_producto_suero, dcs.estado
					FROM 
							g_movilizacion_suero.produccion p 
						INNER JOIN g_movilizacion_suero.detalle_cantidad_suero dcs ON p.id_produccion = dcs.id_produccion
					WHERE 
						p.estado in ('creado') and 
							dcs.estado in ('creado','pendiente','utilizado') and 
							p.identificador = '". $arrayParametros['identificador_operador']."'
							".$busqueda.";";

		$datosTipoQueso =  $this->modeloProduccion->ejecutarConsulta($consulta);
		
		return $datosTipoQueso;
	}
	
}
