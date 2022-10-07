<?php
/**
 * Lógica del negocio de SitiosAreasProductosModelo
 *
 * Este archivo se complementa con el archivo SitiosAreasProductosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-03-23
 * @uses    SitiosAreasProductosLogicaNegocio
 * @package CertificacionBPA
 * @subpackage Modelos
 */
namespace Agrodb\CertificacionBPA\Modelos;

use Agrodb\CertificacionBPA\Modelos\IModelo;

class SitiosAreasProductosLogicaNegocio implements IModelo
{

    private $modeloSitiosAreasProductos = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloSitiosAreasProductos = new SitiosAreasProductosModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new SitiosAreasProductosModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdSitioAreaProducto() != null && $tablaModelo->getIdSitioAreaProducto() > 0) {
            return $this->modeloSitiosAreasProductos->actualizar($datosBd, $tablaModelo->getIdSitioAreaProducto());
        } else {
            unset($datosBd["id_sitio_area_producto"]);
            return $this->modeloSitiosAreasProductos->guardar($datosBd);
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
        $this->modeloSitiosAreasProductos->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return SitiosAreasProductosModelo
     */
    public function buscar($id)
    {
        return $this->modeloSitiosAreasProductos->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloSitiosAreasProductos->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloSitiosAreasProductos->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarSitiosAreasProductos()
    {
        $consulta = "SELECT * FROM " . $this->modeloSitiosAreasProductos->getEsquema() . ". sitios_areas_productos";
        return $this->modeloSitiosAreasProductos->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada para verificar si existe un sitio/área/producto registrado por un 
     * operador/asociación en una solicitud previa.
     *
     * @return array|ResultSet
     */
    public function buscarSitioAreaProducto($arrayParametros) {
        
        $consulta = "   SELECT
                        	sap.identificador_sitio, sap.id_sitio, sap.nombre_sitio, sap.id_area, 
                        	sap.nombre_area, sap.superficie, sap.id_operacion, 
                        	sap.nombre_operacion, sap.id_producto, sap.nombre_producto,
                            s.estado, s.es_asociacion
                        FROM
                        	g_certificacion_bpa.sitios_areas_productos sap
                            INNER JOIN g_certificacion_bpa.solicitudes s ON sap.id_solicitud = s.id_solicitud
                        WHERE
                        	sap.id_sitio = ". $arrayParametros['id_sitio'] ." and
                        	sap.id_area = ". $arrayParametros['id_area'] ." and
                        	sap.id_producto = ". $arrayParametros['id_producto'] ." and
                        	sap.identificador_sitio  = '". $arrayParametros['identificador_sitio'] ."' and
                            sap.identificador_operador  = '". $arrayParametros['identificador_operador'] ."' and
                            --s.es_asociacion  = '". $arrayParametros['es_asociacion'] ."' and
                        	s.estado in ( 'Aprobado', 'enviado', 'inspeccion', 'pago', 'aprobacion', 'subsanacion' );";
        //echo $consulta;
        //'Expirado', 'Rechazado'
        return $this->modeloSitiosAreasProductos->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada para verificar si existe un sitio/área/producto registrado por un
     * operador/asociación en una solicitud previa.
     *
     * @return array|ResultSet
     */
    public function buscarSitioAreaProductoOtroRegistro($arrayParametros) {
        
        $consulta = "   SELECT
                        	sap.identificador_sitio, sap.id_sitio, sap.nombre_sitio, sap.id_area,
                        	sap.nombre_area, sap.superficie, sap.id_operacion,
                        	sap.nombre_operacion, sap.id_producto, sap.nombre_producto,
                            s.estado, s.es_asociacion
                        FROM
                        	g_certificacion_bpa.sitios_areas_productos sap
                            INNER JOIN g_certificacion_bpa.solicitudes s ON sap.id_solicitud = s.id_solicitud
                        WHERE
                        	sap.id_sitio = ". $arrayParametros['id_sitio'] ." and
                        	sap.id_area = ". $arrayParametros['id_area'] ." and
                        	sap.id_producto = ". $arrayParametros['id_producto'] ." and
                        	sap.identificador_sitio  = '". $arrayParametros['identificador_sitio'] ."' and
                            sap.identificador_operador not in ('". $arrayParametros['identificador_operador'] ."') and
                            --s.es_asociacion not in ('". $arrayParametros['es_asociacion'] ."') and
                        	s.estado in ( 'Aprobado' );";
        
        //echo $consulta;
        return $this->modeloSitiosAreasProductos->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los sitios registrados en una solicitud.
     *
     * @return array|ResultSet
     */
    public function buscarSitios($idSolicitud) {
        
        $consulta = "   SELECT
                        	distinct(sap.id_sitio), sap.nombre_sitio
                        FROM
                        	g_certificacion_bpa.sitios_areas_productos sap
                        WHERE
                        	sap.id_solicitud = ". $idSolicitud ." 
                        ORDER BY
                            2 ASC;";
        
        return $this->modeloSitiosAreasProductos->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar sitios registrados y calcular el total de hectáreas.
     *
     * @return array|ResultSet
     */
    public function calcularHectareasXSitioXSolicitud($idSolicitud)
    {
        $consulta = "  SELECT
                        	sum(sap.superficie) as hectareas
                        FROM
                        	g_certificacion_bpa.sitios_areas_productos sap
                        WHERE
                        	sap.id_solicitud = $idSolicitud;";
        
        return $this->modeloSitiosAreasProductos->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar sitios registrados y calcular el total de hectáreas.
     *
     * @return array|ResultSet
     */
    public function calcularHectareasXSitioXAreaXSolicitud($idSolicitud, $idSitioAreaProducto)
    {
        $consulta = "  SELECT
                        	sum(sap.superficie) as hectareas
                        FROM
                        	g_certificacion_bpa.sitios_areas_productos sap
                        WHERE
                        	sap.id_solicitud = $idSolicitud and
                            sap.id_sitio_area_producto in ($idSitioAreaProducto);";
        
        return $this->modeloSitiosAreasProductos->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada para verificar si existe un sitio/área/producto registrado
     * en una solicitud.
     *
     * @return array|ResultSet
     */
    public function buscarSitioAreaProductoXSolicitud($arrayParametros) {
        
        $consulta = "   SELECT
                        	sap.identificador_sitio, sap.id_sitio, sap.nombre_sitio, sap.id_area,
                        	sap.nombre_area, sap.superficie, sap.id_operacion,
                        	sap.nombre_operacion, sap.id_producto, sap.nombre_producto,
                            sap.estado
                        FROM
                        	g_certificacion_bpa.sitios_areas_productos sap
                        WHERE
                        	sap.id_sitio = ". $arrayParametros['id_sitio'] ." and
                        	sap.id_area = ". $arrayParametros['id_area'] ." and
                        	sap.id_producto = ". $arrayParametros['id_producto'] ." and
                        	sap.id_solicitud  = '". $arrayParametros['id_solicitud'] ."';";
        
        return $this->modeloSitiosAreasProductos->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Cambia el estado de los registros de una solicitud.
     *
     * @return array|ResultSet
     */
    public function cambiarEstadoSitiosAreasProductos($arrayParametros)
    {
        $consulta = "  UPDATE
                        	g_certificacion_bpa.sitios_areas_productos
                        SET
                        	estado = '". $arrayParametros['estado'] ."'
                        WHERE
                            id_solicitud = ". $arrayParametros['id_solicitud'] .";";
        
        return $this->modeloSitiosAreasProductos->ejecutarSqlNativo($consulta);
    }
}