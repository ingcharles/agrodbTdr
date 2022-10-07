<?php

/**
 * Lógica del negocio de  InformeResultadosAnalisisModelo
 *
 * Este archivo se complementa con el archivo   InformeResultadosAnalisisControlador.
 *
 * @author DATASTAR
 * @uses       InformeResultadosAnalisisLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;

class InformeResultadosAnalisisLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new InformeResultadosAnalisisModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new InformeResultadosAnalisisModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdInformeAnalisis() != null && $tablaModelo->getIdInformeAnalisis() > 0)
        {
            return $this->modelo->actualizar($datosBd, $tablaModelo->getIdInformeAnalisis());
        } else
        {
            unset($datosBd["id_informe_analisis"]);
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
     * @return InformeResultadosAnalisisModelo
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
    public function buscarInformeResultadosAnalisis()
    {
        $consulta = "SELECT * FROM " . $this->modelo->getEsquema() . ". informe_resultados_analisis";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

}
