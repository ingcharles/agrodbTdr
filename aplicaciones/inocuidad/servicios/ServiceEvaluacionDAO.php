<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 22/02/18
 * Time: 22:16
 */

class ServiceEvaluacionDAO
{
    public function __construct()
    {
    }

    function getAllEvaluacionInStep($usuario,$conexion){
        $queryAll="SELECT EA.ic_evaluacion_analisis_id, EA.ic_analisis_muestra_id, EA.ic_muestra_id, 
                       EA.observacion, EA.activo, REQ.IC_REQUERIMIENTO_ID, ic_tipo_requerimiento_id, EA.ic_resultado_decision_id
                FROM G_INOCUIDAD.IC_EVALUACION_ANALISIS EA
                JOIN G_INOCUIDAD.IC_MUESTRA MU ON EA.IC_MUESTRA_ID = MU.IC_MUESTRA_ID
                JOIN G_INOCUIDAD.IC_REQUERIMIENTO REQ ON REQ.IC_REQUERIMIENTO_ID = MU.IC_REQUERIMIENTO_ID
                WHERE REQ.cancelado='N' AND EA.ACTIVO AND EA.IC_EVALUACION_ANALISIS_ID NOT IN (SELECT IC_EVALUACION_ANALISIS_ID FROM G_INOCUIDAD.IC_EVALUACION_COMITE WHERE ESTADO='Y')
                AND CASE WHEN g_inocuidad.buscar_rol('PFL_ADM_INOC','$usuario') THEN 1=1 ELSE req.inspector_id='$usuario' END ";
        $filas = array();
        try {
            $result = $conexion->ejecutarConsulta($queryAll);
            while ($filasProducto = pg_fetch_assoc($result)) {
                $evaluacion = new Evaluacion($filasProducto['ic_evaluacion_analisis_id'], $filasProducto['ic_analisis_muestra_id'],
                    $filasProducto['ic_muestra_id'], $filasProducto['observacion'], $filasProducto['activo'], $filasProducto['ic_resultado_decision_id']);
                $evaluacion->setIcRequerimientoId($filasProducto['ic_requerimiento_id']);
                $evaluacion->setIcTipoRequerimientoId($filasProducto['ic_tipo_requerimiento_id']);
                array_push($filas, $evaluacion);
            }
        }catch(Exception $exc){
            return array();
        }
        return $filas;
    }

    function getEvaluacionById($ic_evaluacion_analisis_id,$conexion){
        $queryAll="SELECT EA.ic_evaluacion_analisis_id, EA.ic_analisis_muestra_id, EA.ic_muestra_id, 
                       EA.observacion, EA.activo, REQ.IC_REQUERIMIENTO_ID, ic_tipo_requerimiento_id, EA.ic_resultado_decision_id
                FROM G_INOCUIDAD.IC_EVALUACION_ANALISIS EA
                JOIN G_INOCUIDAD.IC_MUESTRA MU ON EA.IC_MUESTRA_ID = MU.IC_MUESTRA_ID
                JOIN G_INOCUIDAD.IC_REQUERIMIENTO REQ ON REQ.IC_REQUERIMIENTO_ID = MU.IC_REQUERIMIENTO_ID
                WHERE EA.IC_EVALUACION_ANALISIS_ID = $ic_evaluacion_analisis_id";
        $evaluacion = null;
        try {
            $result = $conexion->ejecutarConsulta($queryAll);
            while ($filasProducto = pg_fetch_assoc($result)) {
                $evaluacion = new Evaluacion($filasProducto['ic_evaluacion_analisis_id'], $filasProducto['ic_analisis_muestra_id'],
                    $filasProducto['ic_muestra_id'], $filasProducto['observacion'], $filasProducto['activo'], $filasProducto['ic_resultado_decision_id']);
                $evaluacion->setIcRequerimientoId($filasProducto['ic_requerimiento_id']);
                $evaluacion->setIcTipoRequerimientoId($filasProducto['ic_tipo_requerimiento_id']);
            }
        }catch(Exception $exc){
            return array();
        }
        return $evaluacion;
    }

