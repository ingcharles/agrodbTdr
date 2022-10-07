<?php

session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEnsayoEficacia.php';

$conexion = new Conexion();
$cc = new ControladorEnsayoEficacia();

$mensaje = array();
$mensaje['estado'] = 'NO';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$area = htmlspecialchars ($_POST['area'],ENT_NOQUOTES,'UTF-8');
    $tipoProducto = htmlspecialchars ($_POST['tipoProducto'],ENT_NOQUOTES,'UTF-8');
	 if($tipoProducto=='')
		 $tipoProducto=null;
	$mensaje['mensaje'] = $cc->obtenerIA($conexion,$area,$tipoProducto);
	$mensaje['estado'] = 'OK';
}
catch(Exception $ex ){}

echo json_encode($mensaje);

?>