<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEventoSanitario.php';

	$identificador = $_SESSION['usuario'];
	
	$idEventoSanitario = htmlspecialchars ($_POST['idEventoSanitario'],ENT_NOQUOTES,'UTF-8');
	
	$nombrePropietario = htmlspecialchars ($_POST['nombrePropietario'],ENT_NOQUOTES,'UTF-8');
	$cedulaPropietario = htmlspecialchars ($_POST['cedulaPropietario'],ENT_NOQUOTES,'UTF-8');
	$telefonoPropietario = htmlspecialchars ($_POST['telefonoPropietario'],ENT_NOQUOTES,'UTF-8');
	$celularPropietario = htmlspecialchars ($_POST['celularPropietario'],ENT_NOQUOTES,'UTF-8');
	$correoElectronicoPropietario = htmlspecialchars ($_POST['correoElectronicoPropietario'],ENT_NOQUOTES,'UTF-8');
	
	$nombrePredio = htmlspecialchars ($_POST['nombrePredio'],ENT_NOQUOTES,'UTF-8');
	$extencionPredio = htmlspecialchars ($_POST['extencionPredio'],ENT_NOQUOTES,'UTF-8');
	$idMedida = htmlspecialchars ($_POST['unidadMedida'],ENT_NOQUOTES,'UTF-8');
	$medida = htmlspecialchars ($_POST['medidaPredio'],ENT_NOQUOTES,'UTF-8');
	$otrosPredios = htmlspecialchars ($_POST['otroPredio'],ENT_NOQUOTES,'UTF-8');
	$numeroPredios = htmlspecialchars ($_POST['numeroPredios'],ENT_NOQUOTES,'UTF-8');
	$bioseg = htmlspecialchars ($_POST['bioseguridad'],ENT_NOQUOTES,'UTF-8');
	
	$idOficina = htmlspecialchars ($_POST['oficina'],ENT_NOQUOTES,'UTF-8');
	$oficina = htmlspecialchars ($_POST['nombreOficina'],ENT_NOQUOTES,'UTF-8');
	
	$semana = htmlspecialchars ($_POST['semana'],ENT_NOQUOTES,'UTF-8');
	$husoZona = htmlspecialchars ($_POST['zonaPredio'],ENT_NOQUOTES,'UTF-8');
	$utmX = htmlspecialchars ($_POST['utmX'],ENT_NOQUOTES,'UTF-8');
	$utmY = htmlspecialchars ($_POST['utmY'],ENT_NOQUOTES,'UTF-8');
	$utmZ = htmlspecialchars ($_POST['utmZ'],ENT_NOQUOTES,'UTF-8');
	$sitioPredio = htmlspecialchars ($_POST['sitioPredio'],ENT_NOQUOTES,'UTF-8');
		
	

		$conexion = new Conexion();
		$cpco = new ControladorEventoSanitario();
		
		if(($identificador != null) || ($identificador != '')){
		
			$conexion->ejecutarConsulta("begin;");
		
			$cpco->modificarEventoSanitario($conexion, $idEventoSanitario, $identificador, 
														$nombrePropietario,$cedulaPropietario, $telefonoPropietario, $celularPropietario, $correoElectronicoPropietario,
														$nombrePredio, $extencionPredio, $idMedida, $medida, $otrosPredios, $numeroPredios, $bioseg,
														$idOficina, $oficina, $semana, $husoZona, $utmX, $utmY, $utmZ,													
														$sitioPredio);
			
			$conexion->ejecutarConsulta("commit;");
		
			echo '<input type="hidden" id="' . $idEventoSanitario . '" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="abrirEventoSanitario" data-destino="detalleItem"/>';
	
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