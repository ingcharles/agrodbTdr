<?php

/**
 * Lógica del negocio de  AuditoriaLabLogModelo
 *
 * Este archivo se complementa con el archivo   AuditoriaLabLogControlador.
 *
 * @author DATASTAR
 * @uses       AuditoriaLabLogLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;

class AuditoriaLabLogLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new AuditoriaLabLogModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new AuditoriaLabLogModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getLogId() != null && $tablaModelo->getLogId() > 0)
        {
            return $this->modelo->actualizar($datosBd, $tablaModelo->getLogId());
        } else
        {
            unset($datosBd["log_id"]);
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
     * @return AuditoriaLabLogModelo
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
    public function buscarAuditoriaLabLog()
    {
        $consulta = "SELECT * FROM " . $this->modelo->getEsquema() . ". auditoria_lab_log";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Buscar registros según los parámetros ingresados
     * @param type $arrayParametros
     * @return type
     */
    public function buscarAuditoriaInforme($arrayParametros)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['idLaboratorio']))
        {
            $arrayWhere[] = " id_laboratorio = '{$arrayParametros['idLaboratorio']}'";
        }
        if (!empty($arrayParametros['fechaInicio']) & !empty($arrayParametros['fechaFin']))
        {
            $arrayWhere[] = " log_when::DATE BETWEEN '" . $arrayParametros['fechaInicio'] . "'::DATE AND '" . $arrayParametros['fechaFin'] . "'::DATE";
        }
        if ($arrayWhere)
        {
            $where = implode(' AND ', $arrayWhere);
        }
        if (!empty($where))
        {
            $where = " WHERE " . $where;
        }
        $consulta = "SELECT * FROM " . $this->modelo->getEsquema() . ".v_auditoria_informes"
                . "$where";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

}
