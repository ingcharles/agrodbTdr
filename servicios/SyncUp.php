<?php
session_start();

header('Content-Type: application/json');

$servicio = htmlspecialchars($_POST['servicio'], ENT_NOQUOTES, 'UTF-8');
require_once 'Servicio.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
$mensaje['tipo'] = 'RESPUESTA';
$codigo = 400;


if (file_exists("$servicio.php")) {
    require_once "$servicio.php";
    $resultado = new $servicio();
    if ($resultado instanceof Servicio) {
        if (json_last_error() != JSON_ERROR_NONE) {
            $mensaje['mensaje'] = 'Error: ' . json_last_error_msg();
        } else {
            try {
                $mensaje['mensaje'] = $resultado->up($_POST);
                $mensaje['estado'] = $resultado->getEstado();
                $mensaje['log'] = $resultado->getLog();
                $codigo = $resultado->getCodigo();
            } catch (Exception $ex) {
                $mensaje['mensaje'] = $ex->getMessage();
                $mensaje['estado'] = $resultado->getEstado();
                $mensaje['log'] = $resultado->getLog();                $codigo = 500;
            } finally {
            }
        }
    } else {
        $mensaje['mensaje'] = 'El tipo de servicio solicitado es incompatible';
    }
} else {
    $mensaje['mensaje'] = "El servicio '$servicio' no existe";
    $codigo = 404;
}

echo json_encode($mensaje);
http_response_code($codigo);

?>