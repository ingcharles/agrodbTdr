<?php

/**
 * Lógica del negocio de  ParametrosServiciosModelo
 *
 * Este archivo se complementa con el archivo   ParametrosServiciosControlador.
 *
 * @author DATASTAR
 * @uses       ParametrosServiciosLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;

class ParametrosServiciosLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new ParametrosServiciosModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new ParametrosServiciosModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();

        //Validar que no exista otro párametro con el mismo código del mismo laboratorio y servicio
        $where = array("codigo" => $tablaModelo->getCodigo(), "id_laboratorio" => $tablaModelo->getIdLaboratorio(), "id_servicio" => $tablaModelo->getIdServicio());
        $resultado = $this->buscarLista($where);
        $resultado->current();
        
        
        if ($tablaModelo->getIdParametrosServicio() != null && $tablaModelo->getIdParametrosServicio() > 0)
        {
            if ($resultado->count() > 1)
        {
            \Agrodb\Core\Mensajes::fallo('Error: Ya existe un párametro en este servicio con el mismo código');
            throw new \Exception('Error: Ya existe un párametro con este código para este laboratorio Clase: ParametrosLaboratoriosLogicaNegocio Método: guardar ');
        }
            unset($datosBd['id_servicio']);
            return $this->modelo->actualizar($datosBd, $tablaModelo->getIdParametrosServicio());
        } else
        {
            if ($resultado->count() > 0)
        {
            \Agrodb\Core\Mensajes::fallo('Error: Ya existe un párametro en este servicio con el mismo código');
            throw new \Exception('Error: Ya existe un párametro con este código para este laboratorio Clase: ParametrosLaboratoriosLogicaNegocio Método: guardar ');
        }
            unset($datosBd["id_parametros_servicio"]);
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
     * @return ParametrosServiciosModelo
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
    public function buscarParametrosServicios()
    {
        $consulta = "SELECT * FROM " . $this->modelo->getEsquema() . ". parametros_servicios";
        return $this->modelo->ejecutarConsulta($consulta);
    }

    /**
     * Busca los registros hijos
     * @param type $idPadre
     * @return type
     */
    public function buscarIdPadre($idPadre = null)
    {
        if ($idPadre == null)
        {
            $where = "fk_id_parametros_servicio IS NULL order  by orden";
        } else
        {
            $where = "fk_id_parametros_servicio=" . $idPadre . " order  by orden";
        }
        return $this->modelo->buscarLista($where);
    }

    /**
     * Busca varios tipos de análisis de acuerdo a los id separados por una coma.
     * Ej $idServicios= 5,6,8,56
     *
     * @param array $filtro
     * @return array|ResultSet
     */
    public function buscarServicioParametros(Array $filtro, $idDetalleSolicitud)
    {
        if ($idDetalleSolicitud == null)
        {
            $param = " is null";
        } else
        {
            $param = " = $idDetalleSolicitud";
        }
        $idServicios = implode(",", $filtro);
        $consulta = "SELECT
        ser.id_servicio,
        ser.rama_nombre
        FROM
        g_laboratorios.parametros_servicios AS pser
        INNER JOIN g_laboratorios.laboratorios AS lab ON lab.id_laboratorio = pser.id_laboratorio
        LEFT JOIN g_laboratorios.archivos_adjuntos AS adj ON adj.id_parametros_servicio = pser.id_parametros_servicio AND id_detalle_solicitud $param
        INNER JOIN g_laboratorios.servicios AS ser ON ser.id_servicio = pser.id_servicio
        WHERE
        pser.estado = 'ACTIVO' AND
        pser.id_servicio IN ($idServicios)
        GROUP BY
        ser.id_servicio,
        ser.rama_nombre
        ORDER BY
        ser.orden ASC";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Busca varios tipos de análisis de acuerdo a los id separados por una coma.
     * Ej $idServicios= 5,6,8,56
     *
     * @param array $filtro
     * @return array|ResultSet
     */
    public function buscarParametrosPorServicio($idServicio, $idDetalleSolicitud)
    {
        if ($idDetalleSolicitud == null)
        {
            $param = " is null";
        } else
        {
            $param = " = $idDetalleSolicitud";
        }
        $consulta = "SELECT
        lab.codigo AS codigo_laboratorio,
        lab.nombre AS nombre_laboratorio,
        pser.id_parametros_servicio,
        pser.id_servicio,
        pser.id_direccion,
        pser.id_laboratorio,
        pser.codigo AS codigo_parametro,
        pser.tipo_campo,
        pser.nombre AS nombre_parametro,
        pser.descripcion,
        pser.obligatorio,
        adj.id_archivos_adjuntos,
        adj.id_detalle_solicitud,
        adj.nombre_archivo
        FROM
        g_laboratorios.parametros_servicios AS pser
        INNER JOIN g_laboratorios.laboratorios AS lab ON lab.id_laboratorio = pser.id_laboratorio
        LEFT JOIN g_laboratorios.archivos_adjuntos AS adj ON adj.id_parametros_servicio = pser.id_parametros_servicio AND id_detalle_solicitud $param
        WHERE
        pser.estado = 'ACTIVO' AND
        pser.id_servicio = $idServicio
        ORDER BY
        pser.orden ASC";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Retorna la lista de parametros del servicio
     * @param type $arrayParametros
     * @return type
     */
    public function buscarParametrosS($arrayParametros)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['id_direccion']))
        {
            $arrayWhere[] = " pser.id_direccion = '{$arrayParametros['id_direccion']}'";
        }
        if (!empty($arrayParametros['id_laboratorio']))
        {
            $arrayWhere[] = " pser.id_laboratorio = '{$arrayParametros['id_laboratorio']}'";
        }
        if (!empty($arrayParametros['id_servicio']))
        {
            $arrayWhere[] = " pser.id_servicio = '{$arrayParametros['id_servicio']}'";
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
        pser.id_parametros_servicio,
        pser.nombre,
        pser.valor_aux1,
        pser.valor_aux2,
        pser.estado,
        pser.obligatorio,
        pser.orden,
        dir.nombre AS direccion,
        lab.nombre AS laboratorio,
        ser.nombre AS servicio
        FROM
        g_laboratorios.parametros_servicios AS pser
        INNER JOIN g_laboratorios.laboratorios AS dir ON dir.id_laboratorio = pser.id_direccion
        INNER JOIN g_laboratorios.laboratorios AS lab ON lab.id_laboratorio = pser.id_laboratorio
        INNER JOIN g_laboratorios.servicios AS ser ON ser.id_servicio = pser.id_servicio
        $where
        ORDER BY ser.orden, valor_aux1, orden";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }
/**
 * Busca un parámetro de servicio
 * @param type $codigo
 * @param type $idServicio
 * @return type
 */
    public function buscarParametro($codigo, $idServicio)
    {
        $where = "codigo ='" . $codigo . "' AND id_servicio=" . $idServicio;

        return $this->modelo->buscarLista($where);
    }

}
