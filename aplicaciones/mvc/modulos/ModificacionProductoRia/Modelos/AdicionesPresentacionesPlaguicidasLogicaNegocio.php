<?php
 /**
 * Lógica del negocio de AdicionesPresentacionesPlaguicidasModelo
 *
 * Este archivo se complementa con el archivo AdicionesPresentacionesPlaguicidasControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-21
 * @uses    AdicionesPresentacionesPlaguicidasLogicaNegocio
 * @package ModificacionProductoRia
 * @subpackage Modelos
 */
  namespace Agrodb\ModificacionProductoRia\Modelos;
  
  use Agrodb\ModificacionProductoRia\Modelos\IModelo;
 
class AdicionesPresentacionesPlaguicidasLogicaNegocio implements IModelo 
{

	 private $modeloAdicionesPresentacionesPlaguicidas = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloAdicionesPresentacionesPlaguicidas = new AdicionesPresentacionesPlaguicidasModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new AdicionesPresentacionesPlaguicidasModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdAdicionPresentacionPlaguicida() != null && $tablaModelo->getIdAdicionPresentacionPlaguicida() > 0) {
		return $this->modeloAdicionesPresentacionesPlaguicidas->actualizar($datosBd, $tablaModelo->getIdAdicionPresentacionPlaguicida());
		} else {
		unset($datosBd["id_adicion_presentacion_plaguicida"]);
		return $this->modeloAdicionesPresentacionesPlaguicidas->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloAdicionesPresentacionesPlaguicidas->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return AdicionesPresentacionesPlaguicidasModelo
	*/
	public function buscar($id)
	{
		return $this->modeloAdicionesPresentacionesPlaguicidas->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloAdicionesPresentacionesPlaguicidas->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloAdicionesPresentacionesPlaguicidas->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarAdicionesPresentacionesPlaguicidas()
	{
	$consulta = "SELECT * FROM ".$this->modeloAdicionesPresentacionesPlaguicidas->getEsquema().". adiciones_presentaciones_plaguicidas";
		 return $this->modeloAdicionesPresentacionesPlaguicidas->ejecutarSqlNativo($consulta);
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
                        	tapp.id_adicion_presentacion_plaguicida
                        	, tapp.id_detalle_solicitud_producto
                        	, tpp.id_presentacion as id_adicion_presentacion_origen
                        	, COALESCE(tapp.subcodigo, tpp.codigo_presentacion) as subcodigo
                        	, COALESCE(tapp.presentacion, tpp.presentacion) as presentacion
                        	, COALESCE(tapp.unidad_medida, tpp.unidad) as unidad_medida
                        	, COALESCE(tapp.estado, tpp.estado) as estado
                        	, COALESCE(tapp.partida_arancelaria, tpp.partida_arancelaria) as partida_arancelaria
                            , COALESCE(tapp.codigo_producto, tpp.codigo_producto) as codigo_producto
                        	, COALESCE(tapp.codigo_complementario, tpp.codigo_complementario) as codigo_complementario
                        	, COALESCE(tapp.codigo_suplementario, tpp.codigo_suplementario) as codigo_suplementario
                        FROM 
                        	(SELECT 
                        		app.id_adicion_presentacion_plaguicida
                        		, app.id_detalle_solicitud_producto
                        		, app.id_tabla_origen
                        		, app.subcodigo
                        		, app.presentacion
                        		, app.unidad_medida
                        		, sp.id_producto
                        		, app.estado
                        	 	, topp.partida_arancelaria
                                , topp.codigo_producto
                        		, topp.codigo_complementario
                        		, topp.codigo_suplementario
                        	FROM 
                        		g_modificacion_productos.adiciones_presentaciones_plaguicidas app
                        		INNER JOIN g_modificacion_productos.detalle_solicitudes_productos dsp ON dsp.id_detalle_solicitud_producto = app.id_detalle_solicitud_producto
                        		INNER JOIN g_modificacion_productos.solicitudes_productos sp ON sp.id_solicitud_producto = dsp.id_solicitud_producto
                        	 	LEFT JOIN (
                        			SELECT
                        			pa.partida_arancelaria
                                    , pa.codigo_producto 
                        	 		, ccs.codigo_complementario
                        	 		, ccs.codigo_suplementario
                        			, ccs.id_codigo_comp_supl
                        			FROM 
                        			g_catalogos.codigos_comp_supl ccs
                        			INNER JOIN g_catalogos.partidas_arancelarias pa ON pa.id_partida_arancelaria = ccs.id_partida_arancelaria
                        			INNER JOIN g_catalogos.productos p ON p.id_producto = pa.id_producto
                        		) topp ON topp.id_codigo_comp_supl = app.id_codigo_comp_supl
                        	) tapp
                        	FULL OUTER JOIN 
                        	(SELECT 
                        			pp.id_presentacion
                        			, pp.codigo_presentacion
                        			, pp.presentacion
                        			, pp.unidad
                        			, p.id_producto
                        	 		, pp.estado
                        	 		, pa.partida_arancelaria
                                    , pa.codigo_producto
                        	 		, ccs.codigo_complementario
                        	 		, ccs.codigo_suplementario
                        		FROM
                        			g_catalogos.presentaciones_plaguicidas pp
                        			INNER JOIN g_catalogos.codigos_comp_supl ccs ON ccs.id_codigo_comp_supl = pp.id_codigo_comp_supl
                        			INNER JOIN g_catalogos.partidas_arancelarias pa ON pa.id_partida_arancelaria = ccs.id_partida_arancelaria
                        			INNER JOIN g_catalogos.productos p ON p.id_producto = pa.id_producto) tpp ON tpp.id_presentacion  = tapp.id_tabla_origen
                        WHERE
                            tapp.id_detalle_solicitud_producto = '" . $idDetalleSolicitudProducto . "'
                            " . (isset($arrayParametros['id_producto']) ? " or tpp.id_producto = '" . $arrayParametros['id_producto'] . "'" : "") . "
                        ORDER BY
                        	tapp.id_producto || tapp.subcodigo ASC;";
	    
	    return $this->modeloAdicionesPresentacionesPlaguicidas->ejecutarSqlNativo($consulta);
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
	    $idTablaOrigen = $arrayParametros['id_tabla_origen'];
	    
	    $consulta = "SELECT
                    	app.id_adicion_presentacion_plaguicida
                    	, app.id_detalle_solicitud_producto
                    	, app.subcodigo
                    	, app.presentacion
                    	, app.id_unidad_medida
                    	, sp.id_producto
                    	, app.estado
                    FROM
                    	g_modificacion_productos.adiciones_presentaciones_plaguicidas app
                    	INNER JOIN g_modificacion_productos.detalle_solicitudes_productos dsp ON dsp.id_detalle_solicitud_producto = app.id_detalle_solicitud_producto
                    	INNER JOIN g_modificacion_productos.solicitudes_productos sp ON sp.id_solicitud_producto = dsp.id_solicitud_producto
                    WHERE
                        dsp.id_detalle_solicitud_producto = '" . $idDetalleSolicitudProducto . "'
                        and app.id_tabla_origen = '" . $idTablaOrigen . "';";
	    
	    return $this->modeloAdicionesPresentacionesPlaguicidas->ejecutarSqlNativo($consulta);
	}	

}
