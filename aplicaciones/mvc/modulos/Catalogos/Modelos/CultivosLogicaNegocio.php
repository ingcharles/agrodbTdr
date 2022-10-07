<?php
/**
 * Lógica del negocio de CultivosModelo
 *
 * Este archivo se complementa con el archivo CultivosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-13
 * @uses    CultivosLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */

namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class CultivosLogicaNegocio implements IModelo
{

    private $modeloCultivos = null;


    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloCultivos = new CultivosModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(array $datos)
    {
        $tablaModelo = new CultivosModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdCultivo() != null && $tablaModelo->getIdCultivo() > 0) {
            return $this->modeloCultivos->actualizar($datosBd, $tablaModelo->getIdCultivo());
        } else {
            unset($datosBd["id_cultivo"]);
            return $this->modeloCultivos->guardar($datosBd);
        }
    }

    /**
     * Borra el registro actual
     * @param string Where|array $where
     * @return int
     */
    public function borrar($id)
    {
        $this->modeloCultivos->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return CultivosModelo
     */
    public function buscar($id)
    {
        return $this->modeloCultivos->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloCultivos->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloCultivos->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarCultivos()
    {
        $consulta = "SELECT * FROM " . $this->modeloCultivos->getEsquema() . ". cultivos";
        return $this->modeloCultivos->ejecutarSqlNativo($consulta);
    }

    /**
     * Busca el catálogo de cultivos
     *
     * @return ResultSet Cultivo id_area
     */
    public function buscarCultivosCatalogo($idArea){
        $where = "id_area = '$idArea'";
        return $this->modeloCultivos->buscarLista($where, 'nombre_cientifico_cultivo');
    }

}
