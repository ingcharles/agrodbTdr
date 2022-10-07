<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorRIA.php';

    $mensaje = array();
    $mensaje['estado'] = 'error';
    $mensaje['mensaje'] = 'Ha ocurrido un error!';

    try{
        $conexion = new Conexion();

        $idTipo = htmlspecialchars($_POST['idTipo'], ENT_NOQUOTES, 'UTF-8');
        $nombre = htmlspecialchars($_POST['nombreTipo'], ENT_NOQUOTES, 'UTF-8');
        $estado = htmlspecialchars($_POST['estadoTipo'], ENT_NOQUOTES, 'UTF-8');

        try {
            $cr = new ControladorRIA();

            $cr->modificarTipo($conexion, $idTipo, $nombre, $estado);

            $mensaje['estado'] = 'exito';
            $mensaje['mensaje'] = 'Los datos fueron actualizados';

        } catch (Exception $ex){
            $mensaje['mensaje'] = "Error al ejecutar sentencia";
        } finally {
            $conexion->desconectar();
        }
    } catch (Exception $ex) {
        $mensaje['mensaje'] = 'Error de conexión a la base de datos';
    } finally {
        echo json_encode($mensaje);
    }
?>