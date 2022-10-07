<?php
/**
 * Lógica del negocio de FuncionariosModelo
 *
 * Este archivo se complementa con el archivo FuncionariosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-02-03
 * @uses    FuncionariosLogicaNegocio
 * @package Estructura
 * @subpackage Modelos
 */
namespace Agrodb\Estructura\Modelos;

use Agrodb\Estructura\Modelos\IModelo;

class FuncionariosLogicaNegocio implements IModelo
{

    private $modeloFuncionarios = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloFuncionarios = new FuncionariosModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new FuncionariosModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdArea() != null && $tablaModelo->getIdArea() > 0) {
            return $this->modeloFuncionarios->actualizar($datosBd, $tablaModelo->getIdArea());
        } else {
            unset($datosBd["id_area"]);
            return $this->modeloFuncionarios->guardar($datosBd);
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
        $this->modeloFuncionarios->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return FuncionariosModelo
     */
    public function buscar($id)
    {
        return $this->modeloFuncionarios->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloFuncionarios->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloFuncionarios->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarFuncionarios()
    {
        $consulta = "SELECT * FROM " . $this->modeloFuncionarios->getEsquema() . ". funcionarios";
        return $this->modeloFuncionarios->ejecutarSqlNativo($consulta);
    }
}
