<?php
/**
 * Lógica del negocio de GuiasBuenasPracticasModelo
 *
 * Este archivo se complementa con el archivo GuiasBuenasPracticasControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-03-23
 * @uses    GuiasBuenasPracticasLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class GuiasBuenasPracticasLogicaNegocio implements IModelo
{

    private $modeloGuiasBuenasPracticas = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloGuiasBuenasPracticas = new GuiasBuenasPracticasModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new GuiasBuenasPracticasModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdGuiaBuenasPracticas() != null && $tablaModelo->getIdGuiaBuenasPracticas() > 0) {
            return $this->modeloGuiasBuenasPracticas->actualizar($datosBd, $tablaModelo->getIdGuiaBuenasPracticas());
        } else {
            unset($datosBd["id_guia_buenas_practicas"]);
            return $this->modeloGuiasBuenasPracticas->guardar($datosBd);
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
        $this->modeloGuiasBuenasPracticas->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return GuiasBuenasPracticasModelo
     */
    public function buscar($id)
    {
        return $this->modeloGuiasBuenasPracticas->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloGuiasBuenasPracticas->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloGuiasBuenasPracticas->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarGuiasBuenasPracticas()
    {
        $consulta = "SELECT * FROM " . $this->modeloGuiasBuenasPracticas->getEsquema() . ". guias_buenas_practicas";
        return $this->modeloGuiasBuenasPracticas->ejecutarSqlNativo($consulta);
    }
}
