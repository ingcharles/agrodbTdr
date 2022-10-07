<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEventoSanitario.php';

	$identificador = $_SESSION['usuario'];
	
	
	
	$idEventoSanitario = htmlspecialchars ($_POST['idEventoSanitario'],ENT_NOQUOTES,'UTF-8');
	$cuarentenaPredio = htmlspecialchars ($_POST['cuarentenaPredio'],ENT_NOQUOTES,'UTF-8');
	$numeroVisitaMedidaSanitaria = htmlspecialchars ($_POST['numeroVisitaMedidaSanitaria'],ENT_NOQUOTES,'UTF-8');
	$nombreVisitaMedidaSanitaria = htmlspecialchars ($_POST['nombreVisitaMedidaSanitaria'],ENT_NOQUOTES,'UTF-8');
	$numeroActa = htmlspecialchars ($_POST['numeroActa'],ENT_NOQUOTES,'UTF-8');
	$medidasSanitarias = htmlspecialchars ($_POST['medidasSanitarias'],ENT_NOQUOTES,'UTF-8');
	$observaciones = htmlspecialchars ($_POST['observaciones'],ENT_NOQUOTES,'UTF-8');
	
	$conexion = new Conexion();
	$cpco = new ControladorEventoSanitario();
		
	if(($identificador != null) || ($identificador != '')){
		
		$conexion->ejecutarConsulta("begin;");
		
		$cpco->nuevaMedidaSanitariaVisita($conexion, $idEventoSanitario, $cuarentenaPredio, $numeroVisitaMedidaSanitaria, $nombreVisitaMedidaSanitaria,
															$numeroActa,  $medidasSanitarias, $observaciones, $identificador);
			
		$conexion->ejecutarConsulta("commit;");
		
		$conexion->desconectar();

	}else{
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = 'Su sesión ha expirado, por favor ingrese nuevamente al sistema para continuar.';
	
		$conexion->desconectar();
	}
?>

<script type="text/javascript">
	$("document").ready(function(){
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
		abrir($("#detalleItem input"),null,true);
	});	
</script>