<?php

/**
 * Lógica del negocio de  DatosvalidadosinformeModelo
 *
 * Este archivo se complementa con el archivo   DatosvalidadosinformeControlador.
 *
 * @author DATASTAR
 * @uses       DatosvalidadosinformeLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;

class DatosvalidadosinformeLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new DatosvalidadosinformeModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $datosdb = array();
        foreach ($datos as $key => $values)
        {
            if (is_array($values))
            {
                $campos = array_filter($values, "strlen");
                foreach ($campos as $id => $valor)
                {
                    if (!empty($valor))
                    {
                        $datosdb = array("id_datos_validados_informe" => $id, $key => $valor);
                        $this->modelo->actualizar($datosdb, $id);
                    }
                }
            }
        }
    }

    /**
     * Actualiza datos validados para modificar el informe de forma directa
     * @param array $datos
     * @return type
     */
    public function actualizar(Array $datos)
    {
        $tablaModelo = new DatosvalidadosinformeModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        return $this->modelo->actualizar($datosBd, $datos['id_datos_validados_informe']);
    }

    /**
     * Borra el registro actual
     * @param string Where|array $where
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
     * @param  int $id
     * @return DatosvalidadosinformeModelo
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
    public function buscarDatosvalidadosinforme($where)
    {
        $consulta = "SELECT * FROM " . $this->modelo->getEsquema() . ". datos_validados_informe";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Retorna los registros de la vista v_campos_etiquetas
     * @param type $idOrden
     * @param type $tipo
     * @return type
     */
    public function buscarCamposEtiquetas($idOrden, $tipo)
    {
        $consulta = "SELECT * FROM g_laboratorios.v_campos_etiquetas 
        WHERE tipo LIKE '%" . $tipo . "%' AND id_orden_trabajo=" . $idOrden . " 
        ORDER BY etiqueta ASC";

        return $this->modelo->ejecutarSqlNativo($consulta);
    }

}
