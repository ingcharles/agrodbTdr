<?php
    session_start();
    require_once '../../../clases/Conexion.php';
    require_once '../../../clases/ControladorCatalogos.php';

    $mensaje = array();
    $mensaje['estado'] = 'error';
    $mensaje['mensaje'] = 'Ha ocurrido un error!';

    try{
        try {
            $conexion = new Conexion();
            $cc = new ControladorCatalogos();

            $provincia = htmlspecialchars ($_POST['parametroBusqueda'],ENT_NOQUOTES,'UTF-8');
            $cantones = $cc->jsonListarCantonesPorProvincia($conexion, $provincia, 'CANTONES');

            $mensaje['estado'] = 'exito';
            $mensaje['mensaje'] = $cantones[array_to_json];

            $conexion->desconectar();
        } catch (Exception $ex){
            $mensaje['mensaje'] = "Error al ejecutar sentencia";
        } finally {
            pg_close($conexion);
        }
    } catch (Exception $ex) {
        $mensaje['mensaje'] = 'Error de conexión a la base de datos';
    } finally {
        echo json_encode($mensaje);
    }
?>