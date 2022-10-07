<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorRIA.php';

    $mensaje = array();
    $mensaje['estado'] = 'error';
    $mensaje['mensaje'] = 'Ha ocurrido un error!';

    try{
        $conexion = new Conexion();

        $idArea = htmlspecialchars($_POST['area'], ENT_NOQUOTES, 'UTF-8');
        $idCategoriaToxicologica = htmlspecialchars($_POST['idCategoriaToxicologica'], ENT_NOQUOTES, 'UTF-8');

        try {
            $cr = new ControladorRIA();

            $id = pg_fetch_row($cr->guardarComposicion($conexion, $idArea, $idCategoriaToxicologica));

            $mensaje['estado'] = 'exito';
            $mensaje['mensaje'] = 'Los datos fueron actualizados';
            $mensaje['idComposicion'] = $id[0];

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