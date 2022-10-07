<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorRIA.php';
    require_once '../../clases/ControladorCatalogos.php';

    $mensaje = array();
    $mensaje['estado'] = 'error';
    $mensaje['mensaje'] = 'Ha ocurrido un error!';

    try{
        $conexion = new Conexion();
        $cr = new ControladorRIA();
        $cc = new ControladorCatalogos();

        $idProducto = htmlspecialchars ($_POST['idProducto'],ENT_NOQUOTES,'UTF-8');
        $idSubtipo = htmlspecialchars ($_POST['idSubtipo'],ENT_NOQUOTES,'UTF-8');
        $partidaArancelaria = htmlspecialchars ($_POST['partidaArancelaria'],ENT_NOQUOTES,'UTF-8');

        $producto = pg_fetch_assoc($cr->abrirProducto($conexion, $idProducto));
        $productos = $cr->listarProductosPorNombre($conexion, $producto['nombre_comun']);

        if (pg_num_rows($productos) > 1) {
            $mensaje['mensaje'] = "No se ha guardado el producto debido a que no puede existir más de un duplicado";
        } else {

            $codigoArancelProducto = 0;
            if ($partidaArancelaria != '' || $partidaArancelaria != 0) {
                $codigoArancel = $cc->obtenerCodigoProducto($conexion, $partidaArancelaria);
                $codigoArancelProducto = str_pad(pg_fetch_result($codigoArancel, 0, 'codigo'), 4, '0', STR_PAD_LEFT);
            }

            try {

                $idProducto = pg_fetch_row($cr->duplicarProducto(
                    $conexion,
                    $idProducto,
                    $codigoArancelProducto,
                    $idSubtipo,
                    $partidaArancelaria
                ));

                $mensaje['estado'] = 'exito';
                $mensaje['mensaje'] = "Producto duplicado exitosamente";

            } catch (Exception $ex) {
                $mensaje['mensaje'] = $ex->getMessage();
                $mensaje['error'] = $conexion->mensajeError;
            } finally {
                $conexion->desconectar();
            }
        }
    } catch (Exception $ex) {
        $mensaje['mensaje'] = $ex->getMessage();
        $mensaje['error'] = $conexion->mensajeError;
    } finally {
        echo json_encode($mensaje);
    }
?>