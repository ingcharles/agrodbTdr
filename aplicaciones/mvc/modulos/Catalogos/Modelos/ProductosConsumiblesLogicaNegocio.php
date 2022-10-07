<?php
/**
 * Lógica del negocio de ProductosConsumiblesModelo
 *
 * Este archivo se complementa con el archivo ProductosConsumiblesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-06-23
 * @uses    ProductosConsumiblesLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class ProductosConsumiblesLogicaNegocio implements IModelo
{

    private $modeloProductosConsumibles = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloProductosConsumibles = new ProductosConsumiblesModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new ProductosConsumiblesModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdProductoConsumible() != null && $tablaModelo->getIdProductoConsumible() > 0) {
            return $this->modeloProductosConsumibles->actualizar($datosBd, $tablaModelo->getIdProductoConsumible());
        } else {
            unset($datosBd["id_producto_consumible"]);
            return $this->modeloProductosConsumibles->guardar($datosBd);
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
        $this->modeloProductosConsumibles->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return ProductosConsumiblesModelo
     */
    public function buscar($id)
    {
        return $this->modeloProductosConsumibles->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloProductosConsumibles->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloProductosConsumibles->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarProductosConsumibles()
    {
        $consulta = "SELECT * FROM " . $this->modeloProductosConsumibles->getEsquema() . ". productos_consumibles";
        return $this->modeloProductosConsumibles->ejecutarSqlNativo($consulta);
    }
}
