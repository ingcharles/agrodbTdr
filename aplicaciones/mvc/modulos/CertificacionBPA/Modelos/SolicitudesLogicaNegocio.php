<?php

/**
 * Lógica del negocio de SolicitudesModelo
 *
 * Este archivo se complementa con el archivo SolicitudesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-03-23
 * @uses    SolicitudesLogicaNegocio
 * @package CertificacionBPA
 * @subpackage Modelos
 */

namespace Agrodb\CertificacionBPA\Modelos;

use Agrodb\CertificacionBPA\Modelos\IModelo;
use Agrodb\Token\Modelos\TokenLogicaNegocio;
use Agrodb\FormulariosInspeccion\Modelos\Bpaf01LogicaNegocio;
use Agrodb\FormulariosInspeccion\Modelos\Bpaf01DetalleLogicaNegocio;
use Agrodb\RevisionFormularios\Modelos\AsignacionInspectorLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\OperacionesLogicaNegocio;




use Agrodb\Core\Excepciones\GuardarExcepcion;
use Agrodb\Core\Excepciones\GuardarExcepcionConDatos;
use Agrodb\Core\Excepciones\BuscarExcepcion;
use Agrodb\Core\JasperReport;
use Exception;

class SolicitudesLogicaNegocio implements IModelo
{

    private $modeloSolicitudes = null;
    private $lNegocioToken = null;
    private $lNegocioBpaf01DetalleLogica = null;
    private $lNegocioBpaf01Logica = null;
    private $lNegocioAsignacionInspector = null;
    private $lNegocioOperaciones = null;

    /* Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloSolicitudes = new SolicitudesModelo();
        $this->lNegocioToken = new TokenLogicaNegocio();
        $this->lNegocioBpaf01DetalleLogica = new Bpaf01DetalleLogicaNegocio();
        $this->lNegocioBpaf01Logica = new Bpaf01LogicaNegocio();
        $this->lNegocioAsignacionInspector = new AsignacionInspectorLogicaNegocio();
        $this->lNegocioOperaciones = new OperacionesLogicaNegocio();

        $this->rutaFecha = date('Y') . '/' . date('m') . '/' . date('d');
    }

    /**
     * Guarda el registro actual
     * @param array $datos
     * @return int
     */
    public function guardar(array $datos)
    {
        $tablaModelo = new SolicitudesModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();

        if ($tablaModelo->getIdSolicitud() != null && $tablaModelo->getIdSolicitud() > 0) {
            return $this->modeloSolicitudes->actualizar($datosBd, $tablaModelo->getIdSolicitud());
        } else {
            unset($datosBd["id_solicitud"]);
            return $this->modeloSolicitudes->guardar($datosBd);
        }
    }

