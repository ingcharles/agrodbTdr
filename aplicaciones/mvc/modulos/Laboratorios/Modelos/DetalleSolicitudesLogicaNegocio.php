<?php
/**
 * Lógica del negocio de  DetalleSolicitudesModelo
 *
 * Este archivo se complementa con el archivo   DetalleSolicitudesControlador.
 *
 * @author DATASTAR
 * @uses       DetalleSolicitudesLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */
namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;

class DetalleSolicitudesLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new DetalleSolicitudesModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new DetalleSolicitudesModelo($datos);
        if ($tablaModelo->getIdDetalleSolicitud() != null && $tablaModelo->getIdDetalleSolicitud() > 0) {
            return $this->modelo->actualizar($datos, $tablaModelo->getIdDetalleSolicitud());
        } else {
            unset($datos["id_detalle_solicitud"]);
            return $this->modelo->guardar($datos);
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
        $this->modelo->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return DetalleSolicitudesModelo
     */
    public function buscar($id)
    {
        return $this->modelo->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modelo->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modelo->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarDetalleSolicitudes()
    {
        $consulta = "SELECT * FROM g_laboratorios.detalle_solicitudes";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }
    
    /**
     * OJO No se usa
     * @param type $idDetalleSolicitud
     * @return type
     */
    public function buscarDetalleSolicitud($idDetalleSolicitud){
        $consulta = "SELECT
        direccion.id_laboratorio AS id_direccion,
        direccion.nombre AS nom_direccion,
        g_laboratorios.laboratorios.id_laboratorio AS id_laboratorio,
        g_laboratorios.laboratorios.nombre AS nom_laboratorio,
        g_laboratorios.servicios.id_servicio,
        g_laboratorios.servicios.nombre AS nom_servicio,
        g_financiero.servicios.valor,
        Count(*) AS numero_muestras
        FROM
        g_laboratorios.detalle_solicitudes
        INNER JOIN g_laboratorios.servicios ON g_laboratorios.detalle_solicitudes.id_servicio = g_laboratorios.servicios.id_servicio
        INNER JOIN g_financiero.servicios ON g_laboratorios.servicios.id_servicio_guia = g_financiero.servicios.id_servicio
        INNER JOIN g_laboratorios.tipo_analisis ON g_laboratorios.detalle_solicitudes.id_detalle_solicitud = g_laboratorios.tipo_analisis.id_detalle_solicitud
        INNER JOIN g_laboratorios.laboratorios ON g_laboratorios.servicios.id_laboratorio = g_laboratorios.laboratorios.id_laboratorio
        INNER JOIN g_laboratorios.laboratorios direccion ON g_laboratorios.laboratorios.fk_id_laboratorio = direccion.id_laboratorio
        WHERE
        g_laboratorios.detalle_solicitudes.id_detalle_solicitud = $idDetalleSolicitud
        GROUP BY
        direccion.id_laboratorio,
        direccion.nombre,
        g_laboratorios.laboratorios.id_laboratorio,
        g_laboratorios.laboratorios.nombre,
        g_laboratorios.servicios.id_servicio,
        g_laboratorios.servicios.nombre,
        g_financiero.servicios.valor";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Consultar datos del servicio con el valor de la vista v_serviciovalor 
     * @param type $idSolicitud
     * @return type
     */
    public function listaDetalleSolicitudes($idSolicitud)
    {
        $consulta = "SELECT * from g_laboratorios.v_valor_total WHERE id_solicitud = $idSolicitud";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }
    
   /**
    * Columnas para guardar junto con la solicitud
    * @return string[]
    */
    public function columnas()
    {
        $columnas = array(
            'id_servicio',
            'id_solicitud',
            'tiempo_estimado',
            'observacion'
        );
        
        return $columnas;
    }
    
}
