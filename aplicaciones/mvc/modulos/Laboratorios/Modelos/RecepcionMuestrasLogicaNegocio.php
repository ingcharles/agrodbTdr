<?php

/**
 * Lógica del negocio de  RecepcionMuestrasModelo
 *
 * Este archivo se complementa con el archivo   RecepcionMuestrasControlador.
 *
 * @author DATASTAR
 * @uses       RecepcionMuestrasLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;
use Agrodb\Core\Constantes;

class RecepcionMuestrasLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new RecepcionMuestrasModelo();
    }

    /**
     * Guarda en la tabla recepcion_muestra y ordenes_trabajo
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $this->modelo = new RecepcionMuestrasModelo();
        $proceso = $this->modelo->getAdapter()
                ->getDriver()
                ->getConnection();
        if (!$proceso->beginTransaction())
        {
            throw new \Exception('No se pudo iniciar la transacción en: Guardar recepcion_muestra');
        }
        //si NO existe el id_orden_trabajo entonces crear el registro
        if (empty($datos['idOrdenTrabajo']))
        {
            //primero guarda en tabla ordenes_trabajos
            $lNOrdentesTrabajos = new OrdenesTrabajosLogicaNegocio();
            $modeloOrdenesT = new OrdenesTrabajosModelo();
            $datosOrdenT = array(
                'identificador' => $datos['identificador'],
                'id_solicitud' => $datos['idSolicitud'],
                'codigo' => ' ', //El código se actualiza con un trigger
                'tipo_orden' => 'PRIMARIA',
                'estado' => $datos['estado'],
                'id_laboratorio' => $datos['idLaboratorio']
            );
            $statement = $this->modelo->getAdapter()
                    ->getDriver()
                    ->createStatement();
            /* ver: https://docs.zendframework.com/zend-db/sql/ */
            $sqlInsertar = $this->modelo->guardarSql('ordenes_trabajos', $this->modelo->getEsquema());
            $sqlInsertar->columns($lNOrdentesTrabajos->columnas());
            $sqlInsertar->values($datosOrdenT, $sqlInsertar::VALUES_MERGE);
            $sqlInsertar->prepareStatement($this->modelo->getAdapter(), $statement);
            $statement->execute();
            $idOrdenTrabajo = $this->modelo->adapter->driver->getLastGeneratedValue($this->modelo->getEsquema() . '.ordenes_trabajos_id_orden_trabajo_seq');
        } else
        {
            $idOrdenTrabajo = $datos['idOrdenTrabajo'];
        }

        $statement = $this->modelo->getAdapter()
                ->getDriver()
                ->createStatement();
        $muestras = $datos['numMuestra']; //id_servicio-num_muestra
        foreach ($muestras as $key => $valor)
        {
            $datosRecepcion = array(
                'identificador' => $datos['identificador'],
                'tipo_recepcion' => 'INGRESO',
                'observacion_recepcion' => $datos['observacionRecepcion'][$key],
                'es_aceptada' => $datos['esAceptada'][$key],
                'conservacion_muestra' => $datos['conservacionMuestra'][$key],
                'id_orden_trabajo' => $idOrdenTrabajo,
                'id_servicio' => $datos['idServicio'][$key],
                'numero_muestra' => $valor,
                'id_laboratorio' => $datos['idLaboratorio'],
                'id_detalle_solicitud' => $datos['idDetalleSolicitud'][$key],
                'codigo_usu_muestra' => $datos['codigoUsuMuestra'][$key],
            );
            if (empty($datos['idRecepcionMuestras'][$key]))
            {
                $sqlInsertar = $this->modelo->guardarSql('recepcion_muestras', $this->modelo->getEsquema());
                $sqlInsertar->columns($this->columnas());
                $sqlInsertar->values($datosRecepcion, $sqlInsertar::VALUES_MERGE);
                $sqlInsertar->prepareStatement($this->modelo->getAdapter(), $statement);
                $statement->execute();
            } else
            {
                $sqlActualizar = $this->modelo->actualizarSql('recepcion_muestras', $this->modelo->getEsquema());
                $sqlActualizar->set($datosRecepcion);
                $sqlActualizar->where(array('id_recepcion_muestras' => $datos['idRecepcionMuestras'][$key]));
                $sqlActualizar->prepareStatement($this->modelo->getAdapter(), $statement);
                $statement->execute();
            }
        }
        $proceso->commit();
        return $idOrdenTrabajo;
    }

    /**
     * Guarda los datos en la tabla g_laboratorios.recepcion_muestras
     * @param type $datos
     */
    public function guardarDatosRM($datos)
    {
        $tablaModelo = new RecepcionMuestrasModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        unset($datosBd['id_orden_trabajo']);
        $this->modelo = new RecepcionMuestrasModelo();
        $this->modelo->actualizar($datosBd, $datos['id_recepcion_muestras']);
    }

    /**
     * Guarda en la tabla recepcion_muestra y ordenes_trabajo
     *
     * @param array $datos
     * @return int
     */
    public function guardarReingreso(Array $datos)
    {
        if (isset($datos['idRecepcionMuestras']))
        {
            $this->modelo = new RecepcionMuestrasModelo();
            $proceso = $this->modelo->getAdapter()
                    ->getDriver()
                    ->getConnection();
            if (!$proceso->beginTransaction())
            {
                throw new \Exception('No se pudo iniciar la transacción en: Guardar recepcion_muestra');
            }
            $muestras = $datos['idRecepcionMuestras'];
            foreach ($muestras as $key => $valor)
            {
                $datosRecepcion = array(
                    'conservacion_muestra' => $datos['conservacionMuestra'][$key],
                    'fecha_verificada' => date('Y-m-d'),
                    'observacion_recepcion' => $datos['observacionRecepcion'][$key],
                    'fecha_toma' => $datos['fechaToma'][$key],
                    'responsable_toma' => $datos['responsableToma'][$key],
                    'estado_actual' => 'RECIBIDA');
                $this->modelo = new RecepcionMuestrasModelo();
                $this->modelo->actualizar($datosRecepcion, $datos['idRecepcionMuestras'][$key]);
            }
            $proceso->commit();
        }
        return true;
    }

    /**
     * Actualizar en la tabla recepcion_muestra
     * Actualiza datos ingresados por Responsable Técnico
     *
     * @param array $datos
     * @return int
     */
    public function guardarRT(Array $datos)
    {
        //Actualzamos primero la orden de trabajo si existe temperatura
        if (!empty($datos['temperatura']))
        {
            $ot = new \Agrodb\Laboratorios\Modelos\OrdenesTrabajosLogicaNegocio();
            $otDatos = array('id_orden_trabajo' => $datos['idOrdenTrabajo'], 'fk_identificador' => $datos['identificador'], 'temperatura' => $datos['temperatura']);
            $ot->guardar($otDatos);
        }
        if (isset($datos['idRecepcionMuestras']))
        {
            $this->modelo = new RecepcionMuestrasModelo();
            $proceso = $this->modelo->getAdapter()
                    ->getDriver()
                    ->getConnection();
            if (!$proceso->beginTransaction())
            {
                throw new \Exception('No se pudo iniciar la transacción en: Guardar recepcion_muestra');
            }

            $muestras = $datos['idRecepcionMuestras'];
            $existeNoIdoneas = FALSE;
            foreach ($muestras as $key => $valor)
            {
                $datosRecepcion = array(
                    'fk_identificador' => $datos['identificador'],
                    'fecha_verificada' => date('Y-m-d'),
                    'es_idonea' => $datos['esIdonea'][$key],
                    'observacion_verificacion' => $datos['observacionVerificacion'][$key]
                );
                if ($datos['esIdonea'][$key] == 'NO')
                {
                    $datosRecepcion['url_archivo_adjunto'] = $datos['archivo'];
                    $datosRecepcion['no_idonea_analisis'] = 'SI';
                    $datosRecepcion['fecha_no_idonea_analisis'] = date('Y-m-d');
                    $existeNoIdoneas = TRUE;
                }
                $this->modelo = new RecepcionMuestrasModelo();
                $this->modelo->actualizar($datosRecepcion, $datos['idRecepcionMuestras'][$key]);
                $this->registrarPaquete($datos['idRecepcionMuestras'][$key]);
            }
            if ($existeNoIdoneas)
            {
                $this->enviarNoticiacionNoIdonea($datos);
            }
            $proceso->commit();
        }
        return true;
    }

    public function registrarPaquete($idRecepcionMuestras)
    {
        $query = "SELECT g_laboratorios.f_registrar_elementos_paquete($idRecepcionMuestras);";
        $this->modelo->ejecutarSqlNativo($query);
    }

    /**
     * Actualizar en la tabla recepcion_muestra
     * La orden de trabajo será finalizada al guardar todas las fechas de impresión de marbetes.
     * Este tipo de servicio no requiere informe por lo que la orden de trabajo será cerrada al guardar esta informacion.
     * @param array $datos
     * @return int
     */
    public function guardarVerificacionMarbetes(Array $datos)
    {
        if (isset($datos['idRecepcionMuestras']))
        {
            $this->modelo = new RecepcionMuestrasModelo();
            $proceso = $this->modelo->getAdapter()
                    ->getDriver()
                    ->getConnection();
            if (!$proceso->beginTransaction())
            {
                throw new \Exception('No se pudo iniciar la transacción en: Guardar recepcion_muestra');
            }

            $muestras = $datos['idRecepcionMuestras'];
            foreach ($muestras as $key => $valor)
            {
                $datosRecepcion = array(
                    'fk_identificador' => $datos['identificador'],
                    'fecha_verificada' => date('Y-m-d'),
                    'es_idonea' => 'SI',
                    'observacion_verificacion' => 'CORRESPONDIENTE A MARBETES',
                    'estado_actual' => 'ALMACENADA',
                    'estado_aprobacion' => 'APROBADO'
                );
                $this->modelo = new RecepcionMuestrasModelo();
                $this->modelo->actualizar($datosRecepcion, $datos['idRecepcionMuestras'][$key]);
            }

            $marbetes = $datos['fecha_impresion'];
            foreach ($marbetes as $key => $valor)
            {
                $datosmarbetes = array(
                    'fecha_impresion' => $datos['fecha_impresion'][$key],
                    'inicio_serie' => $datos['inicio_serie'][$key],
                    'fin_serie' => $datos['fin_serie'][$key]
                );
                $mdeloMarbetes = new MarbetesModelo();
                $mdeloMarbetes->actualizar($datosmarbetes, $key);
            }

            //Actualzamos la orden de trabajo 
            $ot = new \Agrodb\Laboratorios\Modelos\OrdenesTrabajosLogicaNegocio();
            $otDatos = array(
                'id_orden_trabajo' => $datos['idOrdenTrabajo'],
                'fk_identificador' => $datos['identificador'],
                'estado' => Constantes::estado_OT()->FINALIZADA);
            $ot->guardar($otDatos);

            $proceso->commit();
        }

        return true;
    }

    /**
     * Permite enviar notificaciones al cliente de forma manual
     * @param array $datos
     */
    public function enviarNotificacionClienteManual(Array $datos)
    {
        $notificar = new \Agrodb\Correos\Modelos\CorreosLogicaNegocio();
        $notificar->notificarClienteManual($datos['id_solicitud'], $datos["identificador_cliente"], $datos["asunto"], $datos["cuerpo"]);
    }

    /**
     * Enviar notificación de muestra no idónea
     * @param array $datos
     */
    public function enviarNoticiacionNoIdonea(Array $datos)
    {
        $notificar = new \Agrodb\Correos\Modelos\CorreosLogicaNegocio();
        $notificar->notificarMuestraNoIdonea($datos['id_solicitud'], $datos["usuario_guia"], $datos['urlArchivo'], $datos['identificador']);
    }

    /**
     * Actualizar en la tabla recepcion_muestra
     * Actualiza datos ingresados por Responsable Técnico
     *
     * @param array $datos
     * @return int
     */
    public function guardarValidacion(Array $datos)
    {
        $this->modelo = new RecepcionMuestrasModelo();
        $proceso = $this->modelo->getAdapter()
                ->getDriver()
                ->getConnection();
        if (!$proceso->beginTransaction())
        {
            throw new \Exception('No se pudo iniciar la transacción en: Guardar recepcion_muestra');
        }

        $d = $datos['estado_aprobacion'];
        foreach ($d as $key => $valor)
        {
            if ($valor !== '')
            {
                $datosValidacion = array(
                    'estado_aprobacion' => $valor,
                    'observacion_aprobacion' => $datos['observacion_aprobacion'][$key],
                    'nuevo_analisis' => isset($datos['nuevo_analisis'][$key]) ? 'SI' : 'NO',
                    'fk_identificador2' => $datos['identificador']
                );
                if ($valor == 'APROBADO') //estado_aprobacion
                {
                    $datosValidacion['estado_actual'] = Constantes::estado_MU()->ALMACENADA;
                    $datosValidacion['fecha_fin_analisis'] = date('Y-m-d');
                } else if ($valor == 'NO APROBADO')
                {
                    $datosValidacion['contador_no_aprobado'] = (Integer) $datos['contador_no_aprobado'][$key] + 1;
                    $datosValidacion['estado_actual'] = Constantes::estado_MU()->NO_APROBADO;
                }
                $this->modelo = new RecepcionMuestrasModelo();
                $this->modelo->actualizar($datosValidacion, $key);
            }
        }
        $proceso->commit();
        return true;
    }

    /**
     * Actualizar el campo de etiquetas para imprimir
     * @param type $cEtiqueta
     * @param type $ordenTrabajo
     */
    public function crearEtiquetas($cEtiqueta, $ordenTrabajo)
    {
        $muestras = $this->buscarLista(array("id_orden_trabajo" => $ordenTrabajo));
        foreach ($muestras as $fila)
        {
            $cadena = $this->datosEtiquetas($ordenTrabajo, $fila->id_recepcion_muestras, $cEtiqueta);
            $datosBd = array("id_recepcion_muestras" => $fila->id_recepcion_muestras, "etiqueta_imprimir" => $cadena);
            $this->modelo->actualizar($datosBd, $fila->id_recepcion_muestras);
        }
        return true;
    }

    /**
     * Contruye la cadena para la etiqueta
     * @param type $idOrden
     * @param type $idMustra
     * @param type $cEtiqueta
     * @return type
     */
    public function datosEtiquetas($idOrden, $idMustra, $cEtiqueta)
    {

        $cadena = str_replace(':"true"}', '', $cEtiqueta);
        $cadena = str_replace('[{', '', $cadena);
        $cadena = str_replace('{', '', $cadena);
        $cadena = str_replace(']', '', $cadena);
        $cadena = str_replace('"', '\'', $cadena);
        $consulta = "SELECT ir.id_orden_trabajo,
        dv.id_recepcion_muestras,
        dv.codigo,
        dv.etiqueta || ':' ||  dv.valor as etiqueta,
        dv.tipo
        FROM g_laboratorios.datos_validados_informe dv
        JOIN g_laboratorios.informe_resultados_analisis ir ON ir.id_informe_analisis = dv.id_informe_analisis
        WHERE ir.id_orden_trabajo = " . $idOrden . " AND dv.codigo IN (" . $cadena . ") AND (dv.id_recepcion_muestras = " . $idMustra . " or dv.id_recepcion_muestras is null)";

        $resultados = $this->modelo->ejecutarSqlNativo($consulta);
        $cadEtiquetas = "";
        foreach ($resultados as $fila)
        {
            $cadEtiquetas.=";" . $fila->etiqueta;
        }
        return substr($cadEtiquetas, 1);
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
     * @return RecepcionMuestrasModelo
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
    public function buscarRecepcionMuestras()
    {
        $consulta = "SELECT * FROM recepcion_muestras";
        return $this->modelo->ejecutarConsulta($consulta);
    }

    /**
     * Recupera las muestras de la solicitud de acuerdo al tipo de analisis
     */
    public function buscarMuestras($arrayParametros = null)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['id_recepcion_muestras']))
        {
            $arrayWhere[] = " id_recepcion_muestras = {$arrayParametros['id_recepcion_muestras']}";
        }
        if (!empty($arrayParametros['id_orden_trabajo']))
        {
            $arrayWhere[] = " id_orden_trabajo = {$arrayParametros['id_orden_trabajo']}";
        }
        if (!empty($arrayParametros['id_solicitud']))
        {
            $arrayWhere[] = " id_solicitud = {$arrayParametros['id_solicitud']}";
        }
        if (!empty($arrayParametros['id_laboratorio']))
        {
            $arrayWhere[] = " id_laboratorio = {$arrayParametros['id_laboratorio']}";
        }
        if (!empty($arrayParametros['id_laboratorios_provincia']))
        {
            $arrayWhere[] = " id_laboratorios_provincia = {$arrayParametros['id_laboratorios_provincia']}";
        }
        if (!empty($arrayParametros['es_aceptada']))
        {
            $arrayWhere[] = " es_aceptada = '{$arrayParametros['es_aceptada']}'";
        }
        if (!empty($arrayParametros['es_idonea']))
        {
            $arrayWhere[] = " es_idonea = '{$arrayParametros['es_idonea']}'";
        }
        if (!empty($arrayParametros['nuevo_analisis']))
        {
            $arrayWhere[] = " nuevo_analisis = '{$arrayParametros['nuevo_analisis']}'";
        }
        if (!empty($arrayParametros['estado_actual']))
        {
            if (is_array($arrayParametros['estado_actual']))
            {
                $arrayWhere[] = " estado_actual IN ('" . implode("','", $arrayParametros['estado_actual']) . "')";
            } else
            {
                $arrayWhere[] = " estado_actual = '{$arrayParametros['estado_actual']}'";
            }
        }
        if (!empty($arrayParametros['estado_aprobacion']))
        {
            $arrayWhere[] = " estado_aprobacion = '{$arrayParametros['estado_aprobacion']}'";
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
        rm.id_recepcion_muestras,
        rm.id_detalle_solicitud,
        rm.id_laboratorio,
        rm.identificador,
        rm.id_orden_trabajo,
        rm.fk_identificador,
        rm.id_laboratorios_provincia,
        rm.id_servicio,
        rm.tipo_recepcion,
        rm.codigo_usu_muestra,
        rm.codigo_lab_muestra,
        rm.numero_muestra,
        rm.es_aceptada,
        rm.fecha_recepcion,
        rm.observacion_recepcion,
        rm.fecha_verificada,
        rm.es_idonea,
        rm.observacion_verificacion,
        rm.no_idonea_analisis,
        rm.conservacion_muestra,
        rm.url_archivo_adjunto,
        rm.fecha_inicio_analisis,
        rm.fecha_fin_analisis,
        rm.observacion_analisis,
        rm.estado_actual,
        rm.estado_aprobacion,
        rm.observacion_aprobacion,
        rm.nuevo_analisis,
        rm.fecha_toma,
        rm.responsable_toma,
        rm.id_reemplazo,
        rm.id_solicitud_derivacion,
        rm.etiqueta_imprimir,
        rm.fecha_fin_almacenamiento,
        rm.fecha_desecho,
        rm.fk_identificador2,
        rm.fecha_no_idonea_analisis,
        rm.tipo,
        rm.acreditado,
        rm.derivada,
        rm.id_solicitud_confirmacion,
        rm.por_confirmar,
        rm.secuencial,
        s.nombre,
        s.rama_nombre
        FROM
        g_laboratorios.recepcion_muestras rm
        INNER JOIN g_laboratorios.servicios s ON s.id_servicio = rm.id_servicio
        $where ORDER BY rm.codigo_usu_muestra, rm.numero_muestra";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Retorna los datos de la recepcion_muestras según la orden de trabajo
     * Se filtra por las muestras que tienene el tipo INDIVIDUAL o ELEMENTO del paquete, no PAQUETE
     * @param type $idOrdenTrabajo
     * @return type
     */
    public function buscarRMServicios($idOrdenTrabajo)
    {
        $consulta = "SELECT
        rm.id_orden_trabajo,
        rm.id_servicio,
        ser.rama,
        ser.rama_nombre,
        (SELECT c.despliegue
                FROM g_laboratorios.campos_resultados_informes c
                WHERE c.nivel = 0 AND c.estado_registro= 'ACTIVO' AND c.id_servicio::text = ANY (string_to_array((SELECT serv.rama
                        FROM g_laboratorios.servicios serv
                        WHERE serv.id_servicio = rm.id_servicio AND tipo_campo = 'CONTENEDOR'), ',')) LIMIT 1) AS despliegue
        FROM
        g_laboratorios.recepcion_muestras AS rm
        INNER JOIN g_laboratorios.servicios AS ser ON ser.id_servicio = rm.id_servicio
        WHERE
        rm.id_orden_trabajo = $idOrdenTrabajo AND rm.tipo IN ('INDIVIDUAL','ELEMENTO')
        GROUP BY
        rm.id_orden_trabajo,
        rm.id_servicio,
        ser.rama,
        ser.rama_nombre";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Columnas para guardar junto con la solicitud
     * @return string[]
     */
    public function columnas()
    {
        $columnas = array(
            'identificador',
            'tipo_recepcion',
            'observacion_recepcion',
            'es_aceptada',
            'conservacion_muestra',
            'id_orden_trabajo',
            'id_servicio',
            'numero_muestra',
            'id_laboratorio',
            'id_detalle_solicitud'
        );

        return $columnas;
    }

    /**
     * Actualizar el campos acreditado
     * @param type $idRecepcionMuestras
     * @return type
     */
    public function actualizarAcreditado($idRecepcionMuestras, $valor)
    {
        $consulta = "UPDATE g_laboratorios.recepcion_muestras SET acreditado = '$valor' WHERE id_recepcion_muestras = $idRecepcionMuestras";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

}
