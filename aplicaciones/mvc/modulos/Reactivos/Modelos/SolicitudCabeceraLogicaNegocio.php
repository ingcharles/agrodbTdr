<?php

/**
 * Lógica del negocio de  SolicitudCabeceraModelo
 *
 * Este archivo se complementa con el archivo   SolicitudCabeceraControlador.
 *
 * @author DATASTAR
 * @uses       SolicitudCabeceraLogicaNegocio
 * @package Reactivos
 * @subpackage Modelo
 */

namespace Agrodb\Reactivos\Modelos;

use Agrodb\Reactivos\Modelos\IModelo;

class SolicitudCabeceraLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new SolicitudCabeceraModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new SolicitudCabeceraModelo($datos);
        if ($tablaModelo->getIdSolicitudCabecera() != null && $tablaModelo->getIdSolicitudCabecera() > 0)
        {
            return $this->modelo->actualizar($datos, $tablaModelo->getIdSolicitudCabecera());
        } else
        {
            unset($datos["id_solicitud_cabecera"]);
            return $this->modelo->guardar($datos);
        }
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
     * @return SolicitudCabeceraModelo
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
    public function buscarSolicitudCabecera()
    {
        $consulta = "SELECT * FROM " . $this->modelo->getEsquema() . ". solicitud_cabecera";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Buscar las solicitudes registradas
     * @param type $arrayParametros
     * @return type
     */
    public function buscarSolicitudes($arrayParametros)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['id_bodega']))
        {
            if (is_array($arrayParametros['id_bodega']))
            {
                $arrayWhere[] = " solcab.id_bodega IN (" . implode(",", $arrayParametros['id_bodega']) . ")";
            } else
            {
                $arrayWhere[] = " solcab.id_bodega = {$arrayParametros['id_bodega']}";
            }
        }
        if (!empty($arrayParametros['estado']))
        {
            if (is_array($arrayParametros['estado']))
            {
                $arrayWhere[] = " solcab.estado IN ('" . implode("','", $arrayParametros['estado']) . "')";
            } else
            {
                $arrayWhere[] = " solcab.estado = '{$arrayParametros['estado']}'";
            }
        }
        if (!empty($arrayParametros['id_laboratorios_provincia']))
        {
            if (is_array($arrayParametros['id_laboratorios_provincia']))
            {
                $arrayWhere[] = " solcab.id_laboratorios_provincia IN (" . implode(",", $arrayParametros['id_laboratorios_provincia']) . ")";
            } else
            {
                $arrayWhere[] = " solcab.id_laboratorios_provincia = " . $arrayParametros['id_laboratorios_provincia'];
            }
        }
        if (!empty($arrayParametros['tipo']))
        {
            $arrayWhere[] = " solcab.tipo = '{$arrayParametros['tipo']}'";
        }
        if ($arrayWhere)
        {
            $where = " WHERE" . implode(' AND ', $arrayWhere);
        }
        $consulta = "SELECT
        solcab.id_solicitud_cabecera,
        solcab.id_laboratorio,
        solcab.fecha_solicitud,
        solcab.codigo,
        solcab.tipo,
        solcab.estado,
        solcab.observacion,
        solcab.id_laboratorios_provincia,
        solcab.id_bodega,
        bod.id_localizacion,
        bod.nombre_bodega AS nombre_origen,
        prov.nombre AS provincia_origen,
        lab.nombre AS laboratorio_solicita
        FROM
        g_reactivos.solicitud_cabecera AS solcab
        INNER JOIN g_reactivos.bodegas AS bod ON bod.id_bodega = solcab.id_bodega
        INNER JOIN g_catalogos.localizacion AS prov ON prov.id_localizacion = bod.id_localizacion
        INNER JOIN g_laboratorios.laboratorios_provincia AS lprov ON solcab.id_laboratorios_provincia = lprov.id_laboratorios_provincia
        INNER JOIN g_laboratorios.laboratorios AS lab ON lab.id_laboratorio = lprov.id_laboratorio
        $where
        ORDER BY solcab.id_solicitud_cabecera DESC";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Retorna las solicitudes a bodega
     * @param type $arrayParametros
     * @return type
     */
    public function buscarSolicitudesTodas($arrayParametros)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['id_bodega']))
        {
            if (is_array($arrayParametros['id_bodega']))
            {
                $arrayWhere[] = " solcab.id_bodega IN (" . implode(",", $arrayParametros['id_bodega']) . ")";
            } else
            {
                $arrayWhere[] = " solcab.id_bodega = {$arrayParametros['id_bodega']}";
            }
        }
        if (!empty($arrayParametros['estado']))
        {
            if (is_array($arrayParametros['estado']))
            {
                $arrayWhere[] = " solcab.estado IN ('" . implode("','", $arrayParametros['estado']) . "')";
            } else
            {
                $arrayWhere[] = " solcab.estado = '{$arrayParametros['estado']}'";
            }
        }
        if (!empty($arrayParametros['id_laboratorios_provincia']))
        {
            if (is_array($arrayParametros['id_laboratorios_provincia']))
            {
                $arrayWhere[] = " solcab.id_laboratorios_provincia IN (" . implode(",", $arrayParametros['id_laboratorios_provincia']) . ")";
            } else
            {
                $arrayWhere[] = " solcab.id_laboratorios_provincia = " . $arrayParametros['id_laboratorios_provincia'];
            }
        }
        if (!empty($arrayParametros['tipo']))
        {
            $arrayWhere[] = " solcab.tipo = '{$arrayParametros['tipo']}'";
        }
        if ($arrayWhere)
        {
            $where = " WHERE" . implode(' AND ', $arrayWhere);
        }
        $consulta = "SELECT
        solcab.id_solicitud_cabecera,
        solcab.tipo,
        solcab.fecha_solicitud,
        solcab.codigo,
        solcab.estado,
        solcab.observacion,
        solcab.id_laboratorios_provincia,
        solcab.id_bodega AS id_origen,
        bod.nombre_bodega AS nombre_origen,
        prov.nombre AS provincia_origen,
        lab.nombre AS laboratorio_solicita
        FROM
        g_reactivos.solicitud_cabecera AS solcab
        INNER JOIN g_reactivos.bodegas AS bod ON bod.id_bodega = solcab.id_bodega
        INNER JOIN g_catalogos.localizacion AS prov ON prov.id_localizacion = bod.id_localizacion
        INNER JOIN g_laboratorios.laboratorios_provincia AS lprov ON solcab.id_laboratorios_provincia = lprov.id_laboratorios_provincia
        INNER JOIN g_laboratorios.laboratorios AS lab ON lab.id_laboratorio = lprov.id_laboratorio
        $where
        UNION
        SELECT
        solcab.id_solicitud_cabecera,
        solcab.tipo,
        solcab.fecha_solicitud,
        solcab.codigo,
        solcab.estado,
        solcab.observacion,
        solcab.id_laboratorios_provincia,
        solcab.id_laboratorios_provincia_origen AS id_origen,
        labor.nombre AS nombre_origen,
        locor.nombre AS privincia_origen,
        lab.nombre AS laboratorio_solicita
        FROM
        g_reactivos.solicitud_cabecera AS solcab
        INNER JOIN g_laboratorios.laboratorios_provincia AS lprovor ON lprovor.id_laboratorios_provincia = solcab.id_laboratorios_provincia_origen
        INNER JOIN g_catalogos.localizacion AS locor ON locor.id_localizacion = lprovor.id_localizacion
        INNER JOIN g_laboratorios.laboratorios AS labor ON labor.id_laboratorio = lprovor.id_laboratorio
        INNER JOIN g_laboratorios.laboratorios_provincia AS lprov ON lprov.id_laboratorios_provincia = solcab.id_laboratorios_provincia
        INNER JOIN g_laboratorios.laboratorios AS lab ON lab.id_laboratorio = lprov.id_laboratorio
        $where
        ORDER BY id_solicitud_cabecera DESC";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }
}
