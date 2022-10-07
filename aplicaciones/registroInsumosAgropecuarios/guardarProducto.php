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

        $idSubtipo = htmlspecialchars ($_POST['subtipo'],ENT_NOQUOTES,'UTF-8');
        $nombreComun = htmlspecialchars ($_POST['nombre_comun'],ENT_NOQUOTES,'UTF-8');
        $partidaArancelaria = htmlspecialchars ($_POST['partida_arancelaria'],ENT_NOQUOTES,'UTF-8');
        $viaAdministracion = htmlspecialchars ($_POST['viaAdministracion'],ENT_NOQUOTES,'UTF-8');
        $idEmpresa = htmlspecialchars ($_POST['id_empresa'],ENT_NOQUOTES,'UTF-8');
        $idComposicion = htmlspecialchars ($_POST['id_composicion'],ENT_NOQUOTES,'UTF-8');
        $idFabricante = htmlspecialchars ($_POST['idFabricante'],ENT_NOQUOTES,'UTF-8');
        $idPais = htmlspecialchars ($_POST['idPaisProducto'],ENT_NOQUOTES,'UTF-8');

        $ingredientes = json_encode(pg_fetch_all($cr->listarComposicionIngredienteActivo($conexion,$idComposicion)));
        $productos = $cr->listarProductosPorNombre($conexion, $nombreComun);

        if (pg_num_rows($productos) > 0) {
            $mensaje['mensaje'] = "No se ha guardado el producto debido a que el nombre del producto ya existe";
        } else {

            $codigoProducto = 0;
            if ($partidaArancelaria != '' || $partidaArancelaria != 0) {
                $codigo = $cc->obtenerCodigoProducto($conexion, $partidaArancelaria);
                $codigoProducto = str_pad(pg_fetch_result($codigo, 0, 'codigo'), 4, '0', STR_PAD_LEFT);
            }

            try {

                $conexion->ejecutarConsulta("begin;");

                if (substr($idFabricante, 0, 6) == 'nuevo_') {
                    $nuevoIdFabricante = pg_fetch_row($cr->nuevoFabricante($conexion, substr($idFabricante, 6)));
                    $idFabricante = $nuevoIdFabricante[0];
                }

                $idProducto = pg_fetch_row($cr->guardarProducto(
                    $conexion,
                    $idSubtipo,
                    $nombreComun,
                    $partidaArancelaria,
                    $viaAdministracion,
                    $codigoProducto,
                    $idComposicion,
                    $idEmpresa,
                    $idPais,
                    $idFabricante
                ));

                $conexion->ejecutarConsulta("commit;");

                $mensaje['estado'] = 'exito';
                $mensaje['mensaje'] = "producto guardado";
                $mensaje['idComposicion'] = $idComposicion;
                $mensaje['idProducto'] = $idProducto[0];
                $mensaje['secuencia'] = $idProducto[1];
                $mensaje['ingredientes'] = $ingredientes;

                $usos = $cr->listarUsosPorComposicion($conexion, $idComposicion);
                $mensaje['usos'] = $usos['array_to_json'];

            } catch (Exception $ex) {
                $conexion->ejecutarConsulta("rollback;");
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