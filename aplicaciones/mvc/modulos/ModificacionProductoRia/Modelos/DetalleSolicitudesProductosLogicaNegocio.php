<?php
/**
 * Lógica del negocio de DetalleSolicitudesProductosModelo
 *
 * Este archivo se complementa con el archivo DetalleSolicitudesProductosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-13
 * @uses    DetalleSolicitudesProductosLogicaNegocio
 * @package ModificacionProductoRia
 * @subpackage Modelos
 */

namespace Agrodb\ModificacionProductoRia\Modelos;

use Agrodb\ModificacionProductoRia\Modelos\IModelo;
use Agrodb\Core\Excepciones\GuardarExcepcion;

class DetalleSolicitudesProductosLogicaNegocio implements IModelo
{

    private $modeloDetalleSolicitudesProductos = null;


    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloDetalleSolicitudesProductos = new DetalleSolicitudesProductosModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(array $datos)
    {
        try {

        $tablaModelo = new DetalleSolicitudesProductosModelo($datos);
        
        $procesoIngreso = $this->modeloDetalleSolicitudesProductos->getAdapter()
        ->getDriver()
        ->getConnection();
        $procesoIngreso->beginTransaction();
        
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdDetalleSolicitudProducto() != null && $tablaModelo->getIdDetalleSolicitudProducto() > 0) {
            $idDetalleSolicitudProducto = $this->modeloDetalleSolicitudesProductos->actualizar($datosBd, $tablaModelo->getIdDetalleSolicitudProducto());
        } else {
            unset($datosBd["id_detalle_solicitud_producto"]);
            $idDetalleSolicitudProducto = $this->modeloDetalleSolicitudesProductos->guardar($datosBd);
        }
                
        $procesoIngreso->commit();
        return $idDetalleSolicitudProducto;
        } catch (GuardarExcepcion $ex) {
            $procesoIngreso->rollback();
            throw new \Exception($ex->getMessage());
        }        
        
    }
    
    /**
     * Borra el registro actual
     * @param string Where|array $where
     * @return int
     */
    public function borrar($id)
    {
        $this->modeloDetalleSolicitudesProductos->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return DetalleSolicitudesProductosModelo
     */
    public function buscar($id)
    {
        return $this->modeloDetalleSolicitudesProductos->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloDetalleSolicitudesProductos->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloDetalleSolicitudesProductos->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarDetalleSolicitudesProductos()
    {
        $consulta = "SELECT * FROM " . $this->modeloDetalleSolicitudesProductos->getEsquema() . ". detalle_solicitudes_productos";
        return $this->modeloDetalleSolicitudesProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Obtener datos de tipos de modificacion.
     *
     * @return array|ResultSet
     */
    public function obtenerDetallesSolicitudesModificacionProducto($idSolicitudProducto)
    {
        $consulta = "SELECT 
                        * 
                    FROM g_modificacion_productos.detalle_solicitudes_productos dsp
                    INNER JOIN g_catalogos.tipo_modificacion_producto tmp ON tmp.id_tipo_modificacion_producto = dsp.id_tipo_modificacion_producto
                    WHERE dsp.id_solicitud_producto = '".$idSolicitudProducto."'
                    ORDER BY dsp.id_detalle_solicitud_producto ASC";

        return $this->modeloDetalleSolicitudesProductos->ejecutarSqlNativo($consulta);
    }

}
