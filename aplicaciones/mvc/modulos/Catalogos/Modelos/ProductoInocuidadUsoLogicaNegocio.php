<?php
/**
 * Lógica del negocio de ProductoInocuidadUsoModelo
 *
 * Este archivo se complementa con el archivo ProductoInocuidadUsoControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-23
 * @uses    ProductoInocuidadUsoLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class ProductoInocuidadUsoLogicaNegocio implements IModelo
{

    private $modeloProductoInocuidadUso = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloProductoInocuidadUso = new ProductoInocuidadUsoModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new ProductoInocuidadUsoModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdProductoUso() != null && $tablaModelo->getIdProductoUso() > 0) {
            return $this->modeloProductoInocuidadUso->actualizar($datosBd, $tablaModelo->getIdProductoUso());
        } else {
            unset($datosBd["id_producto_uso"]);
            return $this->modeloProductoInocuidadUso->guardar($datosBd);
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
        $this->modeloProductoInocuidadUso->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return ProductoInocuidadUsoModelo
     */
    public function buscar($id)
    {
        return $this->modeloProductoInocuidadUso->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloProductoInocuidadUso->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloProductoInocuidadUso->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarProductoInocuidadUso()
    {
        $consulta = "SELECT * FROM " . $this->modeloProductoInocuidadUso->getEsquema() . ". producto_inocuidad_uso";
        return $this->modeloProductoInocuidadUso->ejecutarSqlNativo($consulta);
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
                            g_catalogos.producto_inocuidad_uso
                        WHERE
                            id_producto = $idProducto; ";
        
        return $this->modeloProductoInocuidadUso->ejecutarSqlNativo($consulta);
    }
}