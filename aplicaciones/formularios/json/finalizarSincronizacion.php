<?php

require_once '../../../clases/Conexion.php';
//require_once '../../../clases/ControladorRegistroOperador.php';
//$idSubtipo = htmlspecialchars($_POST['id'], ENT_NOQUOTES, 'UTF-8');

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Error al ejecutar';
$mensaje['tipo'] = 'INSPECCION';

try {
    $syncDTO = json_decode($_POST['syncDTO']);
    $conexion = new Conexion();
    if (json_last_error() != JSON_ERROR_NONE) {
        $mensaje['mensaje'] = 'Error: ' . json_last_error_msg();
    } else {

       $var = "
            UPDATE t_inspeccion.inspeccion
            SET estado='COMPLETO'
            WHERE token='($syncDTO->identificador_tablet.$syncDTO->token)';
        ";

        $conexion->ejecutarConsulta($var);

        $mensaje['estado'] = 'exito';
        $mensaje['mensaje'] = 'Registros actualizados';

        $conexion->desconectar();
    }
} catch (Exception $ex) {
    $mensaje['mensaje'] = $ex->getMessage();
} finally {
    echo json_encode($mensaje);
}