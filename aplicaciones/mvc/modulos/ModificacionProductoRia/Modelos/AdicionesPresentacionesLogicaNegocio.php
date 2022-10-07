<?php
 /**
 * Lógica del negocio de AdicionesPresentacionesModelo
 *
 * Este archivo se complementa con el archivo AdicionesPresentacionesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-21
 * @uses    AdicionesPresentacionesLogicaNegocio
 * @package ModificacionProductoRia
 * @subpackage Modelos
 */
  namespace Agrodb\ModificacionProductoRia\Modelos;
  
  use Agrodb\ModificacionProductoRia\Modelos\IModelo;
 
class AdicionesPresentacionesLogicaNegocio implements IModelo 
{

	 private $modeloAdicionesPresentaciones = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloAdicionesPresentaciones = new AdicionesPresentacionesModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new AdicionesPresentacionesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdAdicionPresentacion() != null && $tablaModelo->getIdAdicionPresentacion() > 0) {
		return $this->modeloAdicionesPresentaciones->actualizar($datosBd, $tablaModelo->getIdAdicionPresentacion());
		} else {
		unset($datosBd["id_adicion_presentacion"]);
		return $this->modeloAdicionesPresentaciones->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloAdicionesPresentaciones->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return AdicionesPresentacionesModelo
	*/
	public function buscar($id)
	{
		return $this->modeloAdicionesPresentaciones->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloAdicionesPresentaciones->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloAdicionesPresentaciones->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarAdicionesPresentaciones()
	{
	$consulta = "SELECT * FROM ".$this->modeloAdicionesPresentaciones->getEsquema().". adiciones_presentaciones";
		 return $this->modeloAdicionesPresentaciones->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 * Buscar usos usando filtros.
	 *
	 * @return array|ResultSet
	 */
	public function buscarAdicionPresentacionOrigenDestino($arrayParametros)
	{
	    
	    $idDetalleSolicitudProducto = $arrayParametros['id_detalle_solicitud_producto'];
	    	    
	    $consulta = "SELECT
                            tap.id_adicion_presentacion
                            , tap.id_detalle_solicitud_producto
                            , COALESCE(tap.subcodigo, ci.subcodigo) as subcodigo
                            , COALESCE(tap.presentacion, ci.presentacion) as presentacion
                            , COALESCE(tap.unidad_medida, ci.unidad_medida) as unidad_medida
                            , COALESCE(tap.estado, 'activo') as estado
                            , ci.id_producto || '.' || ci.subcodigo as id_adicion_presentacion_origen
                       FROM 
                            (SELECT 
                            	ap.id_adicion_presentacion
                            	, ap.id_detalle_solicitud_producto
                            	, ap.subcodigo
                            	, ap.presentacion
                            	, ap.unidad_medida
                                , sp.id_producto
                                , ap.estado
                            FROM 
                            	g_modificacion_productos.adiciones_presentaciones ap
                            	INNER JOIN g_modificacion_productos.detalle_solicitudes_productos dsp ON dsp.id_detalle_solicitud_producto = ap.id_detalle_solicitud_producto
                            	INNER JOIN g_modificacion_productos.solicitudes_productos sp ON sp.id_solicitud_producto = dsp.id_solicitud_producto
                            ) tap
                            FULL OUTER JOIN g_catalogos.codigos_inocuidad ci ON ci.id_producto || ci.subcodigo  = tap.id_producto || tap.subcodigo
                        WHERE 
                            tap.id_detalle_solicitud_producto = '" . $idDetalleSolicitudProducto . "'
                            " . (isset($arrayParametros['id_producto']) ? " or ci.id_producto = '" . $arrayParametros['id_producto'] . "'" : "") . "
                        ORDER BY
                        	tap.id_producto || tap.subcodigo ASC;";
	    
	    return $this->modeloAdicionesPresentaciones->ejecutarSqlNativo($consulta);
	}	
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 * Buscar usos usando filtros.
	 *
	 * @return array|ResultSet
	 */
	public function buscarAdicionPresentacionPorEliminar($arrayParametros)
	{
	    
	    $idDetalleSolicitudProducto = $arrayParametros['id_detalle_solicitud_producto'];
	    $idProducto = $arrayParametros['id_producto'];
	    $subcodigo = $arrayParametros['subcodigo'];
	    
	    $consulta = "SELECT
                    	ap.id_adicion_presentacion
                    	, ap.id_detalle_solicitud_producto
                    	, ap.subcodigo
                    	, ap.presentacion
                    	, ap.unidad_medida
                    	, sp.id_producto
                    	, ap.estado
                    FROM
                    	g_modificacion_productos.adiciones_presentaciones ap
                    	INNER JOIN g_modificacion_productos.detalle_solicitudes_productos dsp ON dsp.id_detalle_solicitud_producto = ap.id_detalle_solicitud_producto
                    	INNER JOIN g_modificacion_productos.solicitudes_productos sp ON sp.id_solicitud_producto = dsp.id_solicitud_producto
                    WHERE
                        dsp.id_detalle_solicitud_producto = '" . $idDetalleSolicitudProducto . "'
                    	and sp.id_producto = '" . $idProducto . "'
                    	and ap.subcodigo = '" . $subcodigo . "';";
	    
	    return $this->modeloAdicionesPresentaciones->ejecutarSqlNativo($consulta);
	}	

}
