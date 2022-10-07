<?php
/**
 * Created by PhpStorm.
 * User: advance
 * Date: 2/1/18
 * Time: 9:36 PM
 */
require_once '../Modelo/Caso.php';
require_once '../Util.php';
require_once '../controladores/ControladorAuditoria.php';
class ServiceCasoDAO
{

    /**
     * ServiceCasoDAO constructor.
     */
    public function __construct()
    {

    }

    public function saveAndUpdateCaso(Caso $caso,$conexion,$numero_casos){
        $querySave = null;
        $queries = array();
        $result=null;
        $sequenceQuery ='SELECT nextval(\'g_inocuidad.ic_requerimiento_ic_requerimiento_id_seq\')';
        $util = new Util();
        if(isset($caso)){
            $programaId=$caso->getProgramaId()!=null&$caso->getProgramaId()!=''?$caso->getProgramaId():0;
            $casoId=$caso->getId();
            $fuente=$caso->getFuenteId()!=null?$caso->getFuenteId():0;
            $producto=$caso->getProductoId()!=null?$caso->getProductoId():0;
            $pais=$caso->getPaisId()!=null?$caso->getPaisId():0;
            $provincia=$caso->getProvinciaId()!=null?$caso->getProvinciaId():0;
            $inspector=$caso->getInspectorId()!=null?$caso->getInspectorId():null;
            $mercaderia=$caso->getOrigenMercaderiaId()!=null?$caso->getOrigenMercaderiaId():0;
            $tipoReque=$caso->getTipoRequerimientoId()!=null?$caso->getTipoRequerimientoId():0;
            $fecha=$caso->getFechaSolicitud();
            $nombreDenuncia=$caso->getNombreDenunciante();
            $datosDenuncia=$caso->getDatosDenunciante();
            $descripcionDenuncia=$caso->getDescripcionDenuncia();
            $observacion=$caso->getObservacion();
            $numeroMuestras=$caso->getNumeroMuestras()!=null?$caso->getNumeroMuestras():0;
            $fecha_inspeccion=$caso->getFechaInspeccion()!=null&&$caso->getFechaInspeccion()!=0?$caso->getFechaInspeccion():null;
            $fecha_notificacion=$caso->getFechaNotificacion()!=null&&$caso->getFechaNotificacion()!=0?$caso->getFechaNotificacion():null;
            $fecNotFormated=$util->formatDate($fecha_notificacion);
            $fecInspFormated=$util->formatDate($fecha_inspeccion);
            $fecha_denuncia=$caso->getFechaDenuncia()!=null&&$caso->getFechaDenuncia()!=0?$caso->getFechaDenuncia():null;
            $fecDenunciaFormated=$util->formatDate($fecha_denuncia);
            $usuario_id=$caso->getUsuarioId();

            if($caso->getId()!=null){
                $querySave="UPDATE g_inocuidad.ic_requerimiento ";
                $querySave.=" SET ic_fuente_denuncia_id=$fuente,";
                $querySave.=" pais_notificacion_id=$pais, provincia_id=$provincia, inspector_id='$inspector',";
                $querySave.=" origen_mercaderia_id=$mercaderia,";
                $querySave.=" nombre_denunciante='$nombreDenuncia', datos_denunciante='$datosDenuncia', descripcion_denuncia='$descripcionDenuncia', ";
                $querySave.=$fecDenunciaFormated==null||strlen($fecDenunciaFormated)==0?"fecha_denuncia=null":"fecha_denuncia='$fecDenunciaFormated'";
                $querySave.=" , ";
                $querySave.=" observacion='$observacion', numero_muestras=$numeroMuestras, ";
                $querySave.=$fecInspFormated==null||strlen($fecInspFormated)==0?"fecha_inspeccion=null":"fecha_inspeccion='$fecInspFormated'";
                $querySave.=",";
                $querySave.=$fecNotFormated==null||strlen($fecNotFormated)==0?"fecha_notificacion=null":"fecha_notificacion='$fecNotFormated'";
                $querySave.=" WHERE  ic_requerimiento_id=$casoId ";
                if($programaId!=null){
                    $querySave.=" AND programa_id=$programaId";

                }
                $queries[]=$querySave;

            }else{
                $sequenceGrupoQuery ='SELECT nextval(\'g_inocuidad.ic_requerimiento_id_grupo_seq\')';
                $grupoId=$this->obtenerSecuencial($conexion,$sequenceGrupoQuery);
                for($i=0;$i<$numero_casos;$i++){
                    $casoId=$this->obtenerSecuencial($conexion,$sequenceQuery);


                    $querySave="INSERT INTO g_inocuidad.ic_requerimiento ";
                    $querySave.="( ic_requerimiento_id, ic_fuente_denuncia_id, ic_producto_id,";
                    $querySave.=" pais_notificacion_id, provincia_id, inspector_id, origen_mercaderia_id,";
                    $querySave.=" ic_tipo_requerimiento_id, fecha_solicitud, nombre_denunciante,";
                    $querySave.=" datos_denunciante, descripcion_denuncia, observacion, numero_muestras,";
                    $querySave.=" fecha_inspeccion, fecha_notificacion, id_grupo, usuario_id, fecha_denuncia";
                    if($programaId!=null){
                        $querySave.=", programa_id";
                    }
                    $querySave.=")";
                    $querySave.="  VALUES($casoId,$fuente,$producto,$pais,$provincia,'$inspector',";
                    $querySave.="$mercaderia,$tipoReque,now(),'$nombreDenuncia','$datosDenuncia','$descripcionDenuncia','$observacion',$numeroMuestras,";
                    $querySave.=$fecInspFormated==null?"null":"'$fecInspFormated'";
                    $querySave.=",";
                    $querySave.=$fecNotFormated=null || strlen($fecNotFormated)==0?"null":"'$fecNotFormated'";
                    $querySave.=",$grupoId,'$usuario_id',";
                    $querySave.=$fecDenunciaFormated==null?"null":"'$fecDenunciaFormated'";
                    if($programaId!=null){
                        $querySave.=" ,$programaId";
                    }
                    $querySave.=")";
                    $queries[]=$querySave;
                }
            }
            try{
                foreach($queries as $query) {
                    $result = $conexion->ejecutarConsulta($query);
                }
                $result=$casoId;
            }catch (Exception $exc){
                $result = $exc->getMessage();
            }

            return $result;
        }

    }

