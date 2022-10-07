<?php
session_start();

require_once 'Servicio.php';

$mensaje = array();
$mensaje['estado'] = 'exito';
$mensaje['mensaje'] = 'Servidor respondiendo!';

echo json_encode($mensaje);
?>