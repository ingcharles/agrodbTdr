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

        $idComposicion = htmlspecialchars($_POST['idComposicion'], ENT_NOQUOTES, 'UTF-8');
        $idIngredienteActivo = htmlspecialchars($_POST['idIngredienteActivo'], ENT_NOQUOTES, 'UTF-8');
        $concentracion = htmlspecialchars($_POST['concentracion'], ENT_NOQUOTES, 'UTF-8');
        $unidad = htmlspecialchars($_POST['unidad'], ENT_NOQUOTES, 'UTF-8');
        $restriccion = htmlspecialchars($_POST['restriccion'], ENT_NOQUOTES, 'UTF-8');

        $ingredienteActivo = pg_fetch_assoc($cr->abrirIngredienteActivo($conexion, $idIngredienteActivo));

        try {
            $cr = new ControladorRIA();

            $id = pg_fetch_row($cr->nuevoComposicionIngredienteActivo($conexion, $idComposicion, $idIngredienteActivo, $concentracion, $unidad, $restriccion));

            $nombreComposicion = $cr->modificarNombreComposicion($conexion, $idComposicion);

            $mensaje['estado'] = 'exito';
            $mensaje['nombreComposicion'] = $nombreComposicion;
            $mensaje['idComposicion'] = $idComposicion;
            $mensaje['mensaje'] = $cr->imprimirLineaComposicionIngredienteActivo($id[0] . '_' . $id[1], $ingredienteActivo['ingrediente_activo'] . ' (' . $concentracion . $unidad . ')');
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