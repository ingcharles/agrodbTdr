<?php

/**
 * Lógica del negocio de  CronogramaPostregistroModelo
 *
 * Este archivo se complementa con el archivo   CronogramaPostregistroControlador.
 *
 * @author DATASTAR
 * @uses       CronogramaPostregistroLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;

class CronogramaPostregistroLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new CronogramaPostregistroModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new CronogramaPostregistroModelo($datos);
        $anio = date("Y", strtotime($datos["fecha_inicio"]));

        $datosBd = $tablaModelo->getPrepararDatos();
        $datosBd["anio"] = $anio;
        if ($tablaModelo->getIdCronogramaPostregistro() != null && $tablaModelo->getIdCronogramaPostregistro() > 0)
        {
            return $this->modelo->actualizar($datosBd, $tablaModelo->getIdCronogramaPostregistro());
        } else
        {
            unset($datosBd["id_cronograma_postregistro"]);
            return $this->modelo->guardar($datosBd);
        }
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
     * @return CronogramaPostregistroModelo
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
    public function buscarCronogramaPostregistro($anio, $idLaboratorio)
    {
        $consulta = "SELECT
        crp.id_cronograma_postregistro,
        crp.id_laboratorio,
        crp.anio,
        crp.fecha_inicio,
        crp.fecha_fin,
        crp.estado_registro,
        crp.observacion,
        crp.ingrediente_activo
        FROM
        g_laboratorios.cronograma_postregistro crp
        WHERE crp.anio='" . $anio . "' and crp.id_laboratorio=" . $idLaboratorio;
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Retorna los registros según el laboratorio
     * @param type $idLaboratorio
     * @return type
     */
    public function buscarCronogramaUsuario($idLaboratorio)
    {
        $consulta = "SELECT
        crp.id_cronograma_postregistro,
        crp.id_laboratorio,
        crp.anio,
        crp.fecha_inicio,
        crp.fecha_fin,
        crp.estado_registro,
        crp.observacion,
        crp.ingrediente_activo
        FROM
        g_laboratorios.cronograma_postregistro crp
        WHERE NOW() BETWEEN fecha_inicio AND fecha_fin and crp.id_laboratorio=" . $idLaboratorio;
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

}