    function saveAndUpdateEvaluacion(Evaluacion $evaluacion,$conexion){

        $result=null;
        $querySave="";
        if(isset($evaluacion)) {
            $ic_evaluacion_analisis_id  = $evaluacion->getIcEvaluacionAnalisisId();;
            $observacion                = $evaluacion->getObservacion();
            $ic_resultado_decision_id   = $evaluacion->getIcResultadoDecisionId();

            $querySave="UPDATE g_inocuidad.ic_evaluacion_analisis
                   SET observacion='$observacion',ic_resultado_decision_id=$ic_resultado_decision_id
                 WHERE ic_evaluacion_analisis_id=$ic_evaluacion_analisis_id";

        }else
            throw new Exception("La Evaluación debe originarse en el Laboratorio [ic_analisis_muestra_id = null]");

        try{
            $result = $conexion->ejecutarConsulta($querySave);
        }catch (Exception $exc){
            $result = $exc->getMessage();
        }

        return $result;
    }

    function desactivarEvaluacion($ic_evaluacion_analisis_id,$conexion){

        $querySave="UPDATE g_inocuidad.ic_evaluacion_analisis
                   SET activo=FALSE
                 WHERE ic_evaluacion_analisis_id=$ic_evaluacion_analisis_id";
        try{
            $conexion->ejecutarConsulta($querySave);
        }catch (Exception $exc){
            $result = $exc;
        }
    }

    function activarEvaluacion($ic_evaluacion_analisis_id,$conexion){

        $querySave="UPDATE g_inocuidad.ic_evaluacion_analisis
                   SET activo=TRUE
                 WHERE ic_evaluacion_analisis_id=$ic_evaluacion_analisis_id";
        try{
            $conexion->ejecutarConsulta($querySave);
        }catch (Exception $exc){
            $result = $exc;
        }
    }

    function creaEvaluacionLaboratorio(Laboratorio $laboratorio,$conexion){
        $result=null;
        $querySave="";
        $ic_analisis_muestra_id     = $laboratorio->getIcAnalisisMuestraId();
        $ic_muestra_id              = $laboratorio->getIcMuestraId();
        if(isset($ic_analisis_muestra_id)) {
            $sequenceQuery ='SELECT nextval(\'g_inocuidad.ic_evaluacion_analisis_ic_evaluacion_analisis_id_seq\')';
            $evaluacionId=$this->obtenerSecuencial($conexion,$sequenceQuery);
            $querySave="INSERT INTO g_inocuidad.ic_evaluacion_analisis 
                    (ic_evaluacion_analisis_id,ic_analisis_muestra_id,ic_muestra_id, activo,ic_resultado_decision_id) 
             VALUES ($evaluacionId,$ic_analisis_muestra_id,$ic_muestra_id,TRUE,0)";
        }else
            throw new Exception("La Evaluación debe originarse en el Laboratorio [ic_analisis_muestra_id = null]");

        try{
            $result=$conexion->ejecutarConsulta($querySave);
            $result=$evaluacionId;
        }catch (Exception $exc){
            $result = $exc->getMessage();
        }

        return $result;
    }

    function getEvaluacionRO($ic_evaluacion_analisis_id,$conexion){
        $queryAll = "select * from G_INOCUIDAD.ic_evaluacion_analisis WHERE ic_evaluacion_analisis_id=$ic_evaluacion_analisis_id";
        try{
            $result = $conexion->ejecutarConsulta($queryAll);
            $filasPrd = pg_fetch_assoc($result);
            return $filasPrd;
        }catch (Exception $exc){
            return null;
        }
    }

    function getResultadoDatos($ic_analisis_muestra_id,$conexion){
        $queryAll = "select  (SELECT NOMBRE_COMUN FROM g_catalogos.productos WHERE id_producto = pi.ic_insumo_id) as insumo,
                        (SELECT NOMBRE FROM G_INOCUIDAD.IC_LMR WHERE ic_lmr_id = pi.ic_lmr_id) as lmr,
                        pi.um as unidad_medida,limite_minimo,limite_maximo,valor,observaciones
                    from g_inocuidad.ic_producto_insumo pi
                    join g_inocuidad.ic_registro_valor rv on pi.ic_producto_id = rv.ic_producto_id and pi.ic_insumo_id = rv.ic_insumo_id
                    where ic_analisis_muestra_id =$ic_analisis_muestra_id";
        try{
            $result = $conexion->ejecutarConsulta($queryAll);
            $filasPrd = pg_fetch_all($result);
            return $filasPrd;
        }catch (Exception $exc){
            return null;
        }
    }

    private function obtenerSecuencial($conexion,$querySequence){
        $res=$conexion->ejecutarConsulta($querySequence);
        $sec=pg_fetch_assoc($res);
        return $sec['nextval'];
    }
}