<?php
/**
 * Lógica del negocio de IngredienteActivoInocuidadModelo
 *
 * Este archivo se complementa con el archivo IngredienteActivoInocuidadControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-06-23
 * @uses    IngredienteActivoInocuidadLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class IngredienteActivoInocuidadLogicaNegocio implements IModelo
{

    private $modeloIngredienteActivoInocuidad = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloIngredienteActivoInocuidad = new IngredienteActivoInocuidadModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new IngredienteActivoInocuidadModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdIngredienteActivo() != null && $tablaModelo->getIdIngredienteActivo() > 0) {
            return $this->modeloIngredienteActivoInocuidad->actualizar($datosBd, $tablaModelo->getIdIngredienteActivo());
        } else {
            unset($datosBd["id_ingrediente_activo"]);
            return $this->modeloIngredienteActivoInocuidad->guardar($datosBd);
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
        $this->modeloIngredienteActivoInocuidad->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return IngredienteActivoInocuidadModelo
     */
    public function buscar($id)
    {
        return $this->modeloIngredienteActivoInocuidad->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloIngredienteActivoInocuidad->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloIngredienteActivoInocuidad->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarIngredienteActivoInocuidad()
    {
        $consulta = "SELECT * FROM " . $this->modeloIngredienteActivoInocuidad->getEsquema() . ". ingrediente_activo_inocuidad";
        return $this->modeloIngredienteActivoInocuidad->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar ingredientes activos usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarIngredienteActivoXFiltro($arrayParametros)
    {        
        $consulta = "  SELECT
                        	ia.*
                        FROM
                        	g_catalogos.ingrediente_activo_inocuidad ia
                        WHERE
                            ia.estado_ingrediente_activo = '".$arrayParametros['estado_ingrediente_activo']."'
                            ".($arrayParametros['id_area'] != '' ? " and ia.id_area = '".$arrayParametros['id_area']."'" : "")."
                        ORDER BY
                        	ia.ingrediente_activo ASC;";
        
        //echo $consulta;
        return $this->modeloIngredienteActivoInocuidad->ejecutarSqlNativo($consulta);
    }
}