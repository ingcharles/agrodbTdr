<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEventoSanitario.php';

	$identificador = $_SESSION['usuario'];
	
	
	
	$idEventoSanitario = htmlspecialchars ($_POST['idEventoSanitario'],ENT_NOQUOTES,'UTF-8');
	$sintomatologia  = htmlspecialchars ($_POST['sintomatologia'],ENT_NOQUOTES,'UTF-8');
	$lecionesNecropsia = htmlspecialchars ($_POST['lecionesNecropsia'],ENT_NOQUOTES,'UTF-8');
	$especiePrimerAnimal = htmlspecialchars ($_POST['especiePrimerAnimal'],ENT_NOQUOTES,'UTF-8');
	$nombreEspeciePrimerAnimal  = htmlspecialchars ($_POST['nombreEspeciePrimerAnimal'],ENT_NOQUOTES,'UTF-8');
	$edadPrimerAnimal = htmlspecialchars ($_POST['edadPrimerAnimal'],ENT_NOQUOTES,'UTF-8');
	$vacunaPrimerAnimal = htmlspecialchars ($_POST['vacunaPrimerAnimal'],ENT_NOQUOTES,'UTF-8');
	$ingresadoPrimerAnimal = htmlspecialchars ($_POST['ingresadoPrimerAnimal'],ENT_NOQUOTES,'UTF-8');
	$sindromePresuntivo = htmlspecialchars ($_POST['sindromePresuntivo'],ENT_NOQUOTES,'UTF-8');
	$fechaInspeccion = htmlspecialchars ($_POST['fechaInspeccion'],ENT_NOQUOTES,'UTF-8');
	
	$conexion = new Conexion();
	$cpco = new ControladorEventoSanitario();
		
	if(($identificador != null) || ($identificador != '')){
		
		$conexion->ejecutarConsulta("begin;");
		
		$cpco->modificarEventoSanitarioProcedimiento($conexion, $idEventoSanitario, $sintomatologia, $lecionesNecropsia, $especiePrimerAnimal, $nombreEspeciePrimerAnimal, 
															$edadPrimerAnimal, $ingresadoPrimerAnimal, $sindromePresuntivo, $identificador, 'Visita 0001');
			
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