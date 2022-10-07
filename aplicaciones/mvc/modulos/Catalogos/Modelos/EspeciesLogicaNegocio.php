<?php
/**
 * Lógica del negocio de EspeciesModelo
 *
 * Este archivo se complementa con el archivo EspeciesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-07
 * @uses    EspeciesLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class EspeciesLogicaNegocio implements IModelo
{

    private $modeloEspecies = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloEspecies = new EspeciesModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new EspeciesModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdEspecies() != null && $tablaModelo->getIdEspecies() > 0) {
            return $this->modeloEspecies->actualizar($datosBd, $tablaModelo->getIdEspecies());
        } else {
            unset($datosBd["id_especies"]);
            return $this->modeloEspecies->guardar($datosBd);
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
        $this->modeloEspecies->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return EspeciesModelo
     */
    public function buscar($id)
    {
        return $this->modeloEspecies->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloEspecies->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloEspecies->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarEspecies()
    {
        $consulta = "SELECT * FROM " . $this->modeloEspecies->getEsquema() . ". especies";
        return $this->modeloEspecies->ejecutarSqlNativo($consulta);
    }
}
