<?php
/**
 * Created by PhpStorm.
 * User: acanaveral
 */

class ServiceAuditoriaDAO {


    public function auditar($usuario,$registro,$tipo,$conexion){
        $objRegistro = json_encode($registro);
        $clazzName = get_class($registro);

        $sequenceQuery = 'SELECT nextval(\'g_inocuidad.ic_registro_log_ic_registro_log_id_seq\')';
        $auditoriaId = $this->obtenerSecuencial($conexion, $sequenceQuery);

        $strSQL = "INSERT INTO g_inocuidad.ic_registro_log(
                            ic_registro_log_id, usuario_id, fecha, nombre_tabla,
                            tipo, objeto)
                    VALUES ($auditoriaId,'$usuario', current_timestamp, '$clazzName' ,'$tipo', '$objRegistro');";

        try{
            $conexion->ejecutarConsulta($strSQL);
        }catch (Exception $exc){
            error_log($exc);
        }
    }

    public function actualizarNotificaion($usuario,$signo,$conexion){
        $strSQL = "";

        if($signo=='+'){
            $strSQL = "UPDATE g_programas.aplicaciones_registradas 
                    SET cantidad_notificacion = cantidad_notificacion +1, mensaje_notificacion='Hay actividades por resolver' 
                    WHERE identificador = '$usuario'
                    AND id_aplicacion=(SELECT id_aplicacion FROM g_programas.aplicaciones WHERE codificacion_aplicacion='PRG_ADM_INOC')";
        }else{
            $strSQL = "UPDATE g_programas.aplicaciones_registradas 
                    SET cantidad_notificacion = cantidad_notificacion -1 
                    WHERE identificador = '$usuario'
                    AND id_aplicacion=(SELECT id_aplicacion FROM g_programas.aplicaciones WHERE codificacion_aplicacion='PRG_ADM_INOC')";
        }

        try{
            $conexion->ejecutarConsulta($strSQL);
        }catch (Exception $exc){
            error_log($exc);
        }
    }

    private function obtenerSecuencial($conexion,$querySequence){
        $res=$conexion->ejecutarConsulta($querySequence);
        $sec=pg_fetch_assoc($res);
        return $sec['nextval'];
    }
}