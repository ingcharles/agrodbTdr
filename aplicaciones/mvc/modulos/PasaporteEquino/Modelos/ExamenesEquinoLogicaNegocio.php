<?php
/**
 * Lógica del negocio de ExamenesEquinoModelo
 *
 * Este archivo se complementa con el archivo ExamenesEquinoControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-02-18
 * @uses    ExamenesEquinoLogicaNegocio
 * @package PasaporteEquino
 * @subpackage Modelos
 */
namespace Agrodb\PasaporteEquino\Modelos;

use Agrodb\PasaporteEquino\Modelos\IModelo;

class ExamenesEquinoLogicaNegocio implements IModelo
{

    private $modeloExamenesEquino = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloExamenesEquino = new ExamenesEquinoModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new ExamenesEquinoModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdExamenEquino() != null && $tablaModelo->getIdExamenEquino() > 0) {
            return $this->modeloExamenesEquino->actualizar($datosBd, $tablaModelo->getIdExamenEquino());
        } else {
            unset($datosBd["id_examen_equino"]);
            return $this->modeloExamenesEquino->guardar($datosBd);
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
        $this->modeloExamenesEquino->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return ExamenesEquinoModelo
     */
    public function buscar($id)
    {
        return $this->modeloExamenesEquino->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloExamenesEquino->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloExamenesEquino->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarExamenesEquino()
    {
        $consulta = "SELECT * FROM " . $this->modeloExamenesEquino->getEsquema() . ". examenes_equino";
        return $this->modeloExamenesEquino->ejecutarSqlNativo($consulta);
    }
}
