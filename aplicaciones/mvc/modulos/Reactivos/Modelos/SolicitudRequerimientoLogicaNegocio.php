<?php

/**
 * Lógica del negocio de  SolicitudRequerimientoModelo
 *
 * Este archivo se complementa con el archivo   SolicitudRequerimientoControlador.
 *
 * @author DATASTAR
 * @uses       SolicitudRequerimientoLogicaNegocio
 * @package Reactivos
 * @subpackage Modelo
 */

namespace Agrodb\Reactivos\Modelos;

use Agrodb\Reactivos\Modelos\IModelo;
use Agrodb\Core\ValidarDatos;

class SolicitudRequerimientoLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new SolicitudRequerimientoModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {

        $tablaModelo = new SolicitudRequerimientoModelo($datos);
        if ($tablaModelo->getIdSolicitudRequerimiento() != null && $tablaModelo->getIdSolicitudRequerimiento() > 0)
        {
            return $this->modelo->actualizar($datos, $tablaModelo->getIdSolicitudRequerimiento());
        } else
        {
            unset($datos["id_solicitud_requerimiento"]);
            return $this->modelo->guardar($datos);
        }
    }

    /**
     * Actualiza las cantidades solicitadas
     * @param array $datos
     */
    public function actualizarCantidad(Array $datos)
    {
        //actualizar las cantidades en solicitud_requerimiento
        $cantidades = $datos['cantidad'];
        foreach ($cantidades as $clave => $valor)
        {
            if (!empty($valor))
            {
                $dbvalor = ValidarDatos::validarDecimal($valor);
                $dbDatos = array("cantidad_solicitada" => $dbvalor);
                $this->modelo->actualizar($dbDatos, $clave);
            }
        }
        //actualizar la observación en solicitud_cabecera
        $modeloSolicitudCabecera = new SolicitudCabeceraModelo($datos);
        $datosBd = $modeloSolicitudCabecera->getPrepararDatos();
        $modeloSolicitudCabecera->actualizar($datosBd, $modeloSolicitudCabecera->getIdSolicitudCabecera());
    }

    /**
     * Borra el registro actual
     * @param string Where|array $where
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
     * @param  int $id
     * @return SolicitudRequerimientoModelo
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
     * Busca las solicitud activa de un laboratorio
     *
     * @return array|ResultSet
     */
    public function buscarSolicitudRequerimiento($idSolicitudCabecera)
    {
        $consulta = "SELECT
        rbod.codigo_bodega,
        rbod.nombre,
        sr.cantidad_solicitada,
        sr.id_solicitud_requerimiento,
        rl.id_laboratorio,
        sr.id_solicitud_cabecera,
        rbod.unidad,
        rbod.cantidad,
        COALESCE((SELECT SUM(cantidad)
                FROM g_reactivos.saldos_laboratorios slab
                JOIN g_reactivos.reactivos_laboratorios rlab ON rlab.id_reactivo_laboratorio = slab.id_reactivo_laboratorio
                WHERE tipo_ingreso='INGRESO' AND rlab.id_reactivo_bodega = rbod.id_reactivo_bodega),0) AS egresos
        FROM
        g_reactivos.solicitud_requerimiento AS sr
        INNER JOIN g_reactivos.reactivos_laboratorios AS rl ON rl.id_reactivo_laboratorio = sr.id_reactivo_laboratorio
        INNER JOIN g_reactivos.reactivos_bodega AS rbod ON rbod.id_reactivo_bodega = rl.id_reactivo_bodega
        WHERE sr.id_solicitud_cabecera=" . $idSolicitudCabecera . " ORDER BY rbod.nombre ASC";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarSaldos($idLaboratorio)
    {
        $consulta = "SELECT
        rbo.codigo_bodega,
        rbo.nombre,
        rbo.cantidad AS saldo_bodega,
        rbo.unidad,
        COALESCE(slab.cantidad,0) AS saldo_laboratorio,
        rla.id_laboratorio,
        rla.id_reactivo_laboratorio
        FROM
        g_reactivos.reactivos_bodega rbo
        LEFT  JOIN g_reactivos.reactivos_laboratorios rla ON rbo.id_reactivo_bodega = rla.id_reactivo_bodega
        LEFT JOIN g_reactivos.solicitud_requerimiento solr ON rla.id_reactivo_laboratorio = solr.id_reactivo_laboratorio
        LEFT JOIN g_reactivos.saldos_laboratorios slab ON solr.id_solicitud_requerimiento = slab.id_solicitud_requerimiento
        WHERE rla.id_reactivo_laboratorio = " . $idLaboratorio . " OR rla.id_reactivo_laboratorio IS NULL";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Retrorna los reactivos tipo SOLUCION
     * @param type $idSolicitudCabecera
     * @return type
     */
    public function buscarReactivosSolucion($idSolicitudCabecera)
    {
        $consulta = "SELECT
        sreq.id_solicitud_requerimiento,
        rlab.id_reactivo_laboratorio,
        rlab.nombre,
        sreq.cantidad_solicitada,
        scab.tipo,
        rlab.unidad_medida
        FROM
        g_reactivos.solicitud_requerimiento AS sreq
        INNER JOIN g_reactivos.reactivos_laboratorios AS rlab ON rlab.id_reactivo_laboratorio = sreq.id_reactivo_laboratorio
        INNER JOIN g_reactivos.solicitud_cabecera AS scab ON scab.id_solicitud_cabecera = sreq.id_solicitud_cabecera
        WHERE scab.id_solicitud_cabecera = $idSolicitudCabecera AND scab.tipo = 'SOLUCION'";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }
}
