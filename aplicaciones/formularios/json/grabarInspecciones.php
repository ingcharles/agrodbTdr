<?php

require_once '../../../clases/Conexion.php';
//require_once '../../../clases/ControladorRegistroOperador.php';
//$idSubtipo = htmlspecialchars($_POST['id'], ENT_NOQUOTES, 'UTF-8');

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Error al ejecutar';
$mensaje['tipo'] = 'INSPECCION';

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
              t_inspeccion.inspeccion
              (id_inspeccion,
              identificador_inspector,
              fecha,
              identificador_asignante,
              nombre_formulario,
              resultado,
              identificador_tablet,
              version_bd,
              token
              )
            select
              $dato->id,
              '$dato->identificador_inspector',
              '$dato->fecha',
              '$dato->identificador_asignante',
              '$dato->nombre_formulario',
              '$dato->resultado',
              '$syncDTO->identificador_tablet',
              $syncDTO->version_bd,
              '($syncDTO->identificador_tablet.$syncDTO->token)';";
			//echo $var;
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