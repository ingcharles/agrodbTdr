<?php

/**
 * Lógica del negocio de  DetalleFormaPagoModelo
 *
 * Este archivo se complementa con el archivo   DetalleFormaPagoControlador.
 *
 * @author DATASTAR
 * @uses       DetalleFormaPagoLogicaNegocio
 * @package Financiero
 * @subpackage Modelo
 */

namespace Agrodb\Financiero\Modelos;

use Agrodb\Financiero\Modelos\IModelo;

class DetalleFormaPagoLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new DetalleFormaPagoModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new DetalleFormaPagoModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdDetallePago() != null && $tablaModelo->getIdDetallePago() > 0)
        {
            return $this->modelo->actualizar($datosBd, $tablaModelo->getIdDetallePago());
        } else
        {
            unset($datosBd["id_detalle_pago"]);
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
     * @return DetalleFormaPagoModelo
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
    public function buscarDetalleFormaPago()
    {
        $consulta = "SELECT * FROM " . $this->modelo->getEsquema() . ". detalle_forma_pago";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

}
