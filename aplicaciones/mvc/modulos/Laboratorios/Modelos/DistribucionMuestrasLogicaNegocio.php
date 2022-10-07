<?php

/**
 * Lógica del negocio de  DistribucionMuestrasModelo
 *
 * Este archivo se complementa con el archivo   DistribucionMuestrasControlador.
 *
 * @author DATASTAR
 * @uses       DistribucionMuestrasLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;

class DistribucionMuestrasLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new DistribucionMuestrasModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new DistribucionMuestrasModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdDistribucionMuestra() != null && $tablaModelo->getIdDistribucionMuestra() > 0)
        {
            return $this->modelo->actualizar($datosBd, $tablaModelo->getIdDistribucionMuestra());
        } else
        {
            unset($datosBd["id_distribucion_muestra"]);
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
     * @return DistribucionMuestrasModelo
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
     * Retorna los datos de la distribución de la muestra según parámetros
     * Ejmplo usado para obtener el tipo de laboratorio (LN, LR, LDR)
     *
     * @return array|ResultSet
     */
    public function buscarDistribucionMuestras($arrayParametros = null)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['idDireccion']))
        {
            $arrayWhere[] = " dismue.id_direccion = {$arrayParametros['idDireccion']}";
        }
        if (!empty($arrayParametros['idLaboratorio']))
        {
            $arrayWhere[] = " labpro.id_laboratorio = {$arrayParametros['idLaboratorio']}";
        }
        if (!empty($arrayParametros['idServicio']))
        {
            $arrayWhere[] = " dismue.id_servicio = {$arrayParametros['idServicio']}";
        }
        if (!empty($arrayParametros['idLocalizacionMuestra']))
        {
            $arrayWhere[] = " dismue.id_localizacion  = {$arrayParametros['idLocalizacionMuestra']}";
        }
        if (!empty($arrayParametros['id_laboratorios_provincia']))
        {
            $arrayWhere[] = " labpro.id_laboratorios_provincia  = {$arrayParametros['id_laboratorios_provincia']}";
        }
        if ($arrayWhere)
        {
            $where = "WHERE " . implode(' AND ', $arrayWhere);
        }
        $consulta = "SELECT
        dismue.id_distribucion_muestra,
        dismue.id_laboratorio,
        lab.nombre AS laboratorio,
        dismue.id_servicio,
        ser.rama_nombre,
        ser.nombre AS servicio,
        (SELECT  id_localizacion FROM g_catalogos.localizacion pl WHERE pl.id_localizacion = labpro.id_localizacion) AS id_provincia_laboratorio,
        (SELECT  nombre FROM g_catalogos.localizacion pl WHERE pl.id_localizacion = labpro.id_localizacion) AS provincia_laboratorio,
        (SELECT  id_localizacion FROM g_catalogos.localizacion pm WHERE pm.id_localizacion = dismue.id_localizacion) AS id_provincia_muestra,
        (SELECT  nombre FROM g_catalogos.localizacion pm WHERE pm.id_localizacion = dismue.id_localizacion) AS provincia_muestra,
        dismue.estado_registro,
        labpro.tipo,
        labpro.id_laboratorios_provincia
        FROM
        g_laboratorios.laboratorios_provincia AS labpro
        INNER JOIN g_laboratorios.laboratorios AS lab ON lab.id_laboratorio = labpro.id_laboratorio
        INNER JOIN g_laboratorios.distribucion_muestras AS dismue ON labpro.id_laboratorios_provincia = dismue.id_laboratorios_provincia
        INNER JOIN g_laboratorios.servicios AS ser ON ser.id_servicio = dismue.id_servicio 
        $where ORDER BY lab.nombre, ser.nombre, provincia_laboratorio, provincia_muestra";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

}
