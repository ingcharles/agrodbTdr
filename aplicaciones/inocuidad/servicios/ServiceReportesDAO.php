<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 05/04/18
 * Time: 22:25
 */

class ServiceReportesDAO
{
    function cuentaDetallado(Conexion $conexion, $sqlWHERE){
        $strSQL = "
            SELECT COUNT(1)
            FROM G_INOCUIDAD.IC_V_REQUERIMIENTO REQ
            LEFT JOIN G_INOCUIDAD.IC_V_MUESTRA MU ON REQ.IC_REQUERIMIENTO_ID = MU.IC_REQUERIMIENTO_ID
            LEFT JOIN G_INOCUIDAD.IC_MUESTRA_RAPIDA RV ON RV.IC_MUESTRA_ID = MU.IC_MUESTRA_ID
            LEFT JOIN G_INOCUIDAD.IC_INSUMO INS ON INS.IC_INSUMO_ID = RV.IC_INSUMO_ID
            LEFT JOIN G_INOCUIDAD.IC_PRODUCTO_MUESTRA_RAPIDA PI ON PI.IC_PRODUCTO_ID = RV.IC_PRODUCTO_ID AND PI.IC_INSUMO_ID = RV.IC_INSUMO_ID
            LEFT JOIN g_catalogos.unidades_medidas umed on umed.id_unidad_medida::varchar = PI.UM
            LEFT JOIN G_INOCUIDAD.IC_V_ANALISIS_MUESTRA AM ON AM.IC_MUESTRA_ID = MU.IC_MUESTRA_ID
            LEFT JOIN G_INOCUIDAD.IC_EVALUACION_ANALISIS EV ON EV.IC_ANALISIS_MUESTRA_ID = AM.IC_ANALISIS_MUESTRA_ID
            LEFT JOIN G_INOCUIDAD.IC_RESULTADO_DESICION RD ON EV.IC_RESULTADO_DECISION_ID=RD.IC_RESULTADO_DECISION_ID
            LEFT JOIN G_INOCUIDAD.IC_EVALUACION_COMITE CM ON CM.IC_EVALUACION_ANALISIS_ID = EV.IC_EVALUACION_ANALISIS_ID
            ";
        $strSQL = $strSQL.$sqlWHERE;
        return $conexion->ejecutarConsulta($strSQL);
    }

    function reporteDetallado(Conexion $conexion,$sqlWHERE){
        $strSQL = "SELECT 
                REQ.programa as \"Programa\",
                REQ.fuente_denuncia as \"Fuente de Denuncia\",
                REQ.pais_notificacion as \"País Notificación\",
                REQ.provincia as \"Provincia\",
                REQ.origen_mercaderia as \"Origen Mercadería\",
                REQ.inspector as \"Inspector\",
                REQ.producto as \"Producto\",
                REQ.tipo_requerimiento as \"Tipo Requerimiento\",
                REQ.programa_id as \"ID Programa\",
                REQ.ic_requerimiento_id as \"ID Requerimiento\",
                REQ.ic_fuente_denuncia_id as \"ID Funete Denuncia\",
                REQ.ic_producto_id as \"ID Producto\",
                REQ.pais_notificacion_id as \"ID País Notificación\",
                REQ.provincia_id as \"ID Provincia\",
                REQ.inspector_id as \"ID Inspector\",
                REQ.origen_mercaderia_id as \"ID Origen Mercadería\",
                REQ.ic_tipo_requerimiento_id as \"ID Tipo Requerimiento\",
                REQ.fecha_solicitud as \"Fecha de Solicitud\",
                REQ.nombre_denunciante as \"Nombre Denunciante\",
                REQ.datos_denunciante as \"Datos Denunciante\",
                REQ.descripcion_denuncia as \"Descripción Denuncia\",
                REQ.observacion as \"Observación Caso\",
                REQ.numero_muestras as \"Número de Muestras\",
                REQ.fecha_inspeccion as \"Fecha de Inspección\",
                REQ.fecha_denuncia as \"Fecha de Denuncia\",
                REQ.fuente_denuncia_id as \"ID Fuente Denuncia\",
                REQ.fecha_notificacion as \"Fecha Notificación\",
                REQ.cancelado as \"Cancelado\",
                REQ.motivo_cancelacion as \"Motivo Cancelación\",
                MU.provincia as \"Provincia Muestra\",
                MU.canton as \"Cantón Muestra\",
                MU.parroquia as \"Parroquia Muestra\",
                MU.origen_muestra as \"Origen de la Muestra\",
                MU.empresa as \"Empresa\",
                MU.finca as \"Finca\",
                MU.pais_procedencia as \"País Procedencia\",
                MU.tecnico_responsable as \"Técnico Responsable\",
                MU.tipo_muestra as \"Tipo Muestra\",
                MU.fecha_muestreo as \"Fecha de Muestreo\",
                MU.codigo_muestras as \"Código de Muestras\",
                MU.canton_id as \"ID Cantón\",
                MU.parroquia_id as \"ID Parroquia\",
                MU.tipo_empresa as \"Tipo de Empresa\",
                MU.finca_id as \"ID Finca\",
                MU.utm_x as \"UTM X\",
                MU.utm_y as \"UTM Y\",
                MU.registro_importador as \"Registro Importador\",
                MU.permiso_fitosanitario as \"Permiso Fitosanitario\",
                MU.tecnico_id as \"ID Técnico\",
                MU.ic_resultado_decision_id as \"ID Resultado Decisión\",
                MU.activo as \"Activo Muestra\",
                MU.estado as \"Estado Muestra\",
                MU.provincia_id as \"ID Provincia Muestra\",
                MU.origen_muestra_id as \"ID Origen Muestra\",
                MU.nombre_rep_legal as \"Nombre Rep. Legal\",
                MU.pais_procedencia_id as \"ID País Procedencia\",
                MU.tipo_muestra_id as \"ID Tipo Muestra\",
                MU.ic_requerimiento_id as \"ID Requerimiento Muestra\",
                MU.ic_muestra_id as \"ID Muestra\",
                INS.nombre as \"M. Rápida Insumo\",
                RV.VALOR as \"M. Rápida Valor\",
                RV.OBSERVACIONES as \"M. Rápida Observaciones\",
                umed.NOMBRE as \"M. Rápida Unidad de Medida\",
                umed.CODIGO as \"M. Rápida UM Código\",
                PI.LIMITE_MINIMO as \"M. Rápida Límite Mínimo\",
                PI.LIMITE_MAXIMO as \"M. Rápida Límite Máximo\",
                AM.OBSERVACIONES as \"Observaciones Laboratorio\",
                AM.ACTIVO as \"Laboratorio Activo\",
                AM.INSUMO as \"Laboratorio Insumo\",
                AM.LMR as \"Laboratorio LMR\",
                AM.LIMITE_MINIMO as \"Laboratorio Límite Mínimo\",
                AM.LIMITE_MAXIMO as \"Laboratorio Límite Máximo\",
                AM.UNIDAD_MEDIDA as \"Laboratorio Unidad de Medida\",
                AM.VALOR as \"Laboratorio Valor\",
                AM.OBS as \"Laboratorio Observaciones\",
                EV.OBSERVACION as \"Observaciones Evaluacion\",
                EV.ACTIVO as \"Activo Evaluación\",
                RD.NOMBRE as \"Evaluación Resultado Decisión\",
                RD.TIPO_DESICION as \"Evaluación Tipo Desición\",
                CM.OBSERVACION as \"Observaciones Comité\"
            FROM G_INOCUIDAD.IC_V_REQUERIMIENTO REQ
            LEFT JOIN G_INOCUIDAD.IC_V_MUESTRA MU ON REQ.IC_REQUERIMIENTO_ID = MU.IC_REQUERIMIENTO_ID
            LEFT JOIN G_INOCUIDAD.IC_MUESTRA_RAPIDA RV ON RV.IC_MUESTRA_ID = MU.IC_MUESTRA_ID
            LEFT JOIN G_INOCUIDAD.IC_INSUMO INS ON INS.IC_INSUMO_ID = RV.IC_INSUMO_ID
            LEFT JOIN G_INOCUIDAD.IC_PRODUCTO_MUESTRA_RAPIDA PI ON PI.IC_PRODUCTO_ID = RV.IC_PRODUCTO_ID AND PI.IC_INSUMO_ID = RV.IC_INSUMO_ID
            LEFT JOIN g_catalogos.unidades_medidas umed on umed.id_unidad_medida::varchar = PI.UM
            LEFT JOIN G_INOCUIDAD.IC_V_ANALISIS_MUESTRA AM ON AM.IC_MUESTRA_ID = MU.IC_MUESTRA_ID
            LEFT JOIN G_INOCUIDAD.IC_EVALUACION_ANALISIS EV ON EV.IC_ANALISIS_MUESTRA_ID = AM.IC_ANALISIS_MUESTRA_ID
            LEFT JOIN G_INOCUIDAD.IC_RESULTADO_DESICION RD ON EV.IC_RESULTADO_DECISION_ID=RD.IC_RESULTADO_DECISION_ID
            LEFT JOIN G_INOCUIDAD.IC_EVALUACION_COMITE CM ON CM.IC_EVALUACION_ANALISIS_ID = EV.IC_EVALUACION_ANALISIS_ID  
            ";
        $strSQL = $strSQL.$sqlWHERE;
        return $conexion->ejecutarConsulta($strSQL);
    }

    public function formatDate($dateString){
        $date = new DateTime($dateString);
        return $date->format('Y-m-d H:i:s');
    }
}