<?php
/**
 * Lógica del negocio de  EstadoAnalisisModelo
 *
 * Este archivo se complementa con el archivo   EstadoAnalisisControlador.
 *
 * @author DATASTAR
 * @uses       EstadoAnalisisLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */
namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;

class EstadoAnalisisLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new EstadoAnalisisModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new EstadoAnalisisModelo($datos);
        if ($tablaModelo->getIdEstadoAnalisis() != null && $tablaModelo->getIdEstadoAnalisis() > 0) {
            return $this->modelo->actualizar($datos, $tablaModelo->getIdEstadoAnalisis());
        } else {
            unset($datos["id_estado_analisis"]);
            return $this->modelo->guardar($datos);
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
        $this->modelo->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return EstadoAnalisisModelo
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
    public function buscarEstadoAnalisis()
    {
        $consulta = "SELECT * FROM " . $this->modelo->getEsquema() . ".estado_analisis";
        return $this->modelo->ejecutarConsulta($consulta);
    }
}
