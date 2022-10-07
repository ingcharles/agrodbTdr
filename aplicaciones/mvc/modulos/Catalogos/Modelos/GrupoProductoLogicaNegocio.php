<?php
/**
 * Lógica del negocio de GrupoProductoModelo
 *
 * Este archivo se complementa con el archivo GrupoProductoControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-06-23
 * @uses    GrupoProductoLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class GrupoProductoLogicaNegocio implements IModelo
{

    private $modeloGrupoProducto = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloGrupoProducto = new GrupoProductoModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new GrupoProductoModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdGrupoProducto() != null && $tablaModelo->getIdGrupoProducto() > 0) {
            return $this->modeloGrupoProducto->actualizar($datosBd, $tablaModelo->getIdGrupoProducto());
        } else {
            unset($datosBd["id_grupo_producto"]);
            return $this->modeloGrupoProducto->guardar($datosBd);
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
        $this->modeloGrupoProducto->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return GrupoProductoModelo
     */
    public function buscar($id)
    {
        return $this->modeloGrupoProducto->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloGrupoProducto->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloGrupoProducto->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarGrupoProducto()
    {
        $consulta = "SELECT * FROM " . $this->modeloGrupoProducto->getEsquema() . ". grupo_producto";
        return $this->modeloGrupoProducto->ejecutarSqlNativo($consulta);
    }
}
