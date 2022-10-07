<?php
/**
 * Lógica del negocio de TiposOperacionModelo
 *
 * Este archivo se complementa con el archivo TiposOperacionControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-09-10
 * @uses    TiposOperacionLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class TiposOperacionLogicaNegocio implements IModelo
{

    private $modeloTiposOperacion = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloTiposOperacion = new TiposOperacionModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new TiposOperacionModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdTipoOperacion() != null && $tablaModelo->getIdTipoOperacion() > 0) {
            return $this->modeloTiposOperacion->actualizar($datosBd, $tablaModelo->getIdTipoOperacion());
        } else {
            unset($datosBd["id_tipo_operacion"]);
            return $this->modeloTiposOperacion->guardar($datosBd);
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
        $this->modeloTiposOperacion->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return TiposOperacionModelo
     */
    public function buscar($id)
    {
        return $this->modeloTiposOperacion->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloTiposOperacion->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloTiposOperacion->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarTiposOperacion()
    {
        $consulta = "SELECT * FROM " . $this->modeloTiposOperacion->getEsquema() . ". tipos_operacion";
        return $this->modeloTiposOperacion->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar información de Tipos de Productos por área temática.
     *
     * @return array|ResultSet
     */
    public function buscarOperacionesXArea($area)
    {
        $consulta = "  SELECT
                        	o.*
                        FROM
                        	g_catalogos.tipos_operacion o
                        WHERE
                            o.id_area = '$area' and
                            o.estado = 1;";
        
        $operacion = $this->modeloTiposOperacion->ejecutarSqlNativo($consulta);
        
        return $operacion;
    }    
    
    public function buscarTipoOperacionPorIdOperacion($arrayParametros){
        
        $consulta = "SELECT
                        top.codigo,
                        top.id_tipo_operacion,
                        top.id_area,
                        top.nombre
                     FROM
                        g_operadores.operaciones op,
                        g_catalogos.tipos_operacion top
                     WHERE
                        op.id_tipo_operacion = top.id_tipo_operacion
                        and op.id_operacion = " . $arrayParametros['id_operacion'] . ";";
        
        return $this->modeloTiposOperacion->ejecutarSqlNativo($consulta);
    }
}
