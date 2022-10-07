<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorNotificacionEventoSanitario.php';

$ruta = 'seguimientoEventosSanitarios';

	$conexion = new Conexion();
	$cpco = new ControladorNotificacionEventoSanitario();
	
	$identificador = $_SESSION['usuario'];
	
	$idNotificacionEventoSanitario = htmlspecialchars ($_POST['idNotificacionEventoSanitario'],ENT_NOQUOTES,'UTF-8');
	
	$numero = htmlspecialchars ($_POST['numero'],ENT_NOQUOTES,'UTF-8');
	$fecha = htmlspecialchars ($_POST['fecha'],ENT_NOQUOTES,'UTF-8');
	
	$idOrigen = htmlspecialchars ($_POST['origenNotificacion'],ENT_NOQUOTES,'UTF-8');
	$nombreOrigen = htmlspecialchars ($_POST['nombreOrigen'],ENT_NOQUOTES,'UTF-8');
	$idCanal = htmlspecialchars ($_POST['canalNotificacion'],ENT_NOQUOTES,'UTF-8');
	$nombreCanal = htmlspecialchars ($_POST['nombreCanal'],ENT_NOQUOTES,'UTF-8');
	
	$nombreInformante = htmlspecialchars ($_POST['nombreInformante'],ENT_NOQUOTES,'UTF-8');
	$telefonoInformante = htmlspecialchars ($_POST['telefonoInformante'],ENT_NOQUOTES,'UTF-8');
	$celularInformante = htmlspecialchars ($_POST['celularInformante'],ENT_NOQUOTES,'UTF-8');
	$correoElectronicoInformante = htmlspecialchars ($_POST['correoElectronicoInformante'],ENT_NOQUOTES,'UTF-8');
	
	$idProvincia = htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8');
	$nombreProvincia = htmlspecialchars ($_POST['nombreProvincia'],ENT_NOQUOTES,'UTF-8');
	
	$idCanton = htmlspecialchars ($_POST['canton'],ENT_NOQUOTES,'UTF-8');
	$nombreCanton = htmlspecialchars ($_POST['nombreCanton'],ENT_NOQUOTES,'UTF-8');
	
	$idParroquia = htmlspecialchars ($_POST['parroquia'],ENT_NOQUOTES,'UTF-8');
	$nombreParroquia = htmlspecialchars ($_POST['nombreParroquia'],ENT_NOQUOTES,'UTF-8');
	$codigoParroquia = htmlspecialchars ($_POST['codigoParroquia'],ENT_NOQUOTES,'UTF-8');
	
	$sitioPredio = htmlspecialchars ($_POST['sitioPredio'],ENT_NOQUOTES,'UTF-8');
	$fincaPredio = htmlspecialchars ($_POST['fincaPredio'],ENT_NOQUOTES,'UTF-8');
	
	$archivoInforme = htmlspecialchars ($_POST['archivoInforme'],ENT_NOQUOTES,'UTF-8');

		
	if(($identificador != null) || ($identificador != '')){
	
		$conexion->ejecutarConsulta("begin;");
		
			$numero = pg_fetch_result($cpco->generarNumeroEventoSanitario($conexion, 'EV-'.$codigoParroquia), 0, 'num_solicitud');
			$tmp= explode("-", $numero);
			$incremento = end($tmp)+1;
			$numeroSolicitud = 'EV-'.$codigoParroquia.'-'.str_pad($incremento, 4, "0", STR_PAD_LEFT);
			$conexion->ejecutarConsulta("begin;");
			
				$idNotificacionEventoSanitarioCon = $cpco->nuevaNotificacionEventoSanitario($conexion, $idNotificacionEventoSanitario,
														$identificador,
														$numeroSolicitud, $fecha, $idOrigen, $nombreOrigen, $idCanal, $nombreCanal, $nombreInformante,
														$telefonoInformante, $celularInformante, $correoElectronicoInformante, 	$idProvincia,
														$nombreProvincia, $idCanton, $nombreCanton, $idParroquia, $nombreParroquia, $sitioPredio, $fincaPredio,
														$archivoInforme);
														
			
			$conexion->ejecutarConsulta("commit;");
			
		echo '<input type="hidden" id="' . pg_fetch_result($idNotificacionEventoSanitarioCon, 0, 'id_notificacion_evento_sanitario') . '" data-rutaAplicacion="seguimientoEventosSanitarios" data-opcion="abrirNotificacionEventoSanitario" data-destino="detalleItem"/>';
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