    public function cancelarRegistro($ic_requerimiento_id, $mensaje, $usuario, $conexion){
        try{
            $querySave="UPDATE g_inocuidad.ic_requerimiento set cancelado='S', motivo_cancelacion='$mensaje' WHERE ic_requerimiento_id = $ic_requerimiento_id";
            $result=$conexion->ejecutarConsulta($querySave);
            //Auditoria
            $caso = $this->getCasoById($ic_requerimiento_id,$conexion);
            $auditoria = new ControladorAuditoria();
            $auditoria->auditarRegistroCancelar($usuario,$caso);
        }catch(Exception $exc){
            return $exc->getMessage();
        }
        return $result;
    }

    public function getAllCasosInStep($usuario, $conexion){
        $queryAll="SELECT r.programa_id, ic_requerimiento_id, ic_fuente_denuncia_id, r.ic_producto_id, 
            pais_notificacion_id, provincia_id, inspector_id, origen_mercaderia_id, 
            r.ic_tipo_requerimiento_id, fecha_solicitud, nombre_denunciante, 
            datos_denunciante, descripcion_denuncia, observacion, numero_muestras, '' as programa_n, 
            p.nombre as nombre_producto, tr.nombre as nombre_requerimiento
        FROM g_inocuidad.ic_requerimiento r, g_inocuidad.ic_producto p, g_inocuidad.ic_tipo_requerimiento tr
        WHERE r.cancelado='N' AND r.ic_producto_id = p.ic_producto_id 
        AND r.ic_tipo_requerimiento_id = tr.ic_tipo_requerimiento_id
        AND r.ic_requerimiento_id NOT IN (SELECT ic_requerimiento_id FROM g_inocuidad.ic_muestra)
        AND CASE WHEN g_inocuidad.buscar_rol('PFL_ADM_INOC','$usuario') THEN 1=1 ELSE r.usuario_id='$usuario' END
        ORDER BY r.ic_tipo_requerimiento_id, provincia_id, fecha_solicitud";

        $filas = array();
        try {
            $result = $conexion->ejecutarConsulta($queryAll);

            while ($filasPrd = pg_fetch_assoc($result)) {
                $caso = new Caso($filasPrd['ic_requerimiento_id'], $filasPrd['programa_id'], $filasPrd['ic_fuente_denuncia_id'], $filasPrd['ic_producto_id'],
                    $filasPrd['pais_notificacion_id'], $filasPrd['provincia_id'], $filasPrd['inspector_id'], $filasPrd['origen_mercaderia_id'],
                    $filasPrd['ic_tipo_requerimiento_id'], $filasPrd['fecha_solicitud'], $filasPrd['nombre_denunciante'], $filasPrd['datos_denunciante'],
                    $filasPrd['descripcion_denuncia'], $filasPrd['observacion'], $filasPrd['numero_muestras'], $filasPrd['programa_n'],$filasPrd['nombre_producto'],
                    $filasPrd['nombre_requerimiento']);
                array_push($filas, $caso);
            }
        }catch(Exception $exc){
            return array();
        }
        return $filas;
    }

