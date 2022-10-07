<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorRIA.php';

    $mensaje = array();
    $mensaje['estado'] = 'error';
    $mensaje['mensaje'] = 'Ha ocurrido un error!';

    try{
        $conexion = new Conexion();

        $idIngredienteActivo = htmlspecialchars($_POST['idIngredienteActivo'], ENT_NOQUOTES, 'UTF-8');
        $nombreIngredienteActivo = htmlspecialchars($_POST['nombreIngredienteActivo'], ENT_NOQUOTES, 'UTF-8');
        $casIngredienteActivo = htmlspecialchars($_POST['casIngredienteActivo'], ENT_NOQUOTES, 'UTF-8');
        $estadoIngredienteActivo = htmlspecialchars($_POST['estadoIngredienteActivo'], ENT_NOQUOTES, 'UTF-8');
        $restriccionIngredienteActivo = htmlspecialchars($_POST['restriccionIngredienteActivo'], ENT_NOQUOTES, 'UTF-8');

        try {
            $cr = new ControladorRIA();

            $cr->modificarIngredienteActivo($conexion, $idIngredienteActivo, $nombreIngredienteActivo, $casIngredienteActivo, $estadoIngredienteActivo, $restriccionIngredienteActivo);

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