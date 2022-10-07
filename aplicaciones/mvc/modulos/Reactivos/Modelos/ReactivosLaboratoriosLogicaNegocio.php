<?php

/**
 * Lógica del negocio de  ReactivosLaboratoriosModelo
 *
 * Este archivo se complementa con el archivo   ReactivosLaboratoriosControlador.
 *
 * @author DATASTAR
 * @uses       ReactivosLaboratoriosLogicaNegocio
 * @package Reactivos
 * @subpackage Modelo
 */

namespace Agrodb\Reactivos\Modelos;

use Agrodb\Reactivos\Modelos\IModelo;

class ReactivosLaboratoriosLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new ReactivosLaboratoriosModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new ReactivosLaboratoriosModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdReactivoLaboratorio() != null && $tablaModelo->getIdReactivoLaboratorio() > 0)
        {
            unset($datosBd["origen"]);
            unset($datosBd["id_laboratorios_provincia"]);
            $this->modelo->actualizar($datosBd, $tablaModelo->getIdReactivoLaboratorio());
            return $tablaModelo->getIdReactivoLaboratorio();
        } else
        {
            unset($datosBd["id_reactivo_laboratorio"]);
            return $this->modelo->guardar($datosBd);
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
     * @return ReactivosLaboratoriosModelo
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
     * Se suma la cantidad de tipo INGRESO de la tabla saldos_laboratorios ya que es donde
     * esta la cantidad real recibida 
     * @return array|ResultSet
     */
    public function buscarReactivosBodegaSaldos($arrayParametros)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['id_bodega']))
        {
            if (is_array($arrayParametros['id_bodega']))
            {
                $arrayWhere[] = " rbod.id_bodega IN (" . implode(",", $arrayParametros['id_bodega']) . ")";
            } else
            {
                $arrayWhere[] = " rbod.id_bodega = {$arrayParametros['id_bodega']}";
            }
        }
        if (!empty($arrayParametros['nombre']))
        {
            $arrayWhere[] = " UPPER(rbod.nombre) LIKE '%" . strtoupper($arrayParametros['nombre']) . "%'";
        }
        if ($arrayWhere)
        {
            $where = " WHERE" . implode(' AND ', $arrayWhere);
        }
        $consulta = "SELECT
        rbod.id_reactivo_bodega,
        rbod.id_bodega,
        bod.nombre_bodega,
        prov.nombre AS provincia_bodega,
        rbod.nombre,
        rbod.unidad,
        rbod.estado,
        rbod.cantidad,
        COALESCE((SELECT SUM(cantidad)
        FROM g_reactivos.saldos_laboratorios slab
        JOIN g_reactivos.reactivos_laboratorios rlab ON rlab.id_reactivo_laboratorio = slab.id_reactivo_laboratorio
        WHERE tipo_ingreso='INGRESO' AND rlab.id_reactivo_bodega = rbod.id_reactivo_bodega),0) AS egresos
        FROM
        g_reactivos.reactivos_bodega AS rbod
        INNER JOIN g_reactivos.bodegas AS bod ON bod.id_bodega = rbod.id_bodega
        INNER JOIN g_catalogos.localizacion AS prov ON prov.id_localizacion = bod.id_localizacion
        $where
        ORDER BY rbod.nombre";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Buscar los reactivos del laboratorio
     * @param type $idLaboratorio
     * @param type $idReactivoLaboratorio
     * @return type
     */
    public function buscarReactivos($idLaboratorio, $idReactivoLaboratorio = null)
    {
        $where = null;
        if (isset($idReactivoLaboratorio))
        {
            $where = " AND g_reactivos.reactivos_laboratorios.id_reactivo_laboratorio = $idReactivoLaboratorio";
        }
        $consulta = "SELECT
        g_reactivos.reactivos_laboratorios.id_reactivo_laboratorio,
        g_reactivos.reactivos_bodega.unidad,
        g_reactivos.reactivos_bodega.nombre
        FROM
        g_reactivos.reactivos_laboratorios
        INNER JOIN g_reactivos.reactivos_bodega ON g_reactivos.reactivos_bodega.id_reactivo_bodega = g_reactivos.reactivos_laboratorios.id_reactivo_bodega
        WHERE g_reactivos.reactivos_laboratorios.tipo='REACTIVO' AND g_reactivos.reactivos_laboratorios.id_laboratorio = $idLaboratorio $where";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Buscar los reactivos del laboratorio
     * @param type $idLaboratorio
     * @param type $idReactivoLaboratorio
     * @return type
     */
    public function buscarListaReactivos($idLaboratoriosProvincia)
    {
        $consulta = "SELECT
        g_reactivos.reactivos_laboratorios.id_reactivo_laboratorio,
        g_reactivos.reactivos_bodega.unidad,
        g_reactivos.reactivos_bodega.nombre
        FROM
        g_reactivos.reactivos_laboratorios
        INNER JOIN g_reactivos.reactivos_bodega ON g_reactivos.reactivos_bodega.id_reactivo_bodega = g_reactivos.reactivos_laboratorios.id_reactivo_bodega
        WHERE g_reactivos.reactivos_laboratorios.tipo='REACTIVO' 
        AND g_reactivos.reactivos_laboratorios.id_laboratorios_provincia = $idLaboratoriosProvincia";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Buscar los reactivos que de la solución
     * @param type $idSolucion
     * @return type
     */
    public function buscarReactivosSolucion($idSolucion)
    {
        $consulta = "SELECT
        slab.id_solucion,
        sol.nombre AS nombre_solucion,
        slab.cantidad,
        realab.id_reactivo_laboratorio,
        realab.nombre AS nombre_reactivo,
        realab.unidad_medida
        FROM
        g_reactivos.reactivos_laboratorios AS sol
        INNER JOIN g_reactivos.saldos_laboratorios AS slab ON slab.id_solucion = sol.id_reactivo_laboratorio
        INNER JOIN g_reactivos.reactivos_laboratorios AS realab ON realab.id_reactivo_laboratorio = slab.id_reactivo_laboratorio
        WHERE
        sol.id_reactivo_laboratorio = $idSolucion";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Retorna los datos de la tabla g_reactivos.reactivos_laboratorios
     * @param type $arrayParametros
     * @return type
     */
    public function buscarReactivosLaboratorios($arrayParametros = null)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['nombre']))
        {
            $arrayWhere[] = " UPPER(nombre) LIKE '%" . strtoupper($arrayParametros['nombre']) . "%'";
        }
        if (!empty($arrayParametros['id_laboratorios_provincia']))
        {
            $arrayWhere[] = " id_laboratorios_provincia = {$arrayParametros['id_laboratorios_provincia']}";
        }
        if (!empty($arrayParametros['tipo']))
        {
            $arrayWhere[] = " tipo = '{$arrayParametros['tipo']}'";
        }
        if ($arrayWhere)
        {
            $where = "WHERE " . implode(' AND ', $arrayWhere);
        }
        $consulta = "SELECT rlab.id_reactivo_laboratorio, rlab.nombre, rlab.unidad_medida, rlab.volumen_final, rlab.estado_registro, 
        (SELECT COUNT(*) 
        FROM g_reactivos.reactivos_solucion rsol
        WHERE rsol.id_solucion = rlab.id_reactivo_laboratorio AND rsol.estado_registro='ACTIVO') AS total_reactivos_solucion
        FROM g_reactivos.reactivos_laboratorios rlab
        $where ORDER BY rlab.nombre";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

}
