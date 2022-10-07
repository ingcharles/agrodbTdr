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

        $idProducto = htmlspecialchars($_POST['idProducto'], ENT_NOQUOTES, 'UTF-8');
        $idAditivo = htmlspecialchars($_POST['idAditivo'], ENT_NOQUOTES, 'UTF-8');
        $nombreAditivo = htmlspecialchars($_POST['nombreAditivo'], ENT_NOQUOTES, 'UTF-8');
        $concentracion = htmlspecialchars($_POST['concentracion'], ENT_NOQUOTES, 'UTF-8');
        $unidad = htmlspecialchars($_POST['unidad'], ENT_NOQUOTES, 'UTF-8');

        try {
            $cr = new ControladorRIA();

            $id = pg_fetch_row($cr->nuevoProductoAditivo($conexion, $idProducto, $idAditivo, $concentracion, $unidad));

            $texto = "$nombreAditivo ($concentracion $unidad)";

            $mensaje['estado'] = 'exito';
            $mensaje['mensaje'] = $cr->imprimirLineaProductoAditivo($id[0] . '_' . $id[1], $texto);
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