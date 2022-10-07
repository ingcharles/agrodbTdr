<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorRIA.php';

    $mensaje = array();
    $mensaje['estado'] = 'error';
    $mensaje['mensaje'] = 'Ha ocurrido un error!';

    try{
        $conexion = new Conexion();
        $cr = new ControladorRIA();

        $ids_unidos = htmlspecialchars($_POST['ids'], ENT_NOQUOTES, 'UTF-8');

        $ids = explode('_', $ids_unidos);
        $idProducto = $ids[0];
        $idAditivo = $ids[1];


        try {

            $cr->eliminarProductoAditivo($conexion, $idProducto, $idAditivo);

            $mensaje['estado'] = 'exito';
            $mensaje['mensaje'] = $ids_unidos;
            $mensaje['registro'] =  'RPA';
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