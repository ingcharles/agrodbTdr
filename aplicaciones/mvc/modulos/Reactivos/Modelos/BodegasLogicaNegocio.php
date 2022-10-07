<?php

/**
 * Lógica del negocio de  BodegasModelo
 *
 * Este archivo se complementa con el archivo   BodegasControlador.
 *
 * @author DATASTAR
 * @uses       BodegasLogicaNegocio
 * @package Reactivos
 * @subpackage Modelo
 */

namespace Agrodb\Reactivos\Modelos;

use Agrodb\Reactivos\Modelos\IModelo;

class BodegasLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new BodegasModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new BodegasModelo($datos);
        if ($tablaModelo->getIdBodega() != null && $tablaModelo->getIdBodega() > 0)
        {
            return $this->modelo->actualizar($datos, $tablaModelo->getIdBodega());
        } else
        {
            unset($datos["id_bodega"]);
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
     * @return BodegasModelo
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
    public function buscarBodegas()
    {
        $consulta = "SELECT
        prov.nombre as provincia,
        bod.id_bodega,
        bod.nombre_bodega,
        bod.estado
        FROM " . $this->modelo->getEsquema() . ".bodegas bod
        INNER JOIN g_catalogos.localizacion prov ON prov.id_localizacion = bod.id_localizacion";
        return $this->modelo->ejecutarConsulta($consulta);
    }

}
