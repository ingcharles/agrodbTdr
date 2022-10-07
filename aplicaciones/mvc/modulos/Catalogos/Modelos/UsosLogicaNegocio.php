<?php
/**
 * Lógica del negocio de UsosModelo
 *
 * Este archivo se complementa con el archivo UsosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-06-23
 * @uses    UsosLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class UsosLogicaNegocio implements IModelo
{

    private $modeloUsos = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloUsos = new UsosModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new UsosModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdUso() != null && $tablaModelo->getIdUso() > 0) {
            return $this->modeloUsos->actualizar($datosBd, $tablaModelo->getIdUso());
        } else {
            unset($datosBd["id_uso"]);
            return $this->modeloUsos->guardar($datosBd);
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
        $this->modeloUsos->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return UsosModelo
     */
    public function buscar($id)
    {
        return $this->modeloUsos->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloUsos->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloUsos->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarUsos()
    {
        $consulta = "SELECT * FROM " . $this->modeloUsos->getEsquema() . ". usos";
        return $this->modeloUsos->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar usos usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarUsosXFiltro($arrayParametros)
    {
        $consulta = "  SELECT
                        	u.*
                        FROM
                        	g_catalogos.usos u
                        WHERE
                            u.estado_uso = '".$arrayParametros['estado_uso']."'
                            ".($arrayParametros['id_area'] != '' ? " and u.id_area = '".$arrayParametros['id_area']."'" : "")."
                        ORDER BY
                        	u.nombre_uso ASC;";
        
        //echo $consulta;
        return $this->modeloUsos->ejecutarSqlNativo($consulta);
    }
}