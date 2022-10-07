<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 23/02/18
 * Time: 8:26
 */

require_once '../Modelo/Comite.php';
class ServiceComiteDAO
{
    function getAllComiteInStep($conexion){
        $queryAll="SELECT ic_evaluacion_comite_id,EC.observacion,EC.ic_evaluacion_analisis_id,EC.estado, 
                    REQ.IC_REQUERIMIENTO_ID, ic_tipo_requerimiento_id,ic_analisis_muestra_id
                FROM G_INOCUIDAD.IC_EVALUACION_ANALISIS EA
                JOIN G_INOCUIDAD.IC_MUESTRA MU ON EA.IC_MUESTRA_ID = MU.IC_MUESTRA_ID
                JOIN G_INOCUIDAD.IC_REQUERIMIENTO REQ ON REQ.IC_REQUERIMIENTO_ID = MU.IC_REQUERIMIENTO_ID
                JOIN g_inocuidad.ic_evaluacion_comite EC ON EC.ic_evaluacion_analisis_id = EA.ic_evaluacion_analisis_id
                WHERE REQ.cancelado='N' AND EC.ESTADO='Y'";
        $filas = array();
        try {
            $result = $conexion->ejecutarConsulta($queryAll);
            while ($filasProducto = pg_fetch_assoc($result)) {
                $comite = new Comite($filasProducto['ic_evaluacion_comite_id'],$filasProducto['observacion'],
                $filasProducto['ic_evaluacion_analisis_id'],$filasProducto['estado']);
                $comite->setIcTipoRequerimientoId($filasProducto['ic_tipo_requerimiento_id']);
                $comite->setIcRequerimientoId($filasProducto['ic_requerimiento_id']);
                $comite->setIcAnalisisMuestraId($filasProducto['ic_analisis_muestra_id']);
                array_push($filas, $comite);
            }
        }catch(Exception $exc){
            return array();
        }
        return $filas;
    }

    function getComiteById($ic_evaluacion_comite_id,$conexion){
        $queryAll="SELECT ic_evaluacion_comite_id,EC.observacion,EC.ic_evaluacion_analisis_id,EC.estado, 
                    REQ.IC_REQUERIMIENTO_ID, ic_tipo_requerimiento_id,ic_analisis_muestra_id
                FROM G_INOCUIDAD.IC_EVALUACION_ANALISIS EA
                JOIN G_INOCUIDAD.IC_MUESTRA MU ON EA.IC_MUESTRA_ID = MU.IC_MUESTRA_ID
                JOIN G_INOCUIDAD.IC_REQUERIMIENTO REQ ON REQ.IC_REQUERIMIENTO_ID = MU.IC_REQUERIMIENTO_ID
                JOIN g_inocuidad.ic_evaluacion_comite EC ON EC.ic_evaluacion_analisis_id = EA.ic_evaluacion_analisis_id 
                WHERE EC.ESTADO='Y' AND ic_evaluacion_comite_id=$ic_evaluacion_comite_id";
        $comite = null;
        try {
            $result = $conexion->ejecutarConsulta($queryAll);
            while ($filasProducto = pg_fetch_assoc($result)) {
                $comite = new Comite($filasProducto['ic_evaluacion_comite_id'],$filasProducto['ic_evaluacion_analisis_id'],
                    $filasProducto['observacion'],$filasProducto['estado']);
                $comite->setIcTipoRequerimientoId($filasProducto['ic_tipo_requerimiento_id']);
                $comite->setIcRequerimientoId($filasProducto['ic_requerimiento_id']);
                $comite->setIcAnalisisMuestraId($filasProducto['ic_analisis_muestra_id']);
            }
        }catch(Exception $exc){
            return null;
        }
        return $comite;
    }

    function saveAndUpdateComite(Comite $comite,$conexion){
        $result=null;
        $querySave="";
        if(isset($comite)) {
            $ic_evaluacion_comite_id    = $comite->getIcEvaluacionComiteId();
            $ic_evaluacion_analisis_id  = $comite->getIcEvaluacionAnalisisId();
            $observacion                = $comite->getObservacion();
            $estado                     = $comite->getEstado();

            $querySave="UPDATE g_inocuidad.ic_evaluacion_comite
                       SET observacion='$observacion',ic_evaluacion_analisis_id=$ic_evaluacion_analisis_id, 
                           estado='$estado'
                     WHERE ic_evaluacion_comite_id=$ic_evaluacion_comite_id";

        }else
            throw new Exception("El registro de Comité debe originarse en la Evaluación");

        try{
            $conexion->ejecutarConsulta($querySave);
        }catch (Exception $exc){
            $result = $exc;
        }

        return $result;
    }

    function creaComiteEvaluacion(Evaluacion $evaluacion,$conexion){
        $result=null;
        $querySave="";
        $ic_evaluacion_analisis_id     = $evaluacion->getIcEvaluacionAnalisisId();
        if(isset($ic_evaluacion_analisis_id)) {
            $sequenceQuery ='SELECT nextval(\'g_inocuidad.ic_evaluacion_comite_ic_evaluacion_comite_id_seq\')';
            $evaluacionId=$this->obtenerSecuencial($conexion,$sequenceQuery);
            $querySave="INSERT INTO g_inocuidad.ic_evaluacion_comite(
                                ic_evaluacion_comite_id, observacion,ic_evaluacion_analisis_id)
                        VALUES ($evaluacionId, null, $ic_evaluacion_analisis_id);";
        }else
            throw new Exception("El registro de Comité debe originarse en la Evaluación");

        try{
            $result=$conexion->ejecutarConsulta($querySave);
            $result=$evaluacionId;
        }catch (Exception $exc){
            $result = $exc->getMessage();
        }

        return $result;
    }

    private function obtenerSecuencial($conexion,$querySequence){
        $res=$conexion->ejecutarConsulta($querySequence);
        $sec=pg_fetch_assoc($res);
        return $sec['nextval'];
    }
}