    public function getAllCasos($conexion){
        $queryAll="SELECT r.programa_id, ic_requerimiento_id, ic_fuente_denuncia_id, r.ic_producto_id, ";
        $queryAll.=" pais_notificacion_id, provincia_id, inspector_id, origen_mercaderia_id, ";
        $queryAll.=" r.ic_tipo_requerimiento_id, fecha_solicitud, nombre_denunciante, ";
        $queryAll.=" datos_denunciante, descripcion_denuncia, observacion, numero_muestras, '' as programa_n, ";
        $queryAll.=" p.nombre as nombre_producto, tr.nombre as nombre_requerimiento";
        $queryAll.=" FROM g_inocuidad.ic_requerimiento r, g_inocuidad.ic_producto p, g_inocuidad.ic_tipo_requerimiento tr";
        $queryAll.=" WHERE r.ic_producto_id = p.ic_producto_id ";
        $queryAll.=" AND r.ic_tipo_requerimiento_id = tr.ic_tipo_requerimiento_id";

        $filas = array();
        try {
            $result = $conexion->ejecutarConsulta($queryAll);

            while ($filasPrd = pg_fetch_assoc($result)) {
                $caso = new Caso($filasPrd['ic_requerimiento_id'], $filasPrd['programa_id'], $filasPrd['ic_fuente_denuncia_id'], $filasPrd['ic_producto_id'],
                    $filasPrd['pais_notificacion_id'], $filasPrd['provincia_id'], $filasPrd['inspector_id'], $filasPrd['origen_mercaderia_id'],
                    $filasPrd['ic_tipo_requerimiento_id'], $filasPrd['fecha_solicitud'], $filasPrd['nombre_denunciante'], $filasPrd['datos_denunciante'],
                    $filasPrd['descripcion_denuncia'], $filasPrd['observacion'], $filasPrd['numero_muestras'], $filasPrd['programa_n'],$filasPrd['nombre_producto'],
                    $filasPrd['nombre_requerimiento']);
                array_push($filas, $caso);
            }
        }catch(Exception $exc){
            return array();
        }
        return $filas;
    }

    public function getCasoById($requerimientoId,$conexion){
        $caso = null;
        $queryAll="SELECT r.programa_id, ic_requerimiento_id, ic_fuente_denuncia_id, r.ic_producto_id, ";
        $queryAll.=" pais_notificacion_id, provincia_id, inspector_id, origen_mercaderia_id, ";
        $queryAll.=" r.ic_tipo_requerimiento_id, fecha_solicitud, nombre_denunciante, ";
        $queryAll.=" datos_denunciante, descripcion_denuncia, observacion, numero_muestras, '' as programa_n, ";
        $queryAll.=" p.nombre as nombre_producto, tr.nombre as nombre_requerimiento, ";
        $queryAll.=" r.fecha_inspeccion as fecha_inspeccion, fecha_notificacion as fecha_notificacion , fecha_denuncia as fecha_denuncia";
        $queryAll.=" FROM g_inocuidad.ic_requerimiento r, g_inocuidad.ic_producto p, g_inocuidad.ic_tipo_requerimiento tr";
        $queryAll.=" WHERE r.ic_producto_id = p.ic_producto_id ";
        $queryAll.=" AND r.ic_tipo_requerimiento_id = tr.ic_tipo_requerimiento_id";
        $queryAll.=" AND ic_requerimiento_id = $requerimientoId";
        try {
            $result = $conexion->ejecutarConsulta($queryAll);
            while ($filasPrd = pg_fetch_assoc($result)) {
            $caso = new Caso($filasPrd['ic_requerimiento_id'], $filasPrd['programa_id'], $filasPrd['ic_fuente_denuncia_id'], $filasPrd['ic_producto_id'],
                $filasPrd['pais_notificacion_id'], $filasPrd['provincia_id'], $filasPrd['inspector_id'], $filasPrd['origen_mercaderia_id'],
                $filasPrd['ic_tipo_requerimiento_id'], $filasPrd['fecha_solicitud'], $filasPrd['nombre_denunciante'], $filasPrd['datos_denunciante'],
                $filasPrd['descripcion_denuncia'], $filasPrd['observacion'], $filasPrd['numero_muestras'],
                $filasPrd['programa_n'],$filasPrd['nombre_producto'],
                $filasPrd['nombre_requerimiento']);
            $caso->setFechaInspeccion($filasPrd['fecha_inspeccion']);
            $caso->setFechaNotificacion($filasPrd['fecha_notificacion']);
                $caso->setFechaDenuncia($filasPrd['fecha_denuncia']);
            }
        }catch(Exception $exc){
            return new Caso();
        }
        return $caso;
    }

    public function getCasoRO($requerimientoId,$conexion){
        $queryAll = "select * from G_INOCUIDAD.IC_V_REQUERIMIENTO WHERE ic_requerimiento_id=$requerimientoId";
        try{
            $result = $conexion->ejecutarConsulta($queryAll);
            $filasPrd = pg_fetch_assoc($result);
            return $filasPrd;
        }catch (Exception $exc){
            return null;
        }
    }

    public function deleteCaso($requerimientoId,$conexion){
        $queryDelete="DELETE FROM g_inocuidad.ic_requerimiento WHERE ic_requerimiento_id=$requerimientoId";
        $result = $conexion->ejecutarConsulta($queryDelete);
        return $result;
    }

    public function formatDate($dateString){
        $date = new DateTime($dateString);
        return $date->format('Y/m/d H:i:s');
    }

    private function obtenerSecuencial($conexion,$querySequence){
        $res=$conexion->ejecutarConsulta($querySequence);
        $sec=pg_fetch_assoc($res);
        return $sec['nextval'];
    }

}