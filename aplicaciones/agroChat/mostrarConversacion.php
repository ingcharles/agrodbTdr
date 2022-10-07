<?php


require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorUsuarios.php';

$conexion = new Conexion();
$cu = new ControladorUsuarios();

$funcion='';
$funcion = $_POST['funcion'];
$rows = $_POST['rows'];

if($funcion==='comparar'){
	//echo "entro a compr";
	$qNumeroMensajeUsuarioContacto= $cu->numeroMensajeUsuarioContacto($conexion, 1722551049, 1719724781);
	$numMensaje=pg_num_rows($qNumeroMensajeUsuarioContacto);
	if($numMensaje==$rows){
		echo 'false';
	}else{
		echo $numMensaje;
	}

}
?>