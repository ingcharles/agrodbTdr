<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 03/04/18
 * Time: 22:28
 */

class ServiceReportePublicoDAO
{
    public function __construct()
    {
    }

    public function obtenerDatosPrincipales($conexion){
        $strSQL = "
            SELECT 
            (
            select count(1) as totalNotificaciones 
            FROM g_inocuidad.ic_requerimiento req
            JOIN g_inocuidad.ic_muestra mu ON req.ic_requerimiento_id = mu.ic_requerimiento_id
            WHERE req.cancelado='N'
            AND ic_resultado_decision_id IN (2,3,4,5)
            AND extract('year' from req.FECHA_NOTIFICACION) = extract('year' from CURRENT_TIMESTAMP)
            ) as totalNotificaciones,
            (
            SELECT COUNT(1) as TotalProvincias
            FROM (
                select count(1)
                FROM g_inocuidad.ic_requerimiento req
                JOIN g_inocuidad.ic_muestra mu ON req.ic_requerimiento_id = mu.ic_requerimiento_id
                WHERE req.cancelado='N' and mu.provincia_id>0
                AND ic_resultado_decision_id IN (2,3,4,5)
                AND extract('year' from req.FECHA_NOTIFICACION) = extract('year' from CURRENT_TIMESTAMP)
                GROUP BY mu.provincia_id
            ) T
            ) as TotalProvincias,
            (
            SELECT nombre_comun as ingrediente_activo
            FROM(
                select count(ic_insumo_id) as cuenta_insumo,ic_insumo_id--count(1) as totalNotificaciones 
                FROM g_inocuidad.ic_requerimiento req
                JOIN g_inocuidad.ic_muestra mu ON req.ic_requerimiento_id = mu.ic_requerimiento_id
                JOIN g_inocuidad.ic_analisis_muestra lab ON mu.ic_muestra_id=lab.ic_muestra_id
                JOIN g_inocuidad.ic_registro_valor regLab ON lab.ic_analisis_muestra_id = regLab.ic_analisis_muestra_id
                WHERE req.cancelado='N'
                AND ic_resultado_decision_id IN (2,3,4,5)
                AND extract('year' from req.FECHA_NOTIFICACION) = extract('year' from CURRENT_TIMESTAMP)
                GROUP BY ic_insumo_id
                ORDER BY 1 DESC LIMIT 1
            ) T
            JOIN g_catalogos.productos inac ON inac.id_producto= T.ic_insumo_id
            ) as ContaminanteFrecuente
        ";
        try{
            $result = $conexion->ejecutarConsulta($strSQL);
            $filasPrd = pg_fetch_all($result);
            return $filasPrd;
        }catch (Exception $exc){
            return null;
        }
    }

    public function notificacionesPorProvincia($conexion){
        $strSQL = "
        select sum(presente) as actual,sum(anterior) as anterior, nombre
            FROM
            (
                select count(1) as presente,0 as anterior, loc.nombre
                FROM g_inocuidad.ic_requerimiento req
                JOIN g_inocuidad.ic_muestra mu ON req.ic_requerimiento_id = mu.ic_requerimiento_id
                JOIN g_catalogos.localizacion loc ON loc.id_localizacion = mu.provincia_id
                WHERE req.cancelado='N'
                AND ic_resultado_decision_id IN (2,3,4,5)
                AND extract('year' from req.FECHA_NOTIFICACION) = extract('year' from CURRENT_TIMESTAMP)
                GROUP BY loc.nombre
                UNION
                select 0 as presente,count(1) as anterior, loc.nombre
                FROM g_inocuidad.ic_requerimiento req
                JOIN g_inocuidad.ic_muestra mu ON req.ic_requerimiento_id = mu.ic_requerimiento_id
                JOIN g_catalogos.localizacion loc ON loc.id_localizacion = mu.provincia_id
                WHERE req.cancelado='N'
                AND ic_resultado_decision_id IN (2,3,4,5)
                AND extract('year' from req.FECHA_NOTIFICACION) = (extract('year' from CURRENT_TIMESTAMP)-1)
                GROUP BY loc.nombre
            ) T 
            GROUP BY nombre
            ORDER BY 3
        ";

        try{
            $result = $conexion->ejecutarConsulta($strSQL);
            $filasPrd = pg_fetch_all($result);
            return $filasPrd;
        }catch (Exception $exc){
            return null;
        }
    }

    public function notificacionesPorProducto($conexion){
        $strSQL = "
        select sum(presente) as actual,sum(anterior) as anterior, nombre
            FROM
            (
                select count(1) as presente,0 as anterior, pro.nombre
                FROM g_inocuidad.ic_requerimiento req
                JOIN g_inocuidad.ic_muestra mu ON req.ic_requerimiento_id = mu.ic_requerimiento_id
                JOIN g_inocuidad.ic_producto pro ON pro.ic_producto_id = req.ic_producto_id
                WHERE req.cancelado='N'
                AND ic_resultado_decision_id IN (2,3,4,5)
                AND extract('year' from req.FECHA_NOTIFICACION) = extract('year' from CURRENT_TIMESTAMP)
                GROUP BY pro.nombre
                UNION
                select 0 as presente,count(1) as anterior, pro.nombre
                FROM g_inocuidad.ic_requerimiento req
                JOIN g_inocuidad.ic_muestra mu ON req.ic_requerimiento_id = mu.ic_requerimiento_id
                JOIN g_inocuidad.ic_producto pro ON pro.ic_producto_id = req.ic_producto_id
                WHERE req.cancelado='N'
                AND ic_resultado_decision_id IN (2,3,4,5)
                AND extract('year' from req.FECHA_NOTIFICACION) = (extract('year' from CURRENT_TIMESTAMP)-1)
                GROUP BY pro.nombre
            ) T 
            GROUP BY nombre
            ORDER BY 3
        ";
        try{
            $result = $conexion->ejecutarConsulta($strSQL);
            $filasPrd = pg_fetch_all($result);
            return $filasPrd;
        }catch (Exception $exc){
            return null;
        }
    }

    public function notificacionesPorOrigen($conexion){
        $strSQL = "
        select sum(presente) as actual,sum(anterior) as anterior, nombre
            FROM
            (
                select count(1) as presente,0 as anterior, tip.nombre
                FROM g_inocuidad.ic_requerimiento req
                JOIN g_inocuidad.ic_muestra mu ON req.ic_requerimiento_id = mu.ic_requerimiento_id
                JOIN g_inocuidad.ic_tipo_requerimiento tip ON tip.ic_tipo_requerimiento_id = req.ic_tipo_requerimiento_id
                WHERE req.cancelado='N'
                AND ic_resultado_decision_id IN (2,3,4,5)
                AND extract('year' from req.FECHA_NOTIFICACION) = extract('year' from CURRENT_TIMESTAMP)
                GROUP BY tip.nombre
                UNION
                select 0 as presente,count(1) as anterior, tip.nombre
                FROM g_inocuidad.ic_requerimiento req
                JOIN g_inocuidad.ic_muestra mu ON req.ic_requerimiento_id = mu.ic_requerimiento_id
                JOIN g_inocuidad.ic_tipo_requerimiento tip ON tip.ic_tipo_requerimiento_id = req.ic_tipo_requerimiento_id
                WHERE req.cancelado='N'
                AND ic_resultado_decision_id IN (2,3,4,5)
                AND extract('year' from req.FECHA_NOTIFICACION) = (extract('year' from CURRENT_TIMESTAMP)-1)
                GROUP BY tip.nombre
            ) T 
            GROUP BY nombre
            ORDER BY 3
        ";
        try{
            $result = $conexion->ejecutarConsulta($strSQL);
            $filasPrd = pg_fetch_all($result);
            return $filasPrd;
        }catch (Exception $exc){
            return null;
        }
    }

    public function notificacionesPorPrograma($conexion){
        $strSQL = "
        select sum(presente) as actual,sum(anterior) as anterior, replace(coalesce(nombre,'Notificación Exterior'),'Programa Nacional de Vigilancia y Control de ','') as nombre
            FROM
            (
                select count(1) as presente,0 as anterior, cat.nombre
                FROM g_inocuidad.ic_requerimiento req
                JOIN g_inocuidad.ic_muestra mu ON req.ic_requerimiento_id = mu.ic_requerimiento_id
                LEFT JOIN g_inocuidad.ic_catalogo cat ON cat.ic_catalogo_id = req.programa_id
                WHERE req.cancelado='N'
                AND ic_resultado_decision_id IN (2,3,4,5)
                AND extract('year' from req.FECHA_NOTIFICACION) = extract('year' from CURRENT_TIMESTAMP)
                GROUP BY cat.nombre
                UNION
                select 0 as presente,count(1) as anterior, cat.nombre
                FROM g_inocuidad.ic_requerimiento req
                JOIN g_inocuidad.ic_muestra mu ON req.ic_requerimiento_id = mu.ic_requerimiento_id
                LEFT JOIN g_inocuidad.ic_catalogo cat ON cat.ic_catalogo_id = req.programa_id
                WHERE req.cancelado='N'
                AND ic_resultado_decision_id IN (2,3,4,5)
                AND extract('year' from req.FECHA_NOTIFICACION) = (extract('year' from CURRENT_TIMESTAMP)-1)
                GROUP BY cat.nombre
            ) T 
            GROUP BY nombre
            ORDER BY 3
        ";
        try{
            $result = $conexion->ejecutarConsulta($strSQL);
            $filasPrd = pg_fetch_all($result);
            return $filasPrd;
        }catch (Exception $exc){
            return null;
        }
    }
}