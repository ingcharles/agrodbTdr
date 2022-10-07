<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRIA.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$idProducto = trim(htmlspecialchars($_POST['idProductoCreado'], ENT_NOQUOTES, 'UTF-8'));

try {
    $conexion = new Conexion();
    $cr = new ControladorRIA();
    try {
        //VERIFICAR ÁREA
        $res = pg_fetch_assoc($cr->abrirProducto($conexion, $idProducto));
        $area = $res['id_area'];
        //VERIFICAR QUE CADA INGREDIENTE DEL PRODUCTO TENGA UN FABRICANTE
        $fabricantesFaltantes = 0;
        if ($area == 'IAP') {
            $res = pg_fetch_assoc($cr->contarFabricantesDeIngredienteFaltantes($conexion, $idProducto));
            $fabricantesFaltantes = $res['fabricantes'];
        }
        //VERIFICAR QUE AL MENOS EXISTA UN USO
        $res = pg_fetch_assoc($cr->contarUsosDeProducto($conexion, $idProducto));
        $usos = $res['usos'];

        if (($usos > 0) && ($fabricantesFaltantes == 0)) {
            //ACTUALIZAR ESTADO DE PRODUCTO A FINALIZADO
            $cr->finalizarProductoInocuidad($conexion, $idProducto);
            $mensaje['estado'] = 'exito';
            $mensaje['mensaje'] = 'El producto fue finalizado exitosamente';
        } else {
            $mensaje['mensaje'] = 'No se puede finalizar el registro. El producto debe tener al menos un uso y todos los fabricantes de ingresados (SOLO PLAG.)';
        }
    } catch (Exception $ex) {
        $mensaje['mensaje'] = $ex->getMessage();
        $mensaje['error'] = $conexion->mensajeError;
    } finally {
        $conexion->desconectar();
    }
} catch (Exception $ex) {
    $mensaje['mensaje'] = $ex->getMessage();
    $mensaje['error'] = $conexion->mensajeError;
} finally {
    echo json_encode($mensaje);
}

?>



