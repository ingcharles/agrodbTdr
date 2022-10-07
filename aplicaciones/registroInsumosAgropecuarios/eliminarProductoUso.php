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

        $idProductoUso = htmlspecialchars($_POST['id'], ENT_NOQUOTES, 'UTF-8');


        try {

            $id = pg_fetch_row($cr->eliminarProductoUso($conexion, $idProductoUso));

            $codigo = $cr->obtenerCodigoProducto($conexion, $id[0]);

            $mensaje['estado'] = 'exito';
            $mensaje['mensaje'] = $idProductoUso;
            $mensaje['codigoProducto'] = $codigo;
            $mensaje['registro'] =  'RPU';
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