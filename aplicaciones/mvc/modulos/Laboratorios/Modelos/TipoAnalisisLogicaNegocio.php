<?php

/**
 * Lógica del negocio de  TipoAnalisisModelo
 *
 * Este archivo se complementa con el archivo   TipoAnalisisControlador.
 *
 * @author DATASTAR
 * @uses       TipoAnalisisLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;

class TipoAnalisisLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new TipoAnalisisModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new TipoAnalisisModelo($datos);
        if ($tablaModelo->getIdTipoAnalisis() != null && $tablaModelo->getIdTipoAnalisis() > 0)
        {
            return $this->modelo->actualizar($datos, $tablaModelo->getIdTipoAnalisis());
        } else
        {
            unset($datos["id_tipo_analisis"]);
            return $this->modelo->guardar($datos);
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

    public function borrarPorParametro($param, $value)
    {
        $this->modelo->borrarPorParametro($param, $value);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param  int $id
     * @return TipoAnalisisModelo
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
    public function buscarTipoAnalisis()
    {
        $consulta = "SELECT * FROM " . $this->modelo->getEsquema() . ". tipo_analisis";
        return $this->modelo->ejecutarConsulta($consulta);
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function registrar(SolicitudesModelo $modeloSolicitud, Array $datos)
    {
        $sqlInsertar = $modeloSolicitud->guardarSql('tipo_analisis');
        $sqlInsertar->columns($this->columnas());
        $sqlInsertar->values($datos, $sqlInsertar::VALUES_MERGE);
        $statement = $modeloSolicitud->getAdapter()
                ->getDriver()
                ->createStatement();
        $sqlInsertar->prepareStatement($modeloSolicitud->getAdapter(), $statement);
        $statement->execute();
    }

    /**
     * Se define las columnar a ser guardadas de la tabla tipo_analisis
     * @return string
     */
    public function columnas()
    {
        $columnasTipoAnalisis = array(
            'id_detalle_solicitud',
            'id_laboratorio',
            'numero_muestra',
            'valor_usuario',
            'codigo_agrupa',
            'total_marbetes',
            'observacion_interna',
        );
        return $columnasTipoAnalisis;
    }

    /**
     * 
     * @return string
     */
    public function columnas2()
    {
        $columnasTipoAnalisis = array(
            'id_detalle_solicitud',
            'numero_muestra',
            'codigo_usu_muestra',
            'valor_usuario',
            'codigo_agrupa',
        );
        return $columnasTipoAnalisis;
    }

    /**
     * Recupera las muestras de la solicitus de acuerdo al tipo de análisis
     */
    public function tipoAnalisisMuestras($arrayParametros = null)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['id_solicitud']))
        {
            $arrayWhere[] = " sol.id_solicitud = {$arrayParametros['id_solicitud']}";
        }
        if (!empty($arrayParametros['id_laboratorio']))
        {
            $arrayWhere[] = " ser.id_laboratorio = {$arrayParametros['id_laboratorio']}";
        }
        if ($arrayWhere)
        {
            $where = ' AND ' . implode(' AND ', $arrayWhere);
        }
        $consulta = "SELECT
        sol.id_solicitud,
        ds.id_detalle_solicitud,
        ser.id_servicio,
        ser.id_laboratorio,
        ser.codigo_analisis,
        ser.nombre,
        ser.rama_nombre,
        ta.numero_muestra,
        ta.codigo_usu_muestra,
        ta.codigo_lab_muestra,
        rm.id_recepcion_muestras,
        rm.es_aceptada,
        rm.fecha_recepcion,
        rm.observacion_recepcion,
        rm.conservacion_muestra
        FROM
        g_laboratorios.solicitudes AS sol
        INNER JOIN g_laboratorios.detalle_solicitudes AS ds ON ds.id_solicitud = sol.id_solicitud
        INNER JOIN g_laboratorios.servicios AS ser ON ser.id_servicio = ds.id_servicio
        INNER JOIN (SELECT id_detalle_solicitud, numero_muestra, codigo_usu_muestra, codigo_lab_muestra FROM g_laboratorios.tipo_analisis 
                GROUP BY id_detalle_solicitud, numero_muestra, codigo_usu_muestra, codigo_lab_muestra ORDER BY numero_muestra) AS ta ON ta.id_detalle_solicitud= ds.id_detalle_solicitud
        LEFT JOIN g_laboratorios.recepcion_muestras AS rm ON rm.id_detalle_solicitud = ds.id_detalle_solicitud AND rm.numero_muestra = ta.numero_muestra AND rm.id_reemplazo IS NULL
        WHERE ds.tipo IN ('INDIVIDUAL','PAQUETE') $where
        ORDER BY ta.codigo_usu_muestra, ta.numero_muestra";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Recupera el valor del usuario para el tipo de análisis solicitado
     * @param type $idSolicitud
     * @param type $idServicio
     * @param type $idLaboratorio
     * @param type $numeroMuestra
     * @return type
     */
    public function tipoAnalisis($idSolicitud, $idServicio, $idLaboratorio, $numeroMuestra)
    {
        $consulta = "SELECT
        g_laboratorios.detalle_solicitudes.id_solicitud,
        g_laboratorios.detalle_solicitudes.id_detalle_solicitud,
        g_laboratorios.detalle_solicitudes.id_servicio,
        g_laboratorios.tipo_analisis.id_tipo_analisis,
        g_laboratorios.tipo_analisis.id_laboratorio,
        g_laboratorios.tipo_analisis.codigo_usu_muestra,
        g_laboratorios.tipo_analisis.numero_muestra,
        g_laboratorios.tipo_analisis.valor_usuario
        FROM
        g_laboratorios.tipo_analisis
        INNER JOIN g_laboratorios.detalle_solicitudes ON g_laboratorios.detalle_solicitudes.id_detalle_solicitud = g_laboratorios.tipo_analisis.id_detalle_solicitud 
        WHERE
        g_laboratorios.detalle_solicitudes.id_solicitud = $idSolicitud AND
        g_laboratorios.detalle_solicitudes.id_servicio = $idServicio AND
        g_laboratorios.tipo_analisis.id_laboratorio = $idLaboratorio AND
        g_laboratorios.tipo_analisis.numero_muestra = $numeroMuestra";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Consulta por parametros de la tabla tipo_analisis
     * @param array $params
     * @return type
     */
    public function buscarPorParametro(array $params)
    {
        return $this->modelo->buscarPorParametro($params);
    }

    /**
     * Buscar el campo codigo_usu_muestra
     * @param type $idSolicitud
     * @return type
     */
    public function buscarCodigoUsuMuestra($idSolicitud)
    {
        $consulta = "SELECT
        sol.id_solicitud,
        tana.codigo_usu_muestra
        FROM
        g_laboratorios.solicitudes AS sol
        INNER JOIN g_laboratorios.detalle_solicitudes AS dsol ON sol.id_solicitud = dsol.id_solicitud
        INNER JOIN g_laboratorios.tipo_analisis AS tana ON dsol.id_detalle_solicitud = tana.id_detalle_solicitud
        WHERE
        sol.id_solicitud = $idSolicitud
        GROUP BY
        sol.id_solicitud,
        tana.codigo_usu_muestra,
        id_tipo_analisis
        ORDER BY id_tipo_analisis DESC
        LIMIT 1";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

}
