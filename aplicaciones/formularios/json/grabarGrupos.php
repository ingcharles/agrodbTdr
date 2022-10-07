<?php

require_once '../../../clases/Conexion.php';
//require_once '../../../clases/ControladorRegistroOperador.php';
//$idSubtipo = htmlspecialchars($_POST['id'], ENT_NOQUOTES, 'UTF-8');

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Error al ejecutar';
$mensaje['tipo'] = 'GRUPO';

try {
    $datos = json_decode($_POST['lista']);
    if (json_last_error() != JSON_ERROR_NONE) {
        $mensaje['mensaje'] = 'Error: ' . json_last_error_msg();
    } else {
        $syncDTO = json_decode($_POST['syncDTO']);
        $cantidadRegistros = json_decode($_POST['cantidad']);
        $conexion = new Conexion();
        $contador = 0;
        foreach ($datos as $dato) {
            $mensaje['cantidad'] = ++$contador;
            $var = "
            insert into
              t_inspeccion.grupo
              (id_grupo,
              id_inspeccion,
              id_operacion,
              identificador_tablet,
              version_bd,
              token,
			  serial
              )
            select
              $dato->id,
              $dato->id_inspeccion,
              $dato->id_operacion,
              '$syncDTO->identificador_tablet',
              $syncDTO->version_bd,
              '($syncDTO->identificador_tablet.$syncDTO->token)',
			  (SELECT max(serial) FROM t_inspeccion.inspeccion WHERE id_inspeccion=$dato->id_inspeccion and identificador_tablet='$syncDTO->identificador_tablet' and version_bd=$syncDTO->version_bd);";
            $conexion->ejecutarConsulta($var);
        }
        if ($contador == $cantidadRegistros) {
            $mensaje['estado'] = 'exito';
            $mensaje['mensaje'] = 'Registros almacenados';
        } else {
            $mensaje['mensaje'] = 'Registros incompletos';
        }
        $conexion->desconectar();
    }
} catch (Exception $ex) {
    $mensaje['mensaje'] = $ex->getMessage();
} finally {
    echo json_encode($mensaje);
}