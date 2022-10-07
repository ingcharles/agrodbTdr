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

        $idComposicion = htmlspecialchars($_POST['idComposicion2'], ENT_NOQUOTES, 'UTF-8');
        $idUso = htmlspecialchars($_POST['idUso'], ENT_NOQUOTES, 'UTF-8');

        $uso = pg_fetch_assoc($cr->abrirUso($conexion, $idUso));

        try {
            $cr = new ControladorRIA();

            $id = pg_fetch_row($cr->nuevoComposicionUso($conexion, $idComposicion, $idUso));

            $mensaje['estado'] = 'exito';
            $mensaje['idComposicion'] = $idComposicion;
            $mensaje['mensaje'] = $cr->imprimirLineaComposicionUso($id[0] . '_' . $id[1], $uso['nombre_uso'] , $idComposicion);
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