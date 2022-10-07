<?php
/**
 * Lógica del negocio de FormulacionModelo
 *
 * Este archivo se complementa con el archivo FormulacionControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-07-23
 * @uses    FormulacionLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class FormulacionLogicaNegocio implements IModelo
{

    private $modeloFormulacion = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloFormulacion = new FormulacionModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new FormulacionModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdFormulacion() != null && $tablaModelo->getIdFormulacion() > 0) {
            return $this->modeloFormulacion->actualizar($datosBd, $tablaModelo->getIdFormulacion());
        } else {
            unset($datosBd["id_formulacion"]);
            return $this->modeloFormulacion->guardar($datosBd);
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
        $this->modeloFormulacion->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return FormulacionModelo
     */
    public function buscar($id)
    {
        return $this->modeloFormulacion->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloFormulacion->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloFormulacion->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarFormulacion()
    {
        $consulta = "SELECT * FROM " . $this->modeloFormulacion->getEsquema() . ". formulacion";
        return $this->modeloFormulacion->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Buscar usos usando filtros.
     *
     * @return array|ResultSet
     */
    public function buscarFormulacionesXFiltro($arrayParametros)
    {
        $consulta = "  SELECT
                        	f.*
                        FROM
                        	g_catalogos.formulacion f
                        WHERE
                            f.estado_formulacion = '".$arrayParametros['estado_formulacion']."'
                            ".($arrayParametros['id_area'] != '' ? " and f.id_area = '".$arrayParametros['id_area']."'" : "")."
                        ORDER BY
                        	f.formulacion ASC;";
        
        //echo $consulta;
        return $this->modeloFormulacion->ejecutarSqlNativo($consulta);
    }
}