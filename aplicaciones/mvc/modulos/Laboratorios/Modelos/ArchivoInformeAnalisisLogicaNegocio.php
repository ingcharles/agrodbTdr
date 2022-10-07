<?php

/**
 * Lógica del negocio de  ArchivoInformeAnalisisModelo
 *
 * Este archivo se complementa con el archivo   ArchivoInformeAnalisisControlador.
 *
 * @author DATASTAR
 * @uses       ArchivoInformeAnalisisLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */

namespace Agrodb\Laboratorios\Modelos;

use Agrodb\Laboratorios\Modelos\IModelo;
use Agrodb\Laboratorios\Modelos\InformeResultadosAnalisisModelo;
use Agrodb\Core\Constantes;

class ArchivoInformeAnalisisLogicaNegocio implements IModelo
{

    private $modelo = null;

    /**
     * Constructor
     * 
     * @retorna void
     */
    public function __construct()
    {
        $this->modelo = new ArchivoInformeAnalisisModelo();
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {

        $tablaModelo = new ArchivoInformeAnalisisModelo($datos);

        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdArchivoInformeAnalisis() != null && $tablaModelo->getIdArchivoInformeAnalisis() > 0)
        {


            return $this->modelo->actualizar($datosBd, $tablaModelo->getIdArchivoInformeAnalisis());
        } else
        {
            unset($datosBd["id_archivo_informe_analisis"]);
            return $this->modelo->guardar($datosBd);
        }
    }

    /**
     * Guarda el adjunto del informe de análisis
     * @param array $datos
     * @return type
     */
    public function guardarAdjunto(Array $datos)
    {

        $rutaArchivo = $datos['ruta_archivo'] . $datos['nombre_archivo'];

        $datosBd = array("fk_id_archivo_informe_analisis" => $datos['id_archivo_informe_analisis'], "ruta_archivo" => $rutaArchivo,
            "nombre_informe" => "Archivo adjunto: " . $datos['nombre_informe'], "nivel" => 2, "descargado" => "NO", "observacion_general" => "Archivo adjuto al informe", "observacion_estado" => "ADJUNTO");
        return $this->modelo->guardar($datosBd);
    }

    /**
     * Anula un informe y en caso de ser requerido se crea un nuevo informe sustituto
     * @param array $datos
     */
    public function anular(Array $datos, $idLaboratorio)
    {
        $tablaModelo = new ArchivoInformeAnalisisModelo($datos);
    }

