<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorInscripcionCaravana.php';

    $idInscripcion = htmlspecialchars($_POST['idInscripcion'], ENT_NOQUOTES, 'UTF-8');
    $nuevoEstado = htmlspecialchars($_POST['nuevoEstado'], ENT_NOQUOTES, 'UTF-8');
    $observacion = htmlspecialchars($_POST['observacion'], ENT_NOQUOTES, 'UTF-8');

    try {
        $conexion = new Conexion();
        $cic      = new ControladorInscripcionCaravana();

        $cic->actualizarInscripcion($conexion, $idInscripcion, $nuevoEstado, $observacion);
        echo 'Los archivos se han guardado con el registro ' . $idInscripcion;
    } catch (Exception $e) {
        echo 'Ocurrió un error al grabar el registro. ' . $e->getMessage();
    }

?>
