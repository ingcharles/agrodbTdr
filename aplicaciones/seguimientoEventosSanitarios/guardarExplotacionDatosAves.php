<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEventoSanitario.php';

	$identificador = $_SESSION['usuario'];
	
	$idEventoSanitario = htmlspecialchars ($_POST['idEventoSanitario'],ENT_NOQUOTES,'UTF-8');
	$explotacionAves = htmlspecialchars ($_POST['explotacionAves'],ENT_NOQUOTES,'UTF-8');
	
	
	$conexion = new Conexion();
	$cpco = new ControladorEventoSanitario();
		
	if(($identificador != null) || ($identificador != '')){
		
		$conexion->ejecutarConsulta("begin;");
		
		$cpco->modificarEventoSanitarioExplotaAves ($conexion, $idEventoSanitario, $explotacionAves, $identificador);
			
		$conexion->ejecutarConsulta("commit;");
				
		$conexion->desconectar();

	}else{
		$conexion->desconectar();
	}
?>