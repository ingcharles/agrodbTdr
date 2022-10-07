<?php
session_start();
require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorFormularios.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido  un error!';

try{
    try {
        $conexion = new Conexion();
        $cf = new ControladorFormularios();

        $provincia = htmlspecialchars ($_POST['parametroBusqueda'],ENT_NOQUOTES,'UTF-8');
        $area = htmlspecialchars ($_POST['parametroArea'],ENT_NOQUOTES,'UTF-8');
        $canton = htmlspecialchars ($_POST['parametroCanton'],ENT_NOQUOTES,'UTF-8');
                
        $canton = explode(", ", $canton);
        $cadenaCanton = "";
        
        foreach ($canton as $variableCanton){
        	$cadenaCanton .= "'".mb_strtoupper($variableCanton,'UTF-8')."',";
        }
        
        $cadenaCanton = "(".rtrim($cadenaCanton,',').")";
                
        $operaciones = $cf->jsonObtenerOperadoresPorProvincia($conexion, $provincia, $area, $cadenaCanton);

        $mensaje['estado'] = 'exito';
        $mensaje['mensaje'] = $operaciones[array_to_json];

        $conexion->desconectar();
    } catch (Exception $ex){
        pg_close($conexion);
        $mensaje['mensaje'] = "Error al ejecutar sentencia";
        echo json_encode($mensaje);
    } finally {
        pg_close($conexion);
    }
} catch (Exception $ex) {
    $mensaje['mensaje'] = 'Error de conexión a la base de datos';
    echo json_encode($mensaje);
} finally {
    echo json_encode($mensaje);
}
?>