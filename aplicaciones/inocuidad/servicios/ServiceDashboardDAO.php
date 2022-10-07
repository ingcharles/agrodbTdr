<?php
/**
 * Created by IntelliJ IDEA.
 * User: antonio
 * Date: 19/02/18
 * Time: 11:14
 */

require_once "../Modelo/Dashboard.php";

class ServiceDashboardDAO
{
    public function __construct()
    {
    }

    public function recibidosVsDespachados($conexion){
        $queryAll = "select (select count(1)
                        from g_inocuidad.ic_requerimiento) as recibido,
                        (select count(1) from (
                        select ic_requerimiento_id
                        from g_inocuidad.ic_muestra 
                        group by ic_requerimiento_id) T) as atendido,
                        (select count(1) from (
                        select ic_requerimiento_id
                        from g_inocuidad.ic_muestra 
                        where ic_resultado_decision_id>0
                        group by ic_requerimiento_id) T) as despachado";
        try{
            $result = $conexion->ejecutarConsulta($queryAll);
            $filasPrd = pg_fetch_all($result);
            return $filasPrd;
        }catch (Exception $exc){
            return null;
        }
    }

    public function getAllDashboard($usuario,$conexion){
        $queryAll=" SELECT *
                    FROM G_INOCUIDAD.IC_V_DASHBOARD 
                    WHERE CASE WHEN g_inocuidad.buscar_rol('PFL_ADM_INOC','$usuario') 
                              THEN 1=1  
			              WHEN g_inocuidad.buscar_rol('PFL_INP_INOC','$usuario') 
			                  THEN inspector_id = '$usuario' 
                          ELSE usuario='$usuario' END";

        $filas = array();
        try{
            $result = $conexion->ejecutarConsulta($queryAll);
            while ($filasDashboard = pg_fetch_assoc($result)) {
                $dashboard = new Dashboard($filasDashboard['nombre_programa'],$filasDashboard['nombre_tipo_requerimiento'],
                    $filasDashboard['fecha_solicitud'],$filasDashboard['estado'],$filasDashboard['usuario'],$filasDashboard['ic_requerimiento_id'],
                    $filasDashboard['ic_muestra_id'],$filasDashboard['ic_analisis_muestra_id'],$filasDashboard['ic_evaluacion_analisis_id'],
                    $filasDashboard['ic_evaluacion_comite_id'],$filasDashboard['cancelado'],$filasDashboard['motivo_cancelacion'],$filasDashboard['provincia']);
                array_push($filas, $dashboard);
            }
        }catch(Exception $exc){
            return array();
        }
        return $filas;
    }
}