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
        $idComposicion = $ids[0];
        $idIngredienteActivo = $ids[1];


        try {

            $cr->eliminarComposicionIngredienteActivo($conexion, $idComposicion, $idIngredienteActivo);

            $nombreComposicion = $cr->modificarNombreComposicion($conexion, $idComposicion);

            $mensaje['estado'] = 'exito';
            $mensaje['nombreComposicion'] = $nombreComposicion;
            $mensaje['idComposicion'] = $idComposicion;
            $mensaje['mensaje'] = $ids_unidos;
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