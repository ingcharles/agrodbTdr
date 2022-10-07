<?php

/**
 * Lógica del negocio de  FinancieroDetalleModelo
 *
 * Este archivo se complementa con el archivo   FinancieroDetalleControlador.
 *
 * @author DATASTAR
 * @uses       FinancieroDetalleLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\FinancieroAutomatico\Modelos;

use Agrodb\FinancieroAutomatico\Modelos\IModelo;

class FinancieroDetalleLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new FinancieroDetalleModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new FinancieroDetalleModelo($datos);
        if ($tablaModelo->getIdFinancieroDetalle() != null && $tablaModelo->getIdFinancieroDetalle() > 0)
        {
            return $this->modelo->actualizar($datos, $tablaModelo->getIdFinancieroDetalle());
        } else
        {
            unset($datos["id_financiero_detalle"]);
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
     * @return FinancieroDetalleModelo
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
    public function buscarFinancieroDetalle()
    {
        $consulta = "SELECT * FROM " . $this->modelo->getEsquema() . ". financiero_detalle";
        return $this->modelo->ejecutarConsulta($consulta);
    }

    /**
     * Columnas para guardar junto con la solicitud
     * @return string[]
     */
    public function columnas()
    {
        $columnas = array(
            'id_financiero_cabecera',
            'id_servicio',
            'concepto_orden',
            'cantidad',
            'precio_unitario',
            'descuento',
            'iva',
            'total'
        );
        return $columnas;
    }

}
