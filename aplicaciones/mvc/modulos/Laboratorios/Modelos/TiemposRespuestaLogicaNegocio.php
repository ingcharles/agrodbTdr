<?php

/**
 * Lógica del negocio de  TiemposRespuestaModelo
 *
 * Este archivo se complementa con el archivo   TiemposRespuestaControlador.
 *
 * @author DATASTAR
 * @uses       TiemposRespuestaLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\laboratorios\Modelos\IModelo;

class TiemposRespuestaLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new TiemposRespuestaModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new TiemposRespuestaModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdTiemposRespuesta() != null && $tablaModelo->getIdTiemposRespuesta() > 0)
        {
            return $this->modelo->actualizar($datosBd, $tablaModelo->getIdTiemposRespuesta());
        } else
        {
            unset($datosBd["id_tiempos_respuesta"]);
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
     * @return TiemposRespuestaModelo
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
    public function buscarTiemposRespuesta()
    {
        $consulta = "SELECT * FROM " . $this->modelo->getEsquema() . ". tiempos_respuesta";
        return $this->modelo->ejecutarConsulta($consulta);
    }

    /**
     * Busca tiempos de respuesta puede ser de todo el laboratorio o de un servicio
     * @param type $idLaboratorio
     * @param type $idServicio
     * @return type
     */
    public function buscarListaTiempos($arrayParametros = null)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['idDireccion']))
        {
            $arrayWhere[] = " tr.id_direccion = {$arrayParametros['idDireccion']}";
        }
        if (!empty($arrayParametros['idLaboratorio']))
        {
            $arrayWhere[] = " tr.id_laboratorio = {$arrayParametros['idLaboratorio']}";
        }

        if (!empty($arrayParametros['idServicio']))
        {
            $arrayWhere[] = " tr.id_servicio = {$arrayParametros['idServicio']}";
        }

        if (!empty($arrayParametros['id_laboratorios_provincia']))
        {
            $arrayWhere[] = " tr.id_laboratorios_provincia = {$arrayParametros['id_laboratorios_provincia']}";
        }

        if ($arrayWhere)
        {
            $where = "WHERE " . implode(' AND ', $arrayWhere);
        }
        $consulta = "SELECT
        tr.id_tiempos_respuesta,
        tr.id_direccion,
        tr.id_laboratorio,
        tr.id_servicio,
        d.nombre AS nombre_direccion,
        l.nombre AS nombre_laboratorio,
        s.nombre AS nombre_servicio,
        s.rama_nombre,
        tr.condicion,
        tr.tiempo_respuesta,
        tr.tipo_usuario,
        tr.tipo_laboratorio
        FROM
        g_laboratorios.tiempos_respuesta tr
        INNER JOIN g_laboratorios.laboratorios d ON d.id_laboratorio = tr.id_direccion
        INNER JOIN g_laboratorios.laboratorios l ON l.id_laboratorio =tr.id_laboratorio 
        INNER JOIN g_laboratorios.servicios s  ON s.id_servicio = tr.id_servicio
        $where
        ORDER BY s.nombre";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Buscar el tiempo de respuesta
     * @param type $tipoUsuario
     * @param type $rama
     * @param type $tipoLaboratorio
     * @return type
     */
    public function buscarTiempos($tipoUsuario, $rama, $tipoLaboratorio, $idLaboratoriosProvincia = 0)
    {
        if ($tipoUsuario == 'INTERNO')
        {
            $tipo = "interno='SI'";
        } else
        {
            $tipo = "externo='SI'";
        }
        $datos = null;

        //Buscamos primero si existe el tiempo por laboratorio
        if ($idLaboratoriosProvincia > 0)
        {
            $consulta = "SELECT vtr.condicion, vtr.tiempo_respuesta, s.nivel, s.rama as nivel_servicio FROM g_laboratorios.v_tiempos_respuesta  vtr
            JOIN g_laboratorios.servicios s ON s.id_servicio=vtr.id_servicio
            WHERE $tipo AND vtr.id_servicio in ($rama) AND id_laboratorios_provincia = $idLaboratoriosProvincia AND estado_registro='ACTIVO' ORDER BY nivel;";
            $resultado = $this->modelo->ejecutarSqlNativo($consulta);
            if ($resultado->count() > 0)
            {
                $datos = $resultado;
            }
        }
        //Si no existe datos aplicamos el fitro de acuerdo al tipo de laboratorio
        if ($datos == null)
        {
            $consulta = "SELECT vtr.condicion, vtr.tiempo_respuesta, s.nivel, s.rama as nivel_servicio FROM g_laboratorios.v_tiempos_respuesta  vtr
            JOIN g_laboratorios.servicios s ON s.id_servicio=vtr.id_servicio
            WHERE $tipo AND vtr.id_servicio in ($rama) AND tipo_laboratorio = '$tipoLaboratorio' AND estado_registro='ACTIVO' ORDER BY nivel;";
            $datos = $this->modelo->ejecutarSqlNativo($consulta);
        }
        return $datos;
    }

}
