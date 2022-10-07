<?php

/**
 * Lógica del negocio de  LaboratoriosProvinciaModelo
 *
 * Este archivo se complementa con el archivo   LaboratoriosProvinciaControlador.
 *
 * @author DATASTAR
 * @uses       LaboratoriosProvinciaLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;

class LaboratoriosProvinciaLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new LaboratoriosProvinciaModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new LaboratoriosProvinciaModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdLaboratoriosProvincia() != null && $tablaModelo->getIdLaboratoriosProvincia() > 0)
        {
            return $this->modelo->actualizar($datosBd, $tablaModelo->getIdLaboratoriosProvincia());
        } else
        {
            unset($datosBd["id_laboratorios_provincia"]);
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
     * @return LaboratoriosProvinciaModelo
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
     * Retorna los registros de los laboratorios en provincias
     *
     * @return array|ResultSet
     */
    public function buscarLaboratoriosProvincia($arrayParametros = null)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['idDireccion']))
        {
            $arrayWhere[] = " lp.id_direccion = {$arrayParametros['idDireccion']}";
        }
        if (!empty($arrayParametros['idLaboratorio']))
        {
            $arrayWhere[] = " lp.id_laboratorio = {$arrayParametros['idLaboratorio']}";
        }
        if ($arrayWhere)
        {
            $where = "WHERE " . implode(' AND ', $arrayWhere);
        }
        $consulta = "SELECT
        lp.id_direccion,
        lp.id_laboratorio,
        l.nombre as nombre_laboratorio,
        p.nombre as nombre_provincia,
        lp.tipo,
        lp.estado,
        lp.id_laboratorios_provincia
        FROM
        " . $this->modelo->getEsquema() . ".laboratorios_provincia AS lp
        INNER JOIN " . $this->modelo->getEsquema() . ".laboratorios AS l ON l.id_laboratorio = lp.id_laboratorio
        INNER JOIN g_catalogos.localizacion AS p ON p.id_localizacion = lp.id_localizacion
        $where ";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Retorna los registros de la distribución de la muestra
     * @param type $idLaboratorioProvincia
     * @return type
     */
    public function buscarDisMuestraLabProvincia($idLaboratorioProvincia)
    {
        $consulta = "SELECT
        labprov.id_laboratorios_provincia,
        labprov.id_localizacion,
        dm.id_distribucion_muestra,
        dm.id_localizacion,
        dm.id_servicio
        FROM
        g_laboratorios.laboratorios_provincia AS labprov
        INNER JOIN g_laboratorios.distribucion_muestras AS dm ON labprov.id_laboratorios_provincia = dm.id_laboratorios_provincia
        WHERE
        labprov.id_laboratorios_provincia = $idLaboratorioProvincia";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Retorna los registros de los laboratorios en provincias los cuales son bodega del laboratorio
     * excluyendo los laboratorios del usuario
     * Si campo laboratorios_provincia.bodega_laboratorios es null entonces es bodega del laboratorio
     * @param type $identificador
     * @param type $arrayParametros
     * @return type
     */
    public function buscarLaboratoriosProvinciaPrincipal($identificador, $arrayParametros= null)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['idDireccion']))
        {
            $arrayWhere[] = " lprov.id_direccion = {$arrayParametros['idDireccion']}";
        }
        if ($arrayWhere)
        {
            $where = " AND " . implode(' AND ', $arrayWhere);
        }
        $consulta = "SELECT
        lprov.id_laboratorios_provincia,
        loc.nombre AS nombre_provincia,
        lab.id_laboratorio,
        lab.nombre AS nombre_laboratorio
        FROM
        g_laboratorios.laboratorios_provincia AS lprov
        INNER JOIN g_catalogos.localizacion AS loc ON loc.id_localizacion = lprov.id_localizacion
        INNER JOIN g_laboratorios.laboratorios AS lab ON lab.id_laboratorio = lprov.id_laboratorio
        WHERE lprov.bodega_laboratorios ISNULL   
        AND lprov.id_laboratorios_provincia NOT IN (SELECT ulab.id_laboratorios_provincia FROM 
        g_laboratorios.usuario_laboratorio AS ulab WHERE ulab.identificador = '$identificador') $where
        ORDER BY loc.nombre, lab.nombre";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }
}
