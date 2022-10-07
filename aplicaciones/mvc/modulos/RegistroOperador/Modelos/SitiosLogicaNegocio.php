<?php
/**
 * Lógica del negocio de SitiosModelo
 *
 * Este archivo se complementa con el archivo SitiosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-02-19
 * @uses    SitiosLogicaNegocio
 * @package RegistroOperador
 * @subpackage Modelos
 */
namespace Agrodb\RegistroOperador\Modelos;

use Agrodb\RegistroOperador\Modelos\IModelo;

class SitiosLogicaNegocio implements IModelo
{

    private $modeloSitios = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloSitios = new SitiosModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new SitiosModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdSitio() != null && $tablaModelo->getIdSitio() > 0) {
            return $this->modeloSitios->actualizar($datosBd, $tablaModelo->getIdSitio());
        } else {
            unset($datosBd["id_sitio"]);
            return $this->modeloSitios->guardar($datosBd);
        }
    }

    /**
     * Borra el registro actual
     *
     * @param
     *            string Where|array $where
     * @return int
     */
    public function borrar($id)
    {
        $this->modeloSitios->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return SitiosModelo
     */
    public function buscar($id)
    {
        return $this->modeloSitios->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloSitios->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloSitios->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarSitios()
    {
        $consulta = "SELECT * FROM " . $this->modeloSitios->getEsquema() . ". sitios";
        return $this->modeloSitios->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los sitios y áreas que tiene un operador
     * de acuerdo a una operación por área temática.
     *
     * @return array|ResultSet
     */
    public function obtenerSitioXProductoOperacionAreaOperador($arrayParametros) {
        
        $consulta = "   SELECT
                        	op.identificador_operador
                            , s.id_sitio
                            , s.nombre_lugar, a.id_area
                            , a.nombre_area
                            , a.superficie_utilizada
                            , CASE WHEN ot.id_area || ot.codigo = 'AIPRO' THEN
								dma.superficie_miembro
							ELSE
								a.superficie_utilizada
							END as superficie_certificada
                            , op.id_tipo_operacion
                            , ot.nombre
                            , op.id_producto
                            , op.nombre_producto
                            , ot.id_area as area_tematica
                            , ao.nombre
                            , ao.unidad_medida
                        FROM
                        	g_operadores.operaciones op
                        	INNER JOIN g_catalogos.tipos_operacion ot ON op.id_tipo_operacion = ot.id_tipo_operacion
                        	INNER JOIN g_operadores.productos_areas_operacion pao ON op.id_operacion = pao.id_operacion
                        	INNER JOIN g_operadores.areas a ON pao.id_area = a.id_area
                        	INNER JOIN g_operadores.sitios s ON a.id_sitio = s.id_sitio
                            INNER JOIN g_catalogos.areas_operacion ao ON ao.id_tipo_operacion = ot.id_tipo_operacion
                            LEFT JOIN g_operadores.detalle_miembros_asociacion dma ON a.id_area = dma.id_area and op.id_operacion = dma.id_operacion
                        WHERE
                        	ot.codigo in (".$arrayParametros['codigo_operacion'].") and
                        	op.id_producto = ".$arrayParametros['id_producto']." and
                        	op.estado = 'registrado' and
                        	op.identificador_operador in (".$arrayParametros['identificador'].") and
                        	s.estado = 'creado' and
                        	a.estado = 'creado'
                        ORDER BY
                        	s.nombre_lugar ASC;";
        
        return $this->modeloSitios->ejecutarSqlNativo($consulta);
    }
	
	/**
     * Ejecuta una consulta(SQL) personalizada .
     * Busca las provincias en donde se tiene operaciones de un tipo especificado registradas
     *
     * @return array|ResultSet
     */
    public function buscarSitioDestinoXOperacion($arrayParametros)
    {
        $busqueda = '';
        
        if (isset($arrayParametros['tipoOperacion']) && ($arrayParametros['tipoOperacion'] != '')) {
            $busqueda .= " and tp.codigo in ( " . $arrayParametros['tipoOperacion'] .") ";
        }
        
        if (isset($arrayParametros['provincia']) && ($arrayParametros['provincia'] != '')) {
            $busqueda .= " and s.provincia= '" . $arrayParametros['provincia']."' ";
        }
        
        if (isset($arrayParametros['identificador_operador']) && ($arrayParametros['identificador_operador'] != '')) {
            $busqueda .= " and s.identificador_operador= '" . $arrayParametros['identificador_operador']."' ";
        }
        
        if (isset($arrayParametros['nombreSitio']) && ($arrayParametros['nombreSitio'] != '')) {
            $busqueda .= " and upper(s.nombre_lugar) ilike upper('%" . $arrayParametros['nombreSitio'] . "%') ";
        }
        
        if (isset($arrayParametros['codigo_provincia']) && ($arrayParametros['codigo_provincia'] != '')) {
            $busqueda .= " and s.codigo_provincia = '" . $arrayParametros['codigo_provincia'] . "' ";
        }
        
        if (isset($arrayParametros['codigo']) && ($arrayParametros['codigo'] != '')) {
            $busqueda .= " and s.codigo = '" . $arrayParametros['codigo'] . "' ";
        }
        
        if (isset($arrayParametros['idSitio']) && ($arrayParametros['idSitio'] != '')) {
            $busqueda .= " and s.id_sitio not in ( " . $arrayParametros['idSitio'] .") ";
        }
        
        $consulta = "   SELECT 
                        	distinct s.id_sitio,
                        	s.nombre_lugar
                        FROM g_operadores.operaciones o
                        	INNER JOIN g_catalogos.tipos_operacion tp on tp.id_tipo_operacion = o.id_tipo_operacion
                        	INNER JOIN g_operadores.productos_areas_operacion pao on pao.id_operacion = o.id_operacion
                        	INNER JOIN g_operadores.areas a ON pao.id_area = a.id_area
                        	INNER JOIN g_operadores.sitios s ON a.id_sitio = s.id_sitio
                        WHERE
                        	o.estado='registrado' and
                            s.estado = 'creado' ". $busqueda .";";
        
        //echo $consulta;
        return $this->modeloSitios->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Busca las provincias en donde se tiene operaciones de un tipo especificado registradas
     *
     * @return array|ResultSet
     */
    public function buscarSitioDestinoOperador($arrayParametros)
    {
        $busqueda = '';
        
        if (isset($arrayParametros['idSitio']) && ($arrayParametros['idSitio'] != '')) {
            $busqueda .= " and s.id_sitio = " . $arrayParametros['idSitio'] ;
        }
        
        if (isset($arrayParametros['idArea']) && ($arrayParametros['idArea'] != '')) {
            $busqueda .= " and a.id_area = " . $arrayParametros['idArea'] ;
        }
        
        $consulta = "   SELECT
                        	distinct s.id_sitio,
                        	s.nombre_lugar
                        FROM g_operadores.operaciones o
                        	INNER JOIN g_catalogos.tipos_operacion tp on tp.id_tipo_operacion = o.id_tipo_operacion
                        	INNER JOIN g_operadores.productos_areas_operacion pao on pao.id_operacion = o.id_operacion
                        	INNER JOIN g_operadores.areas a ON pao.id_area = a.id_area
                        	INNER JOIN g_operadores.sitios s ON a.id_sitio = s.id_sitio
                        WHERE
                        	o.estado='registrado' and
                            s.estado = 'creado' ". $busqueda .";";
        
        //echo $consulta;
        return $this->modeloSitios->ejecutarSqlNativo($consulta);
    }
    
    public function buscarSitioXOperacion($arrayParametros)
    {
        $consulta = "select
							o.id_operacion,
							o.id_tipo_operacion,
							o.identificador_operador,
							o.id_producto,
							o.nombre_producto,
							o.estado,
							o.id_producto,
							o.nombre_producto,
							o.observacion,
							o.nombre_pais,
							o.fecha_aprobacion,
							o.fecha_finalizacion,
							o.id_operador_tipo_operacion,
							o.id_historial_operacion,
							t.nombre,
							t.id_area as codigo_area,
							t.codigo as codigo_tipo_operacion,
                            ss.*
						from
							g_operadores.operaciones o,
							g_operadores.productos_areas_operacion pao,
							g_operadores.areas a,
							g_catalogos.tipos_operacion t,
							g_operadores.sitios ss
						where
							o.identificador_operador = '" . $arrayParametros['identificadorOperador'] . "' and
							o.id_operacion = " . $arrayParametros['idOperacion'] . " and
							o.id_operacion = pao.id_operacion and
							pao.id_area = a.id_area and
							o.id_operacion = pao.id_operacion and
							o.id_tipo_operacion = t.id_tipo_operacion and
							a.id_sitio = ss.id_sitio
						order by
							o.id_producto;";
        
        return $this->modeloSitios->ejecutarSqlNativo($consulta);
    }
}