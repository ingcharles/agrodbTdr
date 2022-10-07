<?php

/**
 * Lógica del negocio de  SolicitudesModelo
 *
 * Este archivo se complementa con el archivo   SolicitudesControlador.
 * https://docs.zendframework.com/zend-db/sql/
 * 
 * @author DATASTAR
 * @uses       SolicitudesLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;

class BandejaresponsabletecnicoLogicaNegocio implements IModelo
{

    private $modeloSolicitud = null;
    private $esquema = null;
    private $modelo = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloSolicitud = new SolicitudesModelo();
        $this->modelo = new SolicitudesModelo();
        $esquema = $this->modelo->getEsquema();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        
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
     * @return SolicitudesModelo
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
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarListaParametros($arrayParametros = null, $order = null, $count = null, $offset = null)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['estado']))
        {
            $arrayWhere[] = " estado = '{$arrayParametros['estado']}'";
        }
        if (!empty($arrayParametros['codigo']))
        {
            $arrayWhere[] = "UPPER(codigo) LIKE '%" . strtoupper($arrayParametros['codigo']) . "%'";
        }
        if (!empty($arrayParametros['cliente']))
        {
            $arrayWhere[] = "UPPER(cliente) LIKE '%" . strtoupper($arrayParametros['cliente']) . "%'";
        }
        if ($arrayWhere)
        {
            $where = "WHERE " . implode(' AND ', $arrayWhere);
        }
        $consulta = "SELECT * from g_laboratorios.v_valor_total_solicitud $where";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Retorna las ordenes de trabajo con el valor total
     * @param type $arrayParametros
     * @return type
     */
    public function buscarOrdenesTrabajo($arrayParametros = null)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['estado_orden']))
        {
            if (is_array($arrayParametros['estado_orden']))
            {
                $arrayWhere[] = " estado_orden IN ('" . implode("','", $arrayParametros['estado_orden']) . "')";
            } else
            {
                $arrayWhere[] = " estado_orden = '{$arrayParametros['estado_orden']}'";
            }
        }
        if (!empty($arrayParametros['codigo_ot']))
        {
            $arrayWhere[] = "UPPER(codigo_ot) LIKE '%" . strtoupper($arrayParametros['codigo_ot']) . "%'";
        }
        if ($arrayWhere)
        {
            $where = "WHERE " . implode(' AND ', $arrayWhere);
        }
        $consulta = "SELECT * from g_laboratorios.v_valor_total_orden $where";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Retorna la ordenes de trabajo con el valor total
     * @param type $arrayParametros
     * @return type
     */
    public function buscarBandejaOT($arrayParametros = null)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['estado_orden']))
        {
            if (is_array($arrayParametros['estado_orden']))
            {
                $arrayWhere[] = " estado_orden IN ('" . implode("','", $arrayParametros['estado_orden']) . "')";
            } else
            {
                $arrayWhere[] = " estado_orden = '{$arrayParametros['estado_orden']}'";
            }
        }
        if (!empty($arrayParametros['codigo_ot']))
        {
            $arrayWhere[] = " UPPER(codigo_ot) LIKE '%" . strtoupper($arrayParametros['codigo_ot']) . "%'";
        }
        if (!empty($arrayParametros['id_laboratorios_provincia']))
        {
            $arrayWhere[] = " ot.id_laboratorios_provincia = {$arrayParametros['id_laboratorios_provincia']}";
        }
        if ($arrayWhere)
        {
            $where = "WHERE " . implode(' AND ', $arrayWhere);
        }
        $consulta = "SELECT
        ot.id_orden_trabajo,
        ot.codigo as codigo_ot,
        ot.estado as estado_orden,
        totorden.cliente,
        totorden.total_orden,
        totorden.id_solicitud,
        ot.id_laboratorio,
        ot.id_laboratorios_provincia,
        ot.fecha_activacion
        FROM
        g_laboratorios.ordenes_trabajos AS ot
        INNER JOIN g_laboratorios.v_valor_total_orden AS totorden ON ot.id_orden_trabajo = totorden.id_orden_trabajo
        $where
        ORDER BY totorden.fecha_activacion DESC";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Retorna la ordenes de trabajo
     * @param type $arrayParametros
     * @return type
     */
    public function buscarOrdenesT($arrayParametros = null)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['estado_orden']))
        {
            if (is_array($arrayParametros['estado_orden']))
            {
                $arrayWhere[] = " ot.estado IN ('" . implode("','", $arrayParametros['estado_orden']) . "')";
            } else
            {
                $arrayWhere[] = " ot.estado = '{$arrayParametros['estado_orden']}'";
            }
        }
        if (!empty($arrayParametros['codigo_ot']))
        {
            $arrayWhere[] = " UPPER(ot.codigo) LIKE '%" . strtoupper($arrayParametros['codigo_ot']) . "%'";
        }
        if (!empty($arrayParametros['id_laboratorios_provincia']))
        {
            $arrayWhere[] = " ot.id_laboratorios_provincia = {$arrayParametros['id_laboratorios_provincia']}";
        }
        if ($arrayWhere)
        {
            $where = "WHERE " . implode(' AND ', $arrayWhere);
        }
        $consulta = "SELECT
        ot.id_orden_trabajo,
        ot.id_solicitud,
        ot.codigo AS codigo_ot,
        ot.estado AS estado_orden,
        ot.id_laboratorio,
        ot.id_laboratorios_provincia,
        ot.fecha_activacion,
        sol.usuario_guia,
        (SELECT nombre FROM g_laboratorios.f_datos_usuario_ie(sol.usuario_guia)) AS cliente
        FROM
        g_laboratorios.ordenes_trabajos AS ot
        INNER JOIN g_laboratorios.solicitudes AS sol ON sol.id_solicitud = ot.id_solicitud
        $where
        ORDER BY ot.fecha_activacion DESC";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }
