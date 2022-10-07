<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEventoSanitario.php';

$ruta = 'seguimientoEventosSanitarios';

	$conexion = new Conexion();
	$cpco = new ControladorEventoSanitario();
	
	$identificador = $_SESSION['usuario'];
	
	$fecha = htmlspecialchars ($_POST['fecha'],ENT_NOQUOTES,'UTF-8');
	
	$idOrigen = htmlspecialchars ($_POST['origenNotificacion'],ENT_NOQUOTES,'UTF-8');
	$nombreOrigen = htmlspecialchars ($_POST['nombreOrigen'],ENT_NOQUOTES,'UTF-8');
	$idCanal = htmlspecialchars ($_POST['canalNotificacion'],ENT_NOQUOTES,'UTF-8');
	$nombreCanal = htmlspecialchars ($_POST['nombreCanal'],ENT_NOQUOTES,'UTF-8');
	
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
	
	$idProvincia = htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8');
	$nombreProvincia = htmlspecialchars ($_POST['nombreProvincia'],ENT_NOQUOTES,'UTF-8');
	
	$idCanton = htmlspecialchars ($_POST['canton'],ENT_NOQUOTES,'UTF-8');
	$nombreCanton = htmlspecialchars ($_POST['nombreCanton'],ENT_NOQUOTES,'UTF-8');
	
	$idParroquia = htmlspecialchars ($_POST['parroquia'],ENT_NOQUOTES,'UTF-8');
	$nombreParroquia = htmlspecialchars ($_POST['nombreParroquia'],ENT_NOQUOTES,'UTF-8');
	$codigoParroquia = htmlspecialchars ($_POST['codigoParroquia'],ENT_NOQUOTES,'UTF-8');
	
	$idOficina = htmlspecialchars ($_POST['oficina'],ENT_NOQUOTES,'UTF-8');
	$oficina = htmlspecialchars ($_POST['nombreOficina'],ENT_NOQUOTES,'UTF-8');
	
	$semana = htmlspecialchars ($_POST['semana'],ENT_NOQUOTES,'UTF-8');
	$husoZona = htmlspecialchars ($_POST['zonaPredio'],ENT_NOQUOTES,'UTF-8');
	$utmX = htmlspecialchars ($_POST['utmX'],ENT_NOQUOTES,'UTF-8');
	$utmY = htmlspecialchars ($_POST['utmY'],ENT_NOQUOTES,'UTF-8');
	$utmZ = htmlspecialchars ($_POST['utmZ'],ENT_NOQUOTES,'UTF-8');
	$sitioPredio = htmlspecialchars ($_POST['sitioPredio'],ENT_NOQUOTES,'UTF-8');
		

		
	if(($identificador != null) || ($identificador != '')){
	
		$conexion->ejecutarConsulta("begin;");
		
			$numero = pg_fetch_result($cpco->generarNumeroEventoSanitario($conexion, 'ES-'.$codigoParroquia), 0, 'num_solicitud');
			$tmp= explode("-", $numero);
			$incremento = end($tmp)+1;
			$numeroSolicitud = 'ES-'.$codigoParroquia.'-'.str_pad($incremento, 4, "0", STR_PAD_LEFT);
			$conexion->ejecutarConsulta("begin;");
			
				$idEventoSanitarioCon = $cpco->nuevoEventoSanitario($conexion,
														$identificador,
														$numeroSolicitud, $fecha, $idOrigen, $nombreOrigen, $idCanal, $nombreCanal, $nombrePropietario,
														$cedulaPropietario, $telefonoPropietario, $celularPropietario, $correoElectronicoPropietario,
														$nombrePredio, $extencionPredio, $idMedida, $medida, $otrosPredios, $numeroPredios, $bioseg,
														$idProvincia, $nombreProvincia, $idCanton, $nombreCanton, $idParroquia, $nombreParroquia,
														$idOficina, $oficina, $semana, $husoZona, $utmX, $utmY, $utmZ,													
														$sitioPredio);
														
			
			$conexion->ejecutarConsulta("commit;");
			
		echo '<input type="hidden" id="' . pg_fetch_result($idEventoSanitarioCon, 0, 'id_evento_sanitario') . '" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="abrirEventoSanitario" data-destino="detalleItem"/>';
	}else{
		echo '<label>Su sesión ha expirado, por favor ingrese nuevamente al sistema para continuar.</label>';
	}

?>

<script type="text/javascript">
	$("document").ready(function(){
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
		abrir($("#detalleItem input"),null,true);
	});	
</script>
