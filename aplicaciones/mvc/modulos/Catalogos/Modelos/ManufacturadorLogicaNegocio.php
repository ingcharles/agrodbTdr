<?php
/**
 * Lógica del negocio de ManufacturadorModelo
 *
 * Este archivo se complementa con el archivo ManufacturadorControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-13
 * @uses    ManufacturadorLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */

namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class ManufacturadorLogicaNegocio implements IModelo
{

    private $modeloManufacturador = null;


    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloManufacturador = new ManufacturadorModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(array $datos)
    {
        $tablaModelo = new ManufacturadorModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdManufacturador() != null && $tablaModelo->getIdManufacturador() > 0) {
            return $this->modeloManufacturador->actualizar($datosBd, $tablaModelo->getIdManufacturador());
        } else {
            unset($datosBd["id_manufacturador"]);
            return $this->modeloManufacturador->guardar($datosBd);
        }
    }

    /**
     * Borra el registro actual
     * @param string Where|array $where
     * @return int
     */
    public function borrar($id)
    {
        $this->modeloManufacturador->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return ManufacturadorModelo
     */
    public function buscar($id)
    {
        return $this->modeloManufacturador->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloManufacturador->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloManufacturador->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarManufacturador()
    {
        $consulta = "SELECT * FROM " . $this->modeloManufacturador->getEsquema() . ". manufacturador";
        return $this->modeloManufacturador->ejecutarSqlNativo($consulta);
    }

}