    /**
     * Ejecuta la funcion para crear un nuevo informe 
     * select g_laboratorios.f_crear_informe_hijo(4, 381, 'FP18CGLS0001', 3);
     * @param array $datos
     * @param type $idLaboratorio
     * @return type
     */
    public function crearInforme(Array $datos, $idLaboratorio)
    {

        $tablaModelo = new ArchivoInformeAnalisisModelo($datos);
        //Buscamos el codigo de la orden de trabajo
        $consulta = "SELECT
                ot.codigo
                FROM g_laboratorios.informe_resultados_analisis AS ir
                INNER JOIN g_laboratorios.ordenes_trabajos AS ot ON ot.id_orden_trabajo = ir.id_orden_trabajo
                WHERE ir.id_informe_analisis=" . $tablaModelo->getIdInformeAnalisis();
        $resultado = $this->modelo->ejecutarSqlNativo($consulta);
        $codigo_orden = "";
        foreach ($resultado as $fila)
        {
            $codigo_orden = $fila->codigo;
        }

        $query = "select g_laboratorios.f_crear_informe_hijo(" . $tablaModelo->getFkIdArchivoInformeAnalisis() . ", " . $idLaboratorio . ", '" . $codigo_orden . "', " . $tablaModelo->getIdInformeAnalisis() . ");";
        return $this->modelo->ejecutarSqlNativo($query);
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
     * @return ArchivoInformeAnalisisModelo
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
    public function buscarArchivoInformeAnalisis()
    {
        $consulta = "SELECT * FROM " . $this->modelo->getEsquema() . ". archivo_informe_analisis";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Buscar informes para firmar.
     *
     * @return array|ResultSet
     */
    public function buscarInformesFirmar($idPadre)
    {
        $consulta = "SELECT * FROM g_laboratorios.archivo_informe_analisis AS ai";
        $consulta .= " WHERE ai.fk_id_archivo_informe_analisis = " . $idPadre . " AND ai.estado_informe='ACTIVO'";
        $consulta .= " AND ai.nivel=1 AND (SELECT count(*) FROM g_laboratorios.archivo_informe_analisis ar";
        $consulta .= " WHERE ar.fk_id_archivo_informe_analisis=ai.id_archivo_informe_analisis  )>0";

        return $this->modelo->ejecutarSqlNativo($consulta);
    }
    
    /**
     * Buscar informes para firmar.
     *
     * @return array|ResultSet
     */
    public function buscarInformesModificar($idPadre)
    {
        $consulta = "SELECT * FROM g_laboratorios.archivo_informe_analisis AS ai";
        $consulta .= " WHERE ai.fk_id_archivo_informe_analisis = " . $idPadre ." AND estado_informe <>'ANULADO'" ;
        $consulta .= " AND ai.nivel=1 AND (SELECT count(*) FROM g_laboratorios.archivo_informe_analisis ar";
        $consulta .= " WHERE ar.fk_id_archivo_informe_analisis=ai.id_archivo_informe_analisis  )>0";

        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Busca clientes para el combo de clientes de informes para consolidar
     * @param type $idLaboratorio
     * @return type
     */
    public function buscarClientesInforme($idLaboratoriosProvincia, $estado_orden)
    {
        $consulta = "SELECT
        informe.id_archivo_informe_analisis,
        ia.id_informe_analisis,
        ot.id_laboratorio,
        informe.nombre_informe
        FROM
        g_laboratorios.archivo_informe_analisis AS informe
        INNER JOIN g_laboratorios.informe_resultados_analisis AS ia ON ia.id_informe_analisis = informe.id_informe_analisis
        INNER JOIN g_laboratorios.ordenes_trabajos AS ot ON ot.id_orden_trabajo = ia.id_orden_trabajo
        WHERE
        informe.estado_informe = 'ACTIVO' AND
        informe.nivel = 0 AND ot.id_laboratorios_provincia=" . $idLaboratoriosProvincia . " AND ot.estado like '%" . $estado_orden . "%'";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    
    public function buscarClientesInformeModificar($idLaboratoriosProvincia,$anio,$mes)
    {
        $consulta = "SELECT
        informe.id_archivo_informe_analisis,
        ia.id_informe_analisis,
        ot.id_laboratorio,
        informe.nombre_informe
        FROM
        g_laboratorios.archivo_informe_analisis AS informe
        INNER JOIN g_laboratorios.informe_resultados_analisis AS ia ON ia.id_informe_analisis = informe.id_informe_analisis
        INNER JOIN g_laboratorios.ordenes_trabajos AS ot ON ot.id_orden_trabajo = ia.id_orden_trabajo
        WHERE  EXTRACT(YEAR FROM fecha_creacion)=".$anio." AND EXTRACT(MONTH FROM fecha_creacion)=".$mes."
        AND informe.nivel = 0 AND ot.id_laboratorios_provincia=" . $idLaboratoriosProvincia . ";";
        
        return $this->modelo->ejecutarSqlNativo($consulta);
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
            $where = "fk_id_archivo_informe_analisis IS NULL order  by orden";
        } else
        {
            $where = "fk_id_archivo_informe_analisis=" . $idPadre . " order  by orden";
        }
        return $this->modelo->buscarLista($where);
    }

    /**
     * Enviar notificación de inoforme
     * @param array $datos
     */
    public function enviar(Array $datos)
    {
        $identificadorCreaSol = null;
        //si se desea enviar al usuario que registró la solicitud
        if (isset($datos['checkNotificarCreaSol']))
        {
            //buscar datos de la solicitud
            $buscaUsuarioGuia = $this->buscarUsuarioGuia($datos['id_archivo_informe_analisis']);
            $fila = $buscaUsuarioGuia->current();
            $identificadorCreaSol = $fila->usuario_guia;
        }

        //buscar los correos configurados en catalogos de laboratorios
        $lNCatalogos = new \Agrodb\Catalogos\Modelos\CatalogosLaboratoriosLogicaNegocio();
        $destinatarioCorreoCopia = array();
        if (isset($datos['destinatario_correo']))
        {
            $buscaDestino = $lNCatalogos->buscarLista(array('id_catalogos' => $datos['destinatario_correo']));

            foreach ($buscaDestino as $fila)
            {
                $destinatarioCorreoCopia[] = $fila->descripcion;
            }
        }

        //buscar la ruta el archivo
        $datosArchivo = new ArchivoInformeAnalisisModelo();
        $datosArchivo = $this->modelo->buscar($datos['id_archivo_informe_analisis']);
        $urlArchivo = URL_MVC_MODULO . "Laboratorios/archivos/informes/firmados/" . $datosArchivo->getRutaArchivo() . "_firmado.pdf";
        $notificar = new \Agrodb\Correos\Modelos\CorreosLogicaNegocio();
        $notificar->notificarInforme($datos['id_archivo_informe_analisis'], $identificadorCreaSol, $urlArchivo, $destinatarioCorreoCopia, $datos['identificador']);
        //luego de notificar al cliente actualizamos la fehcha de envio y el estado del informea a enviado

        $datosActualizar = array("id_archivo_informe_analisis" => $datos['id_archivo_informe_analisis'], "fecha_envio" => 'now()', "estado_informe" => Constantes::estado_informe()->ENVIADO);
        $this->modelo->actualizar($datosActualizar, $datos['id_archivo_informe_analisis']);
    }

    /**
     * Busca el último informe creado
     * @param type $idPadre
     * @return type
     */
    public function buscarUltimoInforme($idPadre)
    {
        $consulta = "select * from g_laboratorios.archivo_informe_analisis where fk_id_archivo_informe_analisis=" . $idPadre . " ORDER BY id_archivo_informe_analisis DESC LIMIT 1";
        return $this->modelo->ejecutarConsulta($consulta);
    }

    /**
     * Busca el identificador del usuario_guia de la solicitud según el id_archivo_informe_analisis
     * @param type $idArchivoInformeAnalisis
     */
    public function buscarUsuarioGuia($idArchivoInformeAnalisis)
    {
        $consulta = "SELECT
        sol.usuario_guia
        FROM
        g_laboratorios.solicitudes AS sol
        INNER JOIN g_laboratorios.ordenes_trabajos AS ot ON ot.id_solicitud = sol.id_solicitud
        INNER JOIN g_laboratorios.informe_resultados_analisis AS ira ON ira.id_orden_trabajo = ot.id_orden_trabajo
        INNER JOIN g_laboratorios.archivo_informe_analisis AS aia ON aia.id_informe_analisis = ira.id_informe_analisis
        WHERE
        aia.id_archivo_informe_analisis = $idArchivoInformeAnalisis";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Buscar los informes de la solicitud
     * @param type $idArchivoInformeAnalisis
     */
    public function buscarInformesSolicitud($arrayParametros)
    {
        $where = null;
        $arrayWhere = array();
        if (!empty($arrayParametros['idSolicitud']))
        {
            $arrayWhere[] = " sol.id_solicitud = '{$arrayParametros['idSolicitud']}'";
        }
        if (!empty($arrayParametros['idOrdenTrabajo']))
        {
            $arrayWhere[] = " ot.id_orden_trabajo = '{$arrayParametros['idOrdenTrabajo']}'";
        }
        if ($arrayWhere)
        {
            $where = implode(' AND ', $arrayWhere);
        }
        if (!empty($where))
        {
            $where = " AND " . $where;
        }
        $consulta = "SELECT
        sol.id_solicitud,
        ot.id_orden_trabajo,
        ot.codigo AS codigo_ot,
        ira.id_informe_analisis,
        aia.id_archivo_informe_analisis,
        aia.nombre_informe,
        aia.fecha_creacion,
        aia.fecha_envio,
        aia.fecha_aprobado,
        aia.fecha_firma,
        aia.firmado,
        aia.descargado,
        aia.alcance,
        aia.sustituto,
        aia.observacion_general,
        aia.estado_informe,
        aia.ruta_archivo
        FROM
        g_laboratorios.solicitudes AS sol
        INNER JOIN g_laboratorios.ordenes_trabajos AS ot ON ot.id_solicitud = sol.id_solicitud
        INNER JOIN g_laboratorios.informe_resultados_analisis AS ira ON ira.id_orden_trabajo = ot.id_orden_trabajo
        INNER JOIN g_laboratorios.archivo_informe_analisis AS aia ON aia.id_informe_analisis = ira.id_informe_analisis
        WHERE aia.estado_informe='ENVIADO' $where ORDER BY id_archivo_informe_analisis";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Bsuca los adjuntos de los informes
     * @param type $idArchivoInformeAnalisis
     * @return type
     */
    public function buscarAdjuntosInformesSolicitud($idArchivoInformeAnalisis)
    {
        $consulta = "SELECT
        aia.id_archivo_informe_analisis,
        aia.nombre_informe,
        aia.fecha_creacion,
        aia.ruta_archivo,
        aia.fk_id_archivo_informe_analisis,
        aia.observacion_estado
        FROM
        g_laboratorios.archivo_informe_analisis AS aia
        WHERE
        aia.fk_id_archivo_informe_analisis = $idArchivoInformeAnalisis AND
        aia.observacion_estado = 'ADJUNTO' ORDER BY id_archivo_informe_analisis
        ";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Busca las muestras acreditdas de un informe
     * @param type $idArchivoInformeAnalisis
     * @return type
     */
    public function acreditacion($idArchivoInformeAnalisis)
    {
        $consulta = "SELECT
        count(ai.fk_id_archivo_informe_analisis) as numero,
        rm.acreditado
        FROM
        g_laboratorios.archivo_informe_analisis AS ai
        INNER JOIN g_laboratorios.recepcion_muestras AS rm ON rm.id_recepcion_muestras = ai.id_recepcion_muestras
        WHERE ai.fk_id_archivo_informe_analisis=" . $idArchivoInformeAnalisis . "
        GROUP BY rm.acreditado";
        return $this->modelo->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta la función para actualizar los estados de los campos de acuerdo a la configuración del formato del informe
     * @param type $id_laboratorio
     * @param type $id_informe_analisis
     * @return type
     */
    public function configurarCampoImprimir($idArchivoInformeAnalisis)
    {
        //Ejecutamos una funcion para establecer que campos son visibles y el orden de acuerdo al formato del informe
        $consulta = "SELECT * from g_laboratorios.f_estado_campos_informe(" . $idArchivoInformeAnalisis . ");";
        return $this->modelo->ejecutarConsulta($consulta);
    }

    /**
     * Busca muestras acreditdas en el informe
     * @param type $idArchivoInformeAnalisis
     * @return type
     */
    public function existeAcreditacion($idArchivoInformeAnalisis)
    {
        //Consultamos si en informe tiene servicios/parametros acreditados 
        $consulta = "SELECT * FROM g_laboratorios.v_existe_acreditacion WHERE fk_id_archivo_informe_analisis=" . $idArchivoInformeAnalisis;

        return $this->modelo->ejecutarConsulta($consulta);
    }

    /**
     * Retorna SI/NO segun si requiere el pago
     * @param type $idArchivoInformeAnalisis
     * @return string
     */
    public function requiereRegistroPago($idArchivoInformeAnalisis)
    {
        $consulta = "SELECT
        ainf.id_archivo_informe_analisis,
        sol.id_solicitud,
        sol.tipo_solicitud,
        sol.exoneracion,
        pag.id_pagos
        FROM
        g_laboratorios.archivo_informe_analisis AS ainf
        INNER JOIN g_laboratorios.informe_resultados_analisis AS inf ON inf.id_informe_analisis = ainf.id_informe_analisis
        INNER JOIN g_laboratorios.ordenes_trabajos AS ot ON ot.id_orden_trabajo = inf.id_orden_trabajo
        INNER JOIN g_laboratorios.solicitudes AS sol ON sol.id_solicitud = ot.id_solicitud
        LEFT JOIN g_laboratorios.pagos AS pag ON pag.id_solicitud = sol.id_solicitud 
        WHERE ainf.id_archivo_informe_analisis = $idArchivoInformeAnalisis";
        $res = $this->modelo->ejecutarSqlNativo($consulta);
        $fila = $res->current();
        if ($fila->exoneracion == 'NO' & $fila->id_pagos == '')
        {
            return 'SI';
        } else
        {
            return 'NO';
        }
    }

}
