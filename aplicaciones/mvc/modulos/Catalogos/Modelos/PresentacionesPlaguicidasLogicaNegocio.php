<?php
/**
 * Lógica del negocio de PresentacionesPlaguicidasModelo
 *
 * Este archivo se complementa con el archivo PresentacionesPlaguicidasControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-21
 * @uses    PresentacionesPlaguicidasLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */

namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class PresentacionesPlaguicidasLogicaNegocio implements IModelo
{

    private $modeloPresentacionesPlaguicidas = null;


    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloPresentacionesPlaguicidas = new PresentacionesPlaguicidasModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(array $datos)
    {
        $tablaModelo = new PresentacionesPlaguicidasModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdPresentacion() != null && $tablaModelo->getIdPresentacion() > 0) {
            return $this->modeloPresentacionesPlaguicidas->actualizar($datosBd, $tablaModelo->getIdPresentacion());
        } else {
            unset($datosBd["id_presentacion"]);
            return $this->modeloPresentacionesPlaguicidas->guardar($datosBd);
        }
    }

    /**
     * Borra el registro actual
     * @param string Where|array $where
     * @return int
     */
    public function borrar($id)
    {
        $this->modeloPresentacionesPlaguicidas->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return PresentacionesPlaguicidasModelo
     */
    public function buscar($id)
    {
        return $this->modeloPresentacionesPlaguicidas->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloPresentacionesPlaguicidas->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloPresentacionesPlaguicidas->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarPresentacionesPlaguicidas()
    {
        $consulta = "SELECT * FROM " . $this->modeloPresentacionesPlaguicidas->getEsquema() . ". presentaciones_plaguicidas";
        return $this->modeloPresentacionesPlaguicidas->ejecutarSqlNativo($consulta);
    }

    /**
     * Genera subcodigo de inocuidad
     *
     * @return ResultSet
     */
    public function obtenerCodigoPresentacionPlaguicida($idProducto, $idPartidaArancelaria, $idCodigoComplementarioSuplementario)
    {

        $consulta = "SELECT
                    	COALESCE(MAX(CAST(tscp.codigo_presentacion as  numeric(5))),0)+1 as codigo
                    FROM
                    	(SELECT
                    		pp.codigo_presentacion
                    		, id_producto
                    		, pa.id_partida_arancelaria
                    		, ccs.id_codigo_comp_supl 
                    	FROM
                    		g_catalogos.presentaciones_plaguicidas pp
                    		INNER JOIN g_catalogos.codigos_comp_supl ccs ON pp.id_codigo_comp_supl = ccs.id_codigo_comp_supl
                    		INNER JOIN g_catalogos.partidas_arancelarias pa ON ccs.id_partida_arancelaria = pa.id_partida_arancelaria
                    	UNION
                    	SELECT
                    		app.subcodigo
                    		, sp.id_producto
                    		, app.id_partida_arancelaria
                    		, app.id_codigo_comp_supl 
                    	FROM
                    		g_modificacion_productos.adiciones_presentaciones_plaguicidas app
                    		INNER JOIN g_modificacion_productos.detalle_solicitudes_productos dsp ON dsp.id_detalle_solicitud_producto = app.id_detalle_solicitud_producto
                    		INNER JOIN g_modificacion_productos.solicitudes_productos sp ON sp.id_solicitud_producto = dsp.id_solicitud_producto
                    	) tscp
                    WHERE
                    	tscp.id_producto = '" . $idProducto . "'
                    	and tscp.id_partida_arancelaria = '" . $idPartidaArancelaria . "' 
                    	and tscp.id_codigo_comp_supl = '" . $idCodigoComplementarioSuplementario . "';";

        $res = $this->modeloPresentacionesPlaguicidas->ejecutarSqlNativo($consulta);

        return $res;
    }

    /**
     * Genera subcodigo de inocuidad
     *
     * @return ResultSet
     */
    public function verificarAdicionPresentacionPlaguicida($arrayParametros)
    {

        $idCodigoComplementarioSuplementario = $arrayParametros['id_codigo_complementario_suplementario'];
        $presentacion = $arrayParametros['presentacion'];
        $idUnidadMedida = $arrayParametros['id_unidad_medida'];

        $consulta = "SELECT
						app.id_codigo_comp_supl
                    	, app.presentacion
                    	, app.unidad_medida
                    FROM
                    	g_modificacion_productos.adiciones_presentaciones_plaguicidas app
                    	INNER JOIN g_modificacion_productos.detalle_solicitudes_productos dsp ON dsp.id_detalle_solicitud_producto = app.id_detalle_solicitud_producto
                    	INNER JOIN g_modificacion_productos.solicitudes_productos sp ON sp.id_solicitud_producto = dsp.id_solicitud_producto
                    WHERE
                    	app.id_codigo_comp_supl = '" . $idCodigoComplementarioSuplementario . "'
                    	and UPPER(app.presentacion) = UPPER('" . $presentacion . "')
                    	and app.id_unidad_medida = '" . $idUnidadMedida . "'
						UNION
						SELECT
							pp.id_codigo_comp_supl
							, pp.presentacion
							, pp.unidad
						FROM
							g_catalogos.presentaciones_plaguicidas pp
						WHERE
							pp.id_codigo_comp_supl = '" . $idCodigoComplementarioSuplementario . "'
                            and UPPER(pp.presentacion) = UPPER('" . $presentacion . "')
                            and pp.id_unidad = '" . $idUnidadMedida . "';";


        $res = $this->modeloPresentacionesPlaguicidas->ejecutarSqlNativo($consulta);

        return $res;
    }

    /**
     * Genera subcodigo de inocuidad
     *
     * @return ResultSet
     */
    public function obtenerDatosPartidaArencelariaPorIdCodigoComplementarioSuplementario($idCodigoComplementarioSuplementario)
    {

        //$idCodigoComplementarioSuplementario = $arrayParametros['id_codigo_comp_supl'];

        $consulta = "SELECT 
                        *
                        FROM
                        g_catalogos.codigos_comp_supl ccs
                       INNER JOIN g_catalogos.partidas_arancelarias pa ON pa.id_partida_arancelaria = ccs.id_partida_arancelaria
                        WHERE id_codigo_comp_supl = '" . $idCodigoComplementarioSuplementario . "'";

        $res = $this->modeloPresentacionesPlaguicidas->ejecutarSqlNativo($consulta);

        return $res;

    }

}
