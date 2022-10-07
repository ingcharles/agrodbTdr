<?php
/**
 * Lógica del negocio de OrganizacionEcuestreModelo
 *
 * Este archivo se complementa con el archivo OrganizacionEcuestreControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-02-15
 * @uses    OrganizacionEcuestreLogicaNegocio
 * @package PasaporteEquino
 * @subpackage Modelos
 */
namespace Agrodb\PasaporteEquino\Modelos;

use Agrodb\PasaporteEquino\Modelos\IModelo;

class OrganizacionEcuestreLogicaNegocio implements IModelo
{

    private $modeloOrganizacionEcuestre = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloOrganizacionEcuestre = new OrganizacionEcuestreModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new OrganizacionEcuestreModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdOrganizacionEcuestre() != null && $tablaModelo->getIdOrganizacionEcuestre() > 0) {
            return $this->modeloOrganizacionEcuestre->actualizar($datosBd, $tablaModelo->getIdOrganizacionEcuestre());
        } else {
            unset($datosBd["id_organizacion_ecuestre"]);
            return $this->modeloOrganizacionEcuestre->guardar($datosBd);
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
        $this->modeloOrganizacionEcuestre->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return OrganizacionEcuestreModelo
     */
    public function buscar($id)
    {
        return $this->modeloOrganizacionEcuestre->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloOrganizacionEcuestre->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloOrganizacionEcuestre->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarOrganizacionEcuestre()
    {
        $consulta = "SELECT * FROM " . $this->modeloOrganizacionEcuestre->getEsquema() . ". organizacion_ecuestre";
        return $this->modeloOrganizacionEcuestre->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Busca las provincias en donde hay registradas organizaciones ecuestres
     *
     * @return array|ResultSet
     */
    public function buscarProvinciasOrganizacion()
    {
        $consulta = "   SELECT
                        	distinct(oe.provincia) as provincia
                        FROM
                        	g_pasaporte_equino.organizacion_ecuestre oe
                        WHERE
                        	oe.estado_organizacion in ('Activo');";
        //print_r($consulta);
        return $this->modeloOrganizacionEcuestre->ejecutarSqlNativo($consulta);
    }
}