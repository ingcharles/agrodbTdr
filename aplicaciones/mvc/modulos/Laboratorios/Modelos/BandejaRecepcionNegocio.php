<?php

/**
 * Lógica del negocio de  SolicitudesModelo
 *
 * Este archivo se complementa con el archivo   SolicitudesControlador.
 * https://docs.zendframework.com/zend-db/sql/
 * 
 * @author DATASTAR
 * @uses       SolicitudesLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;
use Agrodb\Core\Constantes;

class BandejaRecepcionNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new SolicitudesModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        
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
        $this->modelo->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return SolicitudesModelo
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
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     * Retorna la lista de solicitudes con excepción de las solicitudes con estado REGISTRADA
     *
     * @return array|ResultSet
     */
    public function buscarListaParametros($arrayParametros = null, $order = null, $count = null, $offset = null)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['estado']))
        {
            $arrayWhere[] = " estado = '{$arrayParametros['estado']}'";
        }
        if (!empty($arrayParametros['codigo']))
        {
            $arrayWhere[] = "UPPER(codigo) LIKE '%" . strtoupper($arrayParametros['codigo']) . "%'";
        }
        if (!empty($arrayParametros['cliente']))
        {
            $arrayWhere[] = " UPPER(cliente) LIKE '%" . strtoupper($arrayParametros['cliente']) . "%'";
        }
        if ($arrayWhere)
        {
            $where = implode(' AND ', $arrayWhere);
        }
        if (!empty($where))
        {
            $where = " WHERE " . $where;
        }
        $consulta = "SELECT * from " . $this->modelo->getEsquema() . ".v_valor_total_solicitud "
                . "  $where ORDER BY id_solicitud";

        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     * Retorna la lista de solicitudes con excepción de las solicitudes con estado REGISTRADA
     *
     * @return array|ResultSet
     */
    public function buscarSolicitudes($arrayParametros = null, $order = null, $count = null, $offset = null)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['identificador']))
        {
            $arrayWhere[] = " identificador = '{$arrayParametros['identificador']}'";
        }
        if (!empty($arrayParametros['id_laboratorio']))
        {
            $arrayWhere[] = " dismu.id_laboratorio = '{$arrayParametros['id_laboratorio']}'";
        }
        if (!empty($arrayParametros['estado']))
        {
            if (is_array($arrayParametros['estado']))
            {
                $arrayWhere[] = " sol.estado IN ('" . implode("','", $arrayParametros['estado']) . "')";
            } else
            {
                $arrayWhere[] = " sol.estado = '{$arrayParametros['estado']}'";
            }
        }
        if (!empty($arrayParametros['codigo']))
        {
            $arrayWhere[] = "UPPER(sol.codigo) LIKE '%" . strtoupper($arrayParametros['codigo']) . "%'";
        }
        if (!empty($arrayParametros['cliente']))
        {
            $arrayWhere[] = " UPPER(cliente) LIKE '%" . strtoupper($arrayParametros['cliente']) . "%'";
        }
        if (!empty($arrayParametros['perfil']))
        {
            $arrayWhere[] = " ulab.perfil = '{$arrayParametros['perfil']}'";
        }
        if ($arrayWhere)
        {
            $where = implode(' AND ', $arrayWhere);
        }
        if (!empty($where))
        {
            $where = " WHERE " . $where;
        }
        $consulta = "SELECT
sol.id_solicitud,
sol.codigo,
sol.exoneracion,
tsol.cliente,
sol.estado,
sol.tipo_solicitud,
tsol.total_solicitud,
tsol.fecha_envio,
sol.id_distribucion_muestra,
dismu.id_localizacion AS id_prov_muestra,
lmuestra.nombre AS prov_muestra,
lprolab.nombre AS prov_laboratorio,
labprov.id_localizacion AS id_prov_laboratorio,
ulab.id_usuario_laboratorio,
ulab.identificador,
(SELECT porcentaje_iva from g_laboratorios.pagos WHERE id_solicitud = sol.id_solicitud LIMIT 1) AS iva_tomado
FROM
g_laboratorios.solicitudes AS sol
INNER JOIN g_laboratorios.distribucion_muestras AS dismu ON sol.id_distribucion_muestra = dismu.id_distribucion_muestra
INNER JOIN g_catalogos.localizacion AS lmuestra ON lmuestra.id_localizacion = dismu.id_localizacion
INNER JOIN g_laboratorios.laboratorios_provincia AS labprov ON labprov.id_laboratorios_provincia = dismu.id_laboratorios_provincia
INNER JOIN g_catalogos.localizacion AS lprolab ON lprolab.id_localizacion = labprov.id_localizacion
INNER JOIN g_laboratorios.v_valor_total_solicitud AS tsol ON tsol.id_solicitud = sol.id_solicitud
INNER JOIN g_laboratorios.usuario_laboratorio AS ulab ON ulab.id_laboratorios_provincia = labprov.id_laboratorios_provincia
$where ORDER BY sol.id_solicitud DESC";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

}