    /**
     * Borra el registro actual
     * @param string Where|array $where
     * @return int
     */
    public function borrar($id)
    {
        $this->modeloSolicitudes->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param  int $id
     * @return SolicitudesModelo
     */
    public function buscar($id)
    {
        return $this->modeloSolicitudes->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloSolicitudes->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloSolicitudes->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarSolicitudes()
    {
        $consulta = "SELECT * FROM " . $this->modeloSolicitudes->getEsquema() . ". solicitudes";
        return $this->modeloSolicitudes->ejecutarSqlNativo($consulta);
    }

    public function buscarEstadoSolicitudes($identificador)
    {
        $consulta = "   SELECT
                            DISTINCT estado
                        FROM
                            g_certificacion_bpa.solicitudes
                        WHERE
                            identificador in ('$identificador')
                        GROUP BY
                            estado; ";

        return $this->modeloSolicitudes->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta consulta(SQL), para la obtención de las solicitudes en estado de inspección.
     *
     * @return array|ResultSet
     */
    public function buscarSolicitudesBpaInspeccionMovil($arrayParametros)
    {

        $arrayToken = $this->lNegocioToken->validarToken(RUTA_PUBLIC_KEY_AGROSERVICIOS);

        if ($arrayToken['estado'] == 'exito') {
            $res = null;
            $consulta = "SELECT row_to_json (res) AS res FROM ( SELECT array_to_json(array_agg(row_to_json(listado))) as cuerpo FROM (
                        	SELECT
                        		s.id_solicitud
                        		, s.identificador
                        		, s.fecha_creacion
                        		, s.es_asociacion
                        		, s.tipo_solicitud
                        		, s.tipo_explotacion
                        		, s.identificador_operador
                        		, UPPER(CASE WHEN o.razon_social = '' THEN o.nombre_representante ||' '|| o.apellido_representante ELSE o.razon_social END) AS nombre_operador
                        		, st.nombre_lugar AS nombre_sitio
                                , st.provincia || ' - ' || st.canton || ' - ' || st.parroquia  AS ubicacion_sitio
                                , o.telefono_uno || ' - ' || o.celular_uno || ' - ' || o.correo  AS contacto_operador
                                , st.direccion AS direccion_sitio
                        		,  STRING_AGG(sap.nombre_producto, ', ') AS nombre_productos
                        		, s.estado
                        		, to_char(s.fecha_auditoria_programada, 'YYYY-MM-DD') AS fecha_auditoria_programada
                        		, s.provincia_revision
                        		, s.fecha_revision
                        		, s.tipo_revision
                        	FROM
                        		g_certificacion_bpa.solicitudes s
                        		INNER JOIN g_certificacion_bpa.sitios_areas_productos sap ON s.id_solicitud = sap.id_solicitud
								INNER JOIN g_operadores.sitios st ON s.id_sitio_unidad_produccion = st.id_sitio
                        		INNER JOIN g_operadores.operadores o ON s.identificador_operador = o.identificador
                        		INNER JOIN g_catalogos.localizacion l ON UPPER(s.provincia_revision) = UPPER(l.nombre) and categoria = 1
                        	WHERE
                        		s.estado in ('inspeccion')--,'subsanacion'
                                AND s.id_resolucion = 1
                        		AND l.id_localizacion = " . $arrayParametros['provincia'] . "
                        		GROUP BY s.id_solicitud, nombre_operador, st.id_sitio, l.id_localizacion, o.telefono_uno || ' - ' || o.celular_uno || ' - ' || o.correo
                        		ORDER BY s.id_solicitud
                        	) AS listado ) AS res;";

            $array = array();

            try {
                $res = $this->modeloSolicitudes->ejecutarSqlNativo($consulta);
                $array['estado'] = 'exito';
                $array['mensaje'] = "Los datos han sido obtenidos satisfactoriamente";
                $cuerpo = json_decode($res->current()->res, true);
                $array['cuerpo'] = $cuerpo['cuerpo'] != null ? $cuerpo['cuerpo'] : [];
                echo json_encode($array);
            } catch (Exception $ex) {
                $array['estado'] = 'error';
                $array['mensaje'] = 'Error al obtener datos: ' . $ex;
                http_response_code(400);
                echo json_encode($array);
                throw new BuscarExcepcion($ex, array('archivo' => 'SolicitudesLogicaNegocio', 'metodo' => 'buscarSolicitudesBpaInspeccionMovil', 'consulta' => $consulta));
            }
        } else {
            echo json_encode($arrayToken);
        }
    }

    /**
     * Método para realizar el proceso de guardado de inspección de certificado BPA por apliactivo movil.
     *
     * @return array|ResultSet
     */
    public function guardarDatosInspeccionMovil($arrayParametros)
    {

        $arrayToken = $this->lNegocioToken->validarToken(RUTA_PUBLIC_KEY_AGROSERVICIOS);

        if ($arrayToken['estado'] == 'exito') {

            try {

                $procesoIngreso = $this->modeloSolicitudes->getAdapter()
                    ->getDriver()
                    ->getConnection();
                $procesoIngreso->beginTransaction();


                foreach ($arrayParametros['inspeccion'] as $value) {

                    $arrayResultadoInspeccion = array();
                    $arrayResumenChecklistInspeccion = array();
                    $arrayAuditoria = array();

                    foreach ($value->cabecera as $cabeceraLlave => $cabeceraValor) {
                        $arrayResultadoInspeccion += [
                            $cabeceraLlave => $cabeceraValor
                        ];
                    }

                    $solicitudBPA = $this->buscar($arrayResultadoInspeccion['id_solicitud']);

                    if ($solicitudBPA->getFechaAuditoria() == null) {
                        $arrayResultadoInspeccion += [
                            'fecha_auditoria' => $arrayResultadoInspeccion['fecha_auditoria']
                        ];
                    } else {
                        $arrayAuditoria = array(
                            'idSolicitud' => $arrayResultadoInspeccion['id_solicitud'],
                            'tipoAuditoria' => 'Complementaria'
                        );

                        $auditoriaComplementaria = $this->buscarAuditoriasSolicitadas($arrayAuditoria);

                        if (isset($auditoriaComplementaria->current()->id_solicitud)) {
                            $arrayResultadoInspeccion += [
                                'fecha_auditoria_complementaria' => $arrayResultadoInspeccion['fecha_auditoria']
                            ];
                        }
                    }

                    // Registra la fecha máxima en la que el usuario debe dar respuesta a la subsanación solicitada
                    if ($arrayResultadoInspeccion['estado'] == 'subsanacion') {
                        $fechaMaxRespuesta = $this->sumaDiaSemana(date("Y-m-d"), 15);
                        $arrayResultadoInspeccion += [
                            'fecha_max_respuesta' => $fechaMaxRespuesta
                        ];
                    }

                    //                 // Realiza la actualizacion de los campos de la tabla de solicitud
                    $idDatoInspeccionMovil = $this->guardar($arrayResultadoInspeccion);

                    if ($idDatoInspeccionMovil) {

                        //Actualiza los resumenes de inspecciones anteriores
                        $this->lNegocioBpaf01Logica->actualizarEstadoInspeccionBpaPorIdSolicitud($arrayResultadoInspeccion['id_solicitud']);

                        foreach ($value->checklist_resumen as $resumenChecklistLlave => $resumenChecklistValor) {

                            if (!is_array($resumenChecklistValor)) {
                                $arrayResumenChecklistInspeccion += [
                                    $resumenChecklistLlave => $resumenChecklistValor
                                ];
                            }
                        }

                        // Guarda el resumen de checklist de inspeccion
                        $idInspeccionBpa = $this->lNegocioBpaf01Logica->guardar($arrayResumenChecklistInspeccion);

                        foreach ($value->checklist_resumen->checklist_inspeccion as $item) {
                            $item->id_padre = $idInspeccionBpa;
                            $array = json_decode(json_encode($item), true);

                            $this->lNegocioBpaf01DetalleLogica->guardar($array);
                        }


                        $idSitio = $solicitudBPA->getIdSitioUnidadProduccion();
                        $solicitudes = $this->obtenerSolicitudesPorAsignarInspector($idSitio);

                        foreach ($solicitudes as $fila) {
                            $arrayResultadoInspeccion['id_operacion'] = $fila['id_operacion'];
                            $this->lNegocioOperaciones->guardarResultadoInspeccion($arrayResultadoInspeccion);
                        }

                   

                    }
                }
                echo json_encode(array('estado' => 'exito', 'mensaje' => 'Registros almancenados en el Sistema GUIA exitosamente'));
                $procesoIngreso->commit();
            } catch (Exception $ex) {
                echo json_encode(array('estado' => 'error', 'mensaje' => $ex->getMessage()));
                $procesoIngreso->rollback();
                throw new GuardarExcepcionConDatos($ex);
            }
        } else {
            echo json_encode($arrayToken);
        }

        //             $procesoIngreso->commit();

        //             return $idDatoInspeccionMovil;

        //         } catch (GuardarExcepcion $ex) {
        //             $procesoIngreso->rollback();
        //             throw new \Exception($ex->getMessage());
        //         }

    }

    public function buscarAuditoriasSolicitadas($arrayParametros)
    {

        $idSolicitud = $arrayParametros['idSolicitud'];
        $tipoAuditoria = $arrayParametros['tipoAuditoria'];

        $consulta = "SELECT
                    	a.*
                    FROM
                    	g_certificacion_bpa.auditorias_solicitadas a
                    WHERE
                    	a.id_solicitud =  " . $idSolicitud . " and
                    	a.id_tipo_auditoria in (
                    		SELECT
                    			id_tipo_auditoria
                    		FROM
                    			g_certificacion_bpa.tipos_auditorias
                    		WHERE
                            	tipo_auditoria like '%" . $tipoAuditoria . "%' and
                            	estado = 'Activo') and
                    	a.estado = 'Activo';";

        return $this->modeloSolicitudes->ejecutarSqlNativo($consulta);
    }

    public function sumaDiaSemana($fecha, $dias)
    {

        $datestart = strtotime($fecha);
        $diasemana = date('N', $datestart);
        $totaldias = $diasemana + $dias;
        $findesemana = intval($totaldias / 5) * 2;
        $diasabado = $totaldias % 5;
        if ($diasabado == 6) $findesemana++;
        if ($diasabado == 0) $findesemana = $findesemana - 2;

        $total = (($dias + $findesemana) * 86400) + $datestart;

        return date('Y-m-d', $total);
    }


    /**
     * Función para crear el PDF del checklist de inspeccion
     */
    public function generarChecklistInspeccionBpa($idSolicitud, $nombreArchivo)
    {
        $jasper = new JasperReport();
        $datosReporte = array();

        $ruta = CERT_BPA_URL_CHECK_MOV_TCPDF . $this->rutaFecha . '/';

        if (!file_exists($ruta)) {
            mkdir($ruta, 0777, true);
        }

        $rutaChecklistBpa = CERT_BPA_URL_CHECK_MOV . $this->rutaFecha . '/';

        $datosReporte = array(
            'rutaReporte' => 'CertificacionBPA/vistas/reportes/checklistAplicativoMovilBpa.jasper',
            'rutaSalidaReporte' => 'CertificacionBPA/archivos/checklistsMovil/' . $this->rutaFecha . '/' . $nombreArchivo,
            'tipoSalidaReporte' => array('pdf'),
            'parametrosReporte' => array('idSolicitud' => $idSolicitud, 'rutaLogoAgro' => RUTA_IMG_GENE . 'agrocalidad.png'),
            'conexionBase' => 'SI'
        );

        $jasper->generarArchivo($datosReporte);

        $rutaChecklist = $rutaChecklistBpa . $nombreArchivo . '.pdf';

        return $rutaChecklist;
    }


    public function obtenerSolicitudesPorGenerarChecklist()
    {

        $consulta = "SELECT
                    	DISTINCT
                    	so.id_solicitud
                    	, so.identificador
                    	, so.fecha_creacion
                    	, so.es_asociacion
                    	, so.tipo_solicitud
                    	, so.tipo_explotacion
                    	, so.origen_inspeccion
                    	, so.estado_checklist
						, op.id_operador_tipo_operacion
                    FROM
                    	g_certificacion_bpa.solicitudes so 
						INNER JOIN g_operadores.areas ar ON so.id_sitio_unidad_produccion = ar.id_sitio and ar.estado = 'creado'
						INNER JOIN g_operadores.productos_areas_operacion pa ON pa.id_area = ar.id_area and pa.estado = 'registrado'
						INNER JOIN g_operadores.operaciones op ON op.id_operacion = pa.id_operacion and op.estado = 'registrado'
						INNER JOIN g_catalogos.tipos_operacion top ON top.id_tipo_operacion = op.id_tipo_operacion and	top.codigo || top.id_area IN ('PROAI')
                    WHERE
                    	origen_inspeccion = 'aplicativoMovil'
                    	and estado_checklist = 'generar';";

        return $this->modeloSolicitudes->ejecutarSqlNativo($consulta);
    }

    public function obtenerSolicitudesPorAsignarInspector($idSitio)
    {

        $consulta = "SELECT
                    	DISTINCT
                    	so.id_solicitud
                    	, so.identificador
                    	, so.fecha_creacion
                    	, so.es_asociacion
                    	, so.tipo_solicitud
                    	, so.tipo_explotacion
                    	, so.origen_inspeccion
                    	, so.estado_checklist
						, op.id_operador_tipo_operacion
                        , min(op.id_operacion) AS id_operacion
                    FROM
                    	g_certificacion_bpa.solicitudes so 
						INNER JOIN g_operadores.areas ar ON so.id_sitio_unidad_produccion = ar.id_sitio and ar.estado = 'creado'
						INNER JOIN g_operadores.productos_areas_operacion pa ON pa.id_area = ar.id_area and pa.estado = 'registrado'
						INNER JOIN g_operadores.operaciones op ON op.id_operacion = pa.id_operacion and op.estado = 'registrado'
						INNER JOIN g_catalogos.tipos_operacion top ON top.id_tipo_operacion = op.id_tipo_operacion and	top.codigo || top.id_area IN ('PROAI')
                    WHERE
                    	origen_inspeccion = 'aplicativoMovil'
                    	and estado_checklist = 'generar'
                        and so.id_sitio_unidad_produccion =  " . $idSitio . "
                        group by id_solicitud, id_operador_tipo_operacion;";

        return $this->modeloSolicitudes->ejecutarSqlNativo($consulta);
    }
}
