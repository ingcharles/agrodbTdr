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
        $idUso = htmlspecialchars($_POST['idUso'], ENT_NOQUOTES, 'UTF-8');
        $nombreUso = htmlspecialchars($_POST['nombreUso'], ENT_NOQUOTES, 'UTF-8');
        $dosis = htmlspecialchars($_POST['dosis'], ENT_NOQUOTES, 'UTF-8');
        $dosis2 = htmlspecialchars($_POST['dosis2'], ENT_NOQUOTES, 'UTF-8');
        $unidad_dosis = htmlspecialchars($_POST['unidad_dosis'], ENT_NOQUOTES, 'UTF-8');
        $unidad_dosis2 = htmlspecialchars($_POST['unidad_dosis2'], ENT_NOQUOTES, 'UTF-8');
        $aplicacion = htmlspecialchars($_POST['nombreAplicacion'], ENT_NOQUOTES, 'UTF-8');
        $idAplicacion = htmlspecialchars($_POST['idAplicacion'], ENT_NOQUOTES, 'UTF-8');
        $idEnfermedad = htmlspecialchars($_POST['idEnfermedad'], ENT_NOQUOTES, 'UTF-8');
        $nombreEnfermedad = htmlspecialchars($_POST['nombreEnfermedad'], ENT_NOQUOTES, 'UTF-8');
        $productoConsumo = htmlspecialchars($_POST['productoConsumo'], ENT_NOQUOTES, 'UTF-8');
        $periodo = htmlspecialchars($_POST['periodo'], ENT_NOQUOTES, 'UTF-8');
        $periodoUnidad = htmlspecialchars($_POST['periodoUnidad'], ENT_NOQUOTES, 'UTF-8');
        $area = htmlspecialchars($_POST['areaAplicacion'], ENT_NOQUOTES, 'UTF-8');

        $periodoCompleto = "$periodo $periodoUnidad";
        $dosisCompleta = "$dosis$unidad_dosis/$dosis2$unidad_dosis2";

        try {
            $cr = new ControladorRIA();

            $conexion->ejecutarConsulta("begin;");
            if (substr($idAplicacion, 0, 6) == 'nuevo_') {
                $nuevoIdAplicacion = pg_fetch_row($cr->nuevaAplicacion($conexion, substr($idAplicacion, 6)),$area);
                $idAplicacion = $nuevoIdAplicacion[0];
            }

            $id = pg_fetch_row($cr->nuevoProductoUso($conexion,
                                                    $idProducto,
                                                    $idUso,
                                                    $idEnfermedad,
                                                    $dosisCompleta,
                                                    $idAplicacion,
                                                    $productoConsumo,
                                                    $periodoCompleto));

            $codigo = $cr->obtenerCodigoProducto($conexion, $idProducto);

            $texto = '<span class="uso_como">' . $nombreUso . '</span><span class="uso_contra">' . $nombreEnfermedad . '</span><span class="uso_para">' . $aplicacion . '</span><span class="uso_dosis">' . $dosisCompleta . '</span><span class="uso_producto_consumo">' . $productoConsumo . '</span><span class="uso_periodo">' . $periodoCompleto . '</span>';
            $conexion->ejecutarConsulta("commit;");

            $mensaje['estado'] = 'exito';
            $mensaje['mensaje'] = $cr->imprimirLineaProductoUso($id[0], $texto);
            $mensaje['codigoProducto'] = $codigo;
        } catch (Exception $ex){
            $conexion->ejecutarConsulta("rollback;");
            $mensaje['mensaje'] = "Error al ejecutar sentencia";
            $mensaje['error'] = $conexion->mensajeError;
        } finally {
            $conexion->desconectar();
        }
    } catch (Exception $ex) {
        $mensaje['mensaje'] = 'Error de conexión a la base de datos';
    } finally {
        echo json_encode($mensaje);
    }
?>