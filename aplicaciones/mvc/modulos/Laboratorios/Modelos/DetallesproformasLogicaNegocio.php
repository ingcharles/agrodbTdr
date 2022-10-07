<?php

/**
 * Lógica del negocio de  DetallesproformasModelo
 *
 * Este archivo se complementa con el archivo   DetallesproformasControlador.
 *
 * @author DATASTAR
 * @uses       DetallesproformasLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;

class DetallesproformasLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new DetallesproformasModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new DetallesproformasModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdDetalleProforma() != null && $tablaModelo->getIdDetalleProforma() > 0)
        {
            return $this->modelo->actualizar($datosBd, $tablaModelo->getIdDetalleProforma());
        } else
        {
            unset($datosBd["id_detalle_proforma"]);
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
    
    public function borrarPorParametro($param, $value) {
        $this->modelo->borrarPorParametro($param, $value);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param  int $id
     * @return DetallesproformasModelo
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
    public function buscarDetallesproformas()
    {
        $consulta = "SELECT * FROM " . $this->modelo->getEsquema() . ". detalles_proformas";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Buscar el valor de GUIA
     * @param type $idServicio
     */
    public function buscarValor($idServicio)
    {
        $consulta = "SELECT valor,
        (SELECT rama_nombre FROM g_laboratorios.servicios WHERE id_servicio=$idServicio)
        FROM g_financiero.servicios 
        WHERE id_servicio = (SELECT id_servicio_guia FROM g_laboratorios.servicios WHERE id_servicio= ( SELECT (((string_to_array((g_laboratorios.f_path_servicio($idServicio))::text, '/'::text))::character varying[])[2])::integer));";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

}