/**
 * Ordenes de trabajo para ser analizadas
 * @param type $idLaboratoriosProvincia
 * @return type
 */
    public function buscarOrdenesAnalizar($idLaboratoriosProvincia)
    {
        $consulta = "SELECT
        ot.id_orden_trabajo,
        ot.id_solicitud,
        ot.codigo AS codigo_ot,
        ot.estado AS estado_orden,
        ot.id_laboratorio,
        ot.id_laboratorios_provincia,
        ot.fecha_activacion,
        sol.usuario_guia,
        (SELECT nombre FROM g_laboratorios.f_datos_usuario_ie(sol.usuario_guia)) AS cliente
        FROM
        g_laboratorios.ordenes_trabajos AS ot
        INNER JOIN g_laboratorios.solicitudes AS sol ON sol.id_solicitud = ot.id_solicitud
        WHERE id_laboratorios_provincia=".$idLaboratoriosProvincia." AND	ot.id_orden_trabajo IN (SELECT
        ot.id_orden_trabajo
        FROM
        g_laboratorios.ordenes_trabajos AS ot
        INNER JOIN g_laboratorios.recepcion_muestras AS rm ON ot.id_orden_trabajo = rm.id_orden_trabajo
        WHERE rm.estado_actual IN ('IDONEA','NO APROBADO')) ORDER BY ot.codigo ASC"; 
        
        return $this->modelo->ejecutarSqlNativo($consulta);
    }
    
    public function buscarOrdenesValidar($idLaboratoriosProvincia)
    {
        $consulta = "SELECT
        ot.id_orden_trabajo,
        ot.id_solicitud,
        ot.codigo AS codigo_ot,
        ot.estado AS estado_orden,
        ot.id_laboratorio,
        ot.id_laboratorios_provincia,
        ot.fecha_activacion,
        sol.usuario_guia,
        (SELECT nombre FROM g_laboratorios.f_datos_usuario_ie(sol.usuario_guia)) AS cliente
        FROM
        g_laboratorios.ordenes_trabajos AS ot
        INNER JOIN g_laboratorios.solicitudes AS sol ON sol.id_solicitud = ot.id_solicitud
        WHERE id_laboratorios_provincia=".$idLaboratoriosProvincia." AND	ot.id_orden_trabajo IN (SELECT
        ot.id_orden_trabajo
        FROM
        g_laboratorios.ordenes_trabajos AS ot
        INNER JOIN g_laboratorios.recepcion_muestras AS rm ON ot.id_orden_trabajo = rm.id_orden_trabajo
        WHERE rm.estado_actual='ANALIZADA')"; 
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

}
