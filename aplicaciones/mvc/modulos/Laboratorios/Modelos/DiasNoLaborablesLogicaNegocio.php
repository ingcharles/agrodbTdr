<?php

/**
 * Lógica del negocio de  DiasNoLaborablesModelo
 *
 * Este archivo se complementa con el archivo   DiasNoLaborablesControlador.
 *
 * @author DATASTAR
 * @uses       DiasNoLaborablesLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;

class DiasNoLaborablesLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new DiasNoLaborablesModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new DiasNoLaborablesModelo($datos);
        $anio = date("Y", strtotime($datos['fecha']));
        $datos["anio"] = $anio;
        if ($tablaModelo->getIdDiasNoLaborables() != null && $tablaModelo->getIdDiasNoLaborables() > 0)
        {
            if (empty($tablaModelo->getIdDireccion()) || $tablaModelo->getIdDireccion() <= 0)
            {
                $datos["alcance"] = "TODOS";
                unset($datos["id_direccion"]);
                unset($datos["id_laboratorio"]);
            } else
            {
                $datos["alcance"] = "INDIVIDUAL";
            }
            return $this->modelo->actualizar($datos, $tablaModelo->getIdDiasNoLaborables());
        } else
        {
            if (empty($tablaModelo->getIdDireccion()) || $tablaModelo->getIdDireccion() <= 0)
            {
                $datos["alcance"] = "TODOS";
                unset($datos["id_direccion"]);
                unset($datos["id_laboratorio"]);
            } else
            {
                $datos["alcance"] = "INDIVIDUAL";
            }

            unset($datos["id_dias_no_laborables"]);
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
     * @return DiasNoLaborablesModelo
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
    public function buscarDiasNoLaborables()
    {
        $consulta = "SELECT * FROM " . $this->modelo->getEsquema() . ". dias_no_laborables";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

}
