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
	$noRegistro = htmlspecialchars ($_POST['noRegistro'],ENT_NOQUOTES,'UTF-8');
	$arr=array();
	
	$arr=$cc->obtenerProductoRegistrado($conexion, $noRegistro);
	if(sizeof($arr['producto'])>0){
		$mensaje['mensaje']=$arr;
		$mensaje['estado'] = 'OK';
	}
}
catch(Exception $ex ){}

echo json_encode($mensaje);

?>