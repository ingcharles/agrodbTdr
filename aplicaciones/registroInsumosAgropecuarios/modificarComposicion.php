<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorRIA.php';

    $mensaje = array();
    $mensaje['estado'] = 'error';
    $mensaje['mensaje'] = 'Ha ocurrido un error!';

    try{
        $conexion = new Conexion();

        $idComposicion = htmlspecialchars($_POST['idComposicionCab'], ENT_NOQUOTES, 'UTF-8');
        $idArea = htmlspecialchars($_POST['area'], ENT_NOQUOTES, 'UTF-8');
        $idCategoriaToxicologica = htmlspecialchars($_POST['idCategoriaToxicologica'], ENT_NOQUOTES, 'UTF-8');

        try {
            $cr = new ControladorRIA();

            $cr->modificarComposicion($conexion, $idComposicion, $idArea, $idCategoriaToxicologica);

            $mensaje['estado'] = 'exito';
            $mensaje['mensaje'] = 'Los datos fueron actualizados';

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