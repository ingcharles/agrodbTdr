<?php

/**
 * Lógica del negocio de  FinancieroCabeceraModelo
 *
 * Este archivo se complementa con el archivo   FinancieroCabeceraControlador.
 *
 * @author DATASTAR
 * @uses       FinancieroCabeceraLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\FinancieroAutomatico\Modelos;

use Agrodb\FinancieroAutomatico\Modelos\IModelo;

class FinancieroCabeceraLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new FinancieroCabeceraModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new FinancieroCabeceraModelo($datos);
        if ($tablaModelo->getIdFinancieroCabecera() != null && $tablaModelo->getIdFinancieroCabecera() > 0)
        {
            return $this->modelo->actualizar($datos, $tablaModelo->getIdFinancieroCabecera());
        } else
        {
            unset($datos["id_financiero_cabecera"]);
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
     * @return FinancieroCabeceraModelo
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
    public function buscarFinancieroCabecera()
    {
        $consulta = "SELECT * FROM " . $this->modelo->getEsquema() . ". financiero_cabecera";
        return $this->modelo->ejecutarConsulta($consulta);
    }

    /**
     * Columnas para guardar junto con la solicitud
     * @return string[]
     */
    public function columnas()
    {
        $columnas = array(
            'total_pagar',
            'tipo_solicitud',
            'estado',
            'tabla_modulo',
            'id_solicitud_tabla',
            'provincia_firmante',
            'id_provincia_firmante',
            'identificador_operador'
        );
        return $columnas;
    }

}
