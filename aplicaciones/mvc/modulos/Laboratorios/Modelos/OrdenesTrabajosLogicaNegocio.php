<?php

/**
 * Lógica del negocio de  OrdenesTrabajosModelo
 *
 * Este archivo se complementa con el archivo   OrdenesTrabajosControlador.
 *
 * @author DATASTAR
 * @uses       OrdenesTrabajosLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;

class OrdenesTrabajosLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new OrdenesTrabajosModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new OrdenesTrabajosModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdOrdenTrabajo() != null && $tablaModelo->getIdOrdenTrabajo() > 0)
        {
            return $this->modelo->actualizar($datosBd, $tablaModelo->getIdOrdenTrabajo());
        } else
        {
            unset($datosBd["id_orden_trabajo"]);
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
     * @return OrdenesTrabajosModelo
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
    public function buscarOrdenesTrabajos($idSolicitud)
    {
        $consulta = "SELECT * FROM g_laboratorios.v_valor_total_orden WHERE id_solicitud = $idSolicitud";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Columnas para guardar junto con la solicitud
     * @return string[]
     */
    public function columnas()
    {
        $columnas = array(
            'identificador',
            'id_solicitud',
            'codigo',
            'tipo_orden',
            'estado',
            'id_laboratorio'
        );
        return $columnas;
    }

}
