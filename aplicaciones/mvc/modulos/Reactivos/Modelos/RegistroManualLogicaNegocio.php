<?php

/**
 * Lógica del negocio de  RegistroManualModelo
 *
 * Este archivo se complementa con el archivo   RegistroManualControlador.
 *
 * @author DATASTAR
 * @uses       RegistroManualLogicaNegocio
 * @package Reactivos
 * @subpackage Modelo
 */

namespace Agrodb\Reactivos\Modelos;

use Agrodb\Reactivos\Modelos\IModelo;

class RegistroManualLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new RegistroManualModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new RegistroManualModelo($datos);
        if ($tablaModelo->getIdRegistroManual() != null && $tablaModelo->getIdRegistroManual() > 0)
        {
            return $this->modelo->actualizar($datos, $tablaModelo->getIdRegistroManual());
        } else
        {
            unset($datos["id_registro_manual"]);
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
     * @return RegistroManualModelo
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
    public function buscarRegistroManual()
    {
        $consulta = "SELECT * FROM " . $this->modelo->getEsquema() . ". registro_manual";
        return $this->modelo->ejecutarConsulta($consulta);
    }

}
