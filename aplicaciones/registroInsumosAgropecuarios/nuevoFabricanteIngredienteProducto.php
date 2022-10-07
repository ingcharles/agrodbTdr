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
        $idFabricante = htmlspecialchars($_POST['idFabricante'], ENT_NOQUOTES, 'UTF-8');
        $idIngredienteActivo = htmlspecialchars($_POST['idIngredienteActivo'], ENT_NOQUOTES, 'UTF-8');
        $idPais = htmlspecialchars($_POST['idPais'], ENT_NOQUOTES, 'UTF-8');

        $nombreFabricante = htmlspecialchars($_POST['nombreFabricante'], ENT_NOQUOTES, 'UTF-8');
        $nombreIngredienteActivo = htmlspecialchars($_POST['nombreIngredienteActivo'], ENT_NOQUOTES, 'UTF-8');
        $nombrePais = htmlspecialchars($_POST['nombrePais'], ENT_NOQUOTES, 'UTF-8');

        try {
            $cr = new ControladorRIA();

            $conexion->ejecutarConsulta("begin;");
            if (substr($idFabricante, 0, 6) == 'nuevo_') {
                $nuevoIdFabricante = pg_fetch_row($cr->nuevoFabricante($conexion, substr($idFabricante, 6)));
                $idFabricante = $nuevoIdFabricante[0];
            }
            $id = pg_fetch_row($cr->nuevoFabricanteIngredienteProducto($conexion, $idFabricante, $idProducto, $idIngredienteActivo, $idPais));

            $conexion->ejecutarConsulta("commit;");
            $mensaje['estado'] = 'exito';
            $mensaje['mensaje'] = $cr->imprimirLineaFabricanteIngredienteProducto($id[0] . '_' . $id[1] . '_' . $id[2] . '_' . $id[3], "$nombreIngredienteActivo: $nombreFabricante ($nombrePais)");
        } catch (Exception $ex){
            $conexion->ejecutarConsulta("rollback;");
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