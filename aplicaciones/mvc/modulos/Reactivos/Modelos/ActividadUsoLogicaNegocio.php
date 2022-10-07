<?php

/**
 * Lógica del negocio de  ActividadUsoModelo
 *
 * Este archivo se complementa con el archivo   ActividadUsoControlador.
 *
 * @author DATASTAR
 * @uses       ActividadUsoLogicaNegocio
 * @package Reactivos
 * @subpackage Modelo
 */

namespace Agrodb\Reactivos\Modelos;

use Agrodb\Reactivos\Modelos\IModelo;

class ActividadUsoLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new ActividadUsoModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new ActividadUsoModelo($datos);
        if ($tablaModelo->getIdActividadUso() != null && $tablaModelo->getIdActividadUso() > 0)
        {
            return $this->modelo->actualizar($datos, $tablaModelo->getIdActividadUso());
        } else
        {
            unset($datos["id_actividad_uso"]);
            $datos['fecha'] = date('Y-m-d');
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
     * @return ActividadUsoModelo
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
    public function buscarActividadUso($idServicio)
    {
        $consulta = "SELECT
        g_reactivos.actividad_uso.id_actividad_uso,
        g_reactivos.actividad_uso.id_servicio,
        g_reactivos.actividad_uso.id_laboratorio,
        g_reactivos.actividad_uso.id_reactivo_bodega,
        g_reactivos.actividad_uso.cantidad,
        g_reactivos.actividad_uso.fecha,
        g_reactivos.actividad_uso.estado,
        g_reactivos.actividad_uso.tipo_procedimiento,
        g_reactivos.actividad_uso.observaciones,
        g_laboratorios.servicios.nombre AS nom_servicio,
        g_reactivos.reactivos_bodega.nombre AS nom_reactivo
        FROM
        g_reactivos.actividad_uso
        INNER JOIN g_laboratorios.servicios ON g_laboratorios.servicios.id_servicio = g_reactivos.actividad_uso.id_servicio
        INNER JOIN g_reactivos.reactivos_bodega ON g_reactivos.reactivos_bodega.id_reactivo_bodega = g_reactivos.actividad_uso.id_reactivo_bodega
        WHERE
        (SELECT (((string_to_array((g_laboratorios.f_path_servicio(g_reactivos.actividad_uso.id_servicio))::text, '/'::text))::character varying[])[2])::integer) = $idServicio";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Retorna los reactivos del laboratorio
     * @param type $idServicio
     * @param type $idLaboratoriosProvincia
     * @return type
     */
    public function buscarReactivosActividadUso($idServicio, $idLaboratoriosProvincia)
    {
        $consulta = "SELECT
        auso.id_actividad_uso,
        rlab.nombre,
        auso.cantidad,
        auso.estado,
        auso.tipo_procedimiento,
        auso.observaciones,
        rlab.unidad_medida
        FROM
        g_reactivos.actividad_uso AS auso
        INNER JOIN g_reactivos.reactivos_laboratorios AS rlab ON rlab.id_reactivo_laboratorio = auso.id_reactivo_laboratorio
        WHERE auso.id_servicio = $idServicio AND auso.id_laboratorios_provincia = $idLaboratoriosProvincia";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }
}
