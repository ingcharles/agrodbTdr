<?php

/**
 * Lógica del negocio de  MarbetesModelo
 *
 * Este archivo se complementa con el archivo   MarbetesControlador.
 * https://docs.zendframework.com/zend-db/sql/
 * 
 * @author DATASTAR
 * @uses       MarbetesLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;

class MarbetesLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new MarbetesModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new MarbetesModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdMarbete() != null && $tablaModelo->getIdMarbete() > 0)
        {
            return $this->modelo->actualizar($datosBd, $tablaModelo->getIdMarbete());
        } else
        {
            unset($datosBd["id_marbete"]);
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
     * @return MarbetesModelo
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
    public function buscarMarbetes()
    {
        $consulta = "SELECT * FROM " . $this->modelo->getEsquema() . ". marbetes";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

}
