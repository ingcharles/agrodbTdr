<?php

    require_once '../../../clases/Conexion.php';
    require_once '../../../clases/ControladorFormularios';

    $mensaje = array();
    $mensaje['estado'] = 'error';
    $mensaje['mensaje'] = 'Ha ocurrido  un error!';

    try {

        $respuestaJSON = json_decode($_POST['json'], true);

        $idFormulario = $respuestaJSON['id_formulario'];
        $idOperacion = $respuestaJSON['id_operacion'];
        $idUsuario = $respuestaJSON['id_usuario'];
        $preguntas = $respuestaJSON['mensaje'];
        //$preguntas = print_r($respuestaJSON['mensaje'],true);
        $resultadoInspeccion = $respuestaJSON['resultado']; //TODO: Por definir, es el resultado final de la inspección

        $conexion = new Conexion();
        $cf = new ControladorFormularios();

        $conexion->ejecutarConsulta('BEGIN;');

        try {

            $idSolicitud = $cf->guardarInspeccion($idFormulario, $idOperacion, $idUsuario, $resultadoInspeccion);

            foreach ($preguntas as $pregunta) {
                if ($pregunta['respuesta']) { //TODO: revisar si esto sirve
                    $cf->guardarRespuestaInformativa($idSolicitud, $pregunta['id_pregunta'], $pregunta['respuesta']);
                } else {
                    foreach ($pregunta['opciones'] as $opcion) {
                        $cf->guardarRespuestaMultiple($idSolicitud, $pregunta['id_pregunta'], $opcion['id_opcion']);
                    }
                }
            }

            $conexion->ejecutarConsulta('COMMIT;');
            pg_close($conexion);
            $mensaje['estado'] = 'exito';
            $mensaje['mensaje'] = 'Los datos se han guardado exitosamente';
        } catch (Exception $ex) {
            $conexion->ejecutarConsulta('ROLLBACK;');
            pg_close($conexion);
            $mensaje['mensaje'] = 'Error al guardar los datos';
        }
    } catch (Exception $ex) {
        $mensaje['mensaje'] = 'Error al conectar con la base de datos';
    }
    echo json_encode($mensaje);
?>



