<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEventoSanitario.php';

	$identificador = $_SESSION['usuario'];	
	
	$idEventoSanitario = htmlspecialchars ($_POST['idEventoSanitario'],ENT_NOQUOTES,'UTF-8');
	$conclusionFinal  = htmlspecialchars ($_POST['conclusionFinal'],ENT_NOQUOTES,'UTF-8');
	
	$conexion = new Conexion();
	$cpco = new ControladorEventoSanitario();
		
	if(($identificador != null) || ($identificador != '')){
		
		$conexion->ejecutarConsulta("begin;");
		
		$cpco->modificarEventoSanitarioConclusionFinal($conexion, $idEventoSanitario, $conclusionFinal, $identificador);
			
		$conexion->ejecutarConsulta("commit;");
		
		$conexion->desconectar();

	}else{
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = 'Su sesión ha expirado, por favor ingrese nuevamente al sistema para continuar.';
	
		$conexion->desconectar();
	}
?>