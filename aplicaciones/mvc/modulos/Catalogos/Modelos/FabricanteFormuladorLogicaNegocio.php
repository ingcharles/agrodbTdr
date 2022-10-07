<?php
/**
 * Lógica del negocio de FabricanteFormuladorModelo
 *
 * Este archivo se complementa con el archivo FabricanteFormuladorControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-23
 * @uses    FabricanteFormuladorLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */

namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class FabricanteFormuladorLogicaNegocio implements IModelo
{

    private $modeloFabricanteFormulador = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloFabricanteFormulador = new FabricanteFormuladorModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(array $datos)
    {
        $tablaModelo = new FabricanteFormuladorModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdFabricanteFormulador() != null && $tablaModelo->getIdFabricanteFormulador() > 0) {
            return $this->modeloFabricanteFormulador->actualizar($datosBd, $tablaModelo->getIdFabricanteFormulador());
        } else {
            unset($datosBd["id_fabricante_formulador"]);
            return $this->modeloFabricanteFormulador->guardar($datosBd);
        }
    }

    /**
     * Borra el registro actual
     *
     * @param
     *            string Where|array $where
     * @return int
     */
    public function borrar($id)
    {
        $this->modeloFabricanteFormulador->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return FabricanteFormuladorModelo
     */
    public function buscar($id)
    {
        return $this->modeloFabricanteFormulador->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloFabricanteFormulador->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloFabricanteFormulador->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarFabricanteFormulador()
    {
        $consulta = "SELECT * FROM " . $this->modeloFabricanteFormulador->getEsquema() . ". fabricante_formulador";
        return $this->modeloFabricanteFormulador->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Elimina todos los registros vinculados a un producto
     *
     * @return array|ResultSet
     */
    public function borrarTodo($idProducto)
    {
        $consulta = "   DELETE FROM
                            g_catalogos.fabricante_formulador
                        WHERE
                            id_producto = $idProducto; ";

        return $this->modeloFabricanteFormulador->ejecutarSqlNativo($consulta);
    }
}