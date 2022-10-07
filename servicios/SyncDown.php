<?php
session_start();

header('Content-Type: application/json');
$servicio = htmlspecialchars($_POST['servicio'], ENT_NOQUOTES, 'UTF-8');
require_once 'Servicio.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
$codigo = 400;


if (file_exists("$servicio.php")) {
    require_once "$servicio.php";
    $resultado = new $servicio();
    if ($resultado instanceof Servicio) {
        try {
            $mensaje['mensaje'] = $resultado->down($_POST);
            $mensaje['estado'] = $resultado->getEstado();
            $mensaje['log'] = $resultado->getLog();
            $codigo = 200;
        } catch (Exception $ex) {
            $mensaje['mensaje'] = $ex->getMessage();
            $mensaje['estado'] = $resultado->getEstado();
            $mensaje['log'] = $resultado->getLog();
            $codigo = 500;
        } finally {
        }
    } else {
        $mensaje['mensaje'] = "El tipo de servicio '$servicio' es incompatible";
    }
} else {
    $mensaje['mensaje'] = "El servicio '$servicio' no existe";
    $codigo = 404;
}

echo json_encode($mensaje);
http_response_code($codigo);

?>