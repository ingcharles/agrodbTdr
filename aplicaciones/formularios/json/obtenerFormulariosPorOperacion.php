<?php
    session_start();
    require_once '../../../clases/Conexion.php';
    require_once '../../../clases/ControladorFormularios.php';

    $mensaje = array();
    $mensaje['estado'] = 'error';
    $mensaje['mensaje'] = 'Ha ocurrido un error!';

    try{
        try {
            $conexion = new Conexion();
            $cf = new ControladorFormularios();

            $formularios = $cf->jsonListarFormulariosPorOperacion($conexion);

            $mensaje['estado'] = 'exito';
            $mensaje['mensaje'] = $formularios[array_to_json];

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