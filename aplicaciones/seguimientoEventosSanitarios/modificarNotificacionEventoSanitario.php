<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorNotificacionEventoSanitario.php';

	$identificador = $_SESSION['usuario'];
	
	$idNotificacionEventoSanitario = htmlspecialchars ($_POST['idNotificacionEventoSanitario'],ENT_NOQUOTES,'UTF-8');
	
	$nombreInformante = htmlspecialchars ($_POST['nombreInformante'],ENT_NOQUOTES,'UTF-8');
	$telefonoInformante = htmlspecialchars ($_POST['telefonoInformante'],ENT_NOQUOTES,'UTF-8');
	$celularInformante = htmlspecialchars ($_POST['celularInformante'],ENT_NOQUOTES,'UTF-8');
	$correoElectronicoInformante = htmlspecialchars ($_POST['correoElectronicoInformante'],ENT_NOQUOTES,'UTF-8');
	
	$sitioPredio = htmlspecialchars ($_POST['sitioPredio'],ENT_NOQUOTES,'UTF-8');
	$fincaPredio = htmlspecialchars ($_POST['fincaPredio'],ENT_NOQUOTES,'UTF-8');
	

		$conexion = new Conexion();
		$cpco = new ControladorNotificacionEventoSanitario();
		
		if(($identificador != null) || ($identificador != '')){
		
			$conexion->ejecutarConsulta("begin;");
		
			$cpco->modificarNotificacionEventoSanitario($conexion, $idNotificacionEventoSanitario, $identificador, 
														$nombreInformante, $telefonoInformante, $celularInformante, $correoElectronicoInformante,$sitioPredio, $fincaPredio);
			
			$conexion->ejecutarConsulta("commit;");
		
			echo '<input type="hidden" id="' . $idNotificacionEventoSanitario . '" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="abrirNotificacionEventoSanitario" data-destino="detalleItem"/>';
	
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