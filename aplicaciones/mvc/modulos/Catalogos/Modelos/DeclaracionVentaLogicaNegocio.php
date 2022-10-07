<?php
/**
 * Lógica del negocio de DeclaracionVentaModelo
 *
 * Este archivo se complementa con el archivo DeclaracionVentaControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-06-23
 * @uses    DeclaracionVentaLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class DeclaracionVentaLogicaNegocio implements IModelo
{

    private $modeloDeclaracionVenta = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloDeclaracionVenta = new DeclaracionVentaModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new DeclaracionVentaModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdDeclaracionVenta() != null && $tablaModelo->getIdDeclaracionVenta() > 0) {
            return $this->modeloDeclaracionVenta->actualizar($datosBd, $tablaModelo->getIdDeclaracionVenta());
        } else {
            unset($datosBd["id_declaracion_venta"]);
            return $this->modeloDeclaracionVenta->guardar($datosBd);
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
        $this->modeloDeclaracionVenta->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return DeclaracionVentaModelo
     */
    public function buscar($id)
    {
        return $this->modeloDeclaracionVenta->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloDeclaracionVenta->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloDeclaracionVenta->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarDeclaracionVenta()
    {
        $consulta = "SELECT * FROM " . $this->modeloDeclaracionVenta->getEsquema() . ". declaracion_venta";
        return $this->modeloDeclaracionVenta->ejecutarSqlNativo($consulta);
    }
}
