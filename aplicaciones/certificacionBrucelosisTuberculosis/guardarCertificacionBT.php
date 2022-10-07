<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorBrucelosisTuberculosis.php';

$conexion = new Conexion();
$cbt = new ControladorBrucelosisTuberculosis();

	$identificador = $_SESSION['usuario'];
	
	$fecha = htmlspecialchars ($_POST['fecha'],ENT_NOQUOTES,'UTF-8');
	$nombreEncuestado = htmlspecialchars ($_POST['nombreEncuestado'],ENT_NOQUOTES,'UTF-8');
	$idPredio = htmlspecialchars ($_POST['predio'],ENT_NOQUOTES,'UTF-8');
	$nombrePredio = htmlspecialchars ($_POST['nombrePredio'],ENT_NOQUOTES,'UTF-8');
	$numCertFiebreAftosa = htmlspecialchars ($_POST['numCertFiebreAftosa'],ENT_NOQUOTES,'UTF-8');
	$certificacion = htmlspecialchars ($_POST['certificacion'],ENT_NOQUOTES,'UTF-8');
	
	$nombrePropietario = htmlspecialchars ($_POST['nombrePropietario'],ENT_NOQUOTES,'UTF-8');
	$cedulaPropietario = htmlspecialchars ($_POST['cedulaPropietario'],ENT_NOQUOTES,'UTF-8');
	$telefonoPropietario = htmlspecialchars ($_POST['telefonoPropietario'],ENT_NOQUOTES,'UTF-8');
	$celularPropietario = htmlspecialchars ($_POST['celularPropietario'],ENT_NOQUOTES,'UTF-8');
	$correoElectronicoPropietario = htmlspecialchars ($_POST['correoElectronicoPropietario'],ENT_NOQUOTES,'UTF-8');
	
	$idProvincia = htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8');
	$provincia = htmlspecialchars ($_POST['nombreProvincia'],ENT_NOQUOTES,'UTF-8');
	$idCanton = htmlspecialchars ($_POST['canton'],ENT_NOQUOTES,'UTF-8');
	$canton = htmlspecialchars ($_POST['nombreCanton'],ENT_NOQUOTES,'UTF-8');
	$idParroquia = htmlspecialchars ($_POST['parroquia'],ENT_NOQUOTES,'UTF-8');
	$parroquia = htmlspecialchars ($_POST['nombreParroquia'],ENT_NOQUOTES,'UTF-8');
	$codigoParroquia = htmlspecialchars ($_POST['codigoParroquia'],ENT_NOQUOTES,'UTF-8');
	
	$x = htmlspecialchars ($_POST['x'],ENT_NOQUOTES,'UTF-8');
	$y = htmlspecialchars ($_POST['y'],ENT_NOQUOTES,'UTF-8');
	$z = htmlspecialchars ($_POST['z'],ENT_NOQUOTES,'UTF-8');
	$huso = htmlspecialchars ($_POST['huso'],ENT_NOQUOTES,'UTF-8');
	
	$imagenMapa = htmlspecialchars ($_POST['archivo'],ENT_NOQUOTES,'UTF-8');
	$informe = htmlspecialchars ($_POST['archivoInforme'],ENT_NOQUOTES,'UTF-8');
	
	switch ($certificacion){
		case 'Brucelosis':
			$codigoPrograma='B';
			break;
		
		case 'Tuberculosis':
			$codigoPrograma='T';
			break;

		default:
			break;
	}
	
	if(($identificador != null) || ($identificador != '')){
	
		$conexion->ejecutarConsulta("begin;");
		
			$numero = pg_fetch_result($cbt->generarNumeroCertificacionBT($conexion, 'CPLBT-'.$codigoPrograma.'-'.$codigoParroquia), 0, 'num_solicitud');
			$tmp= explode("-", $numero);
			$incremento = end($tmp)+1;
			$numeroSolicitud = 'CPLBT-'.$codigoPrograma.'-'.$codigoParroquia.'-'.str_pad($incremento, 4, "0", STR_PAD_LEFT);			
	
			$idCertificacionBT = $cbt->nuevaCertificacionBT($conexion, $identificador, $numeroSolicitud, $fecha, $nombreEncuestado, 
															$idPredio, $nombrePredio, $nombrePropietario, $cedulaPropietario, 
															$telefonoPropietario, $celularPropietario, 
															$correoElectronicoPropietario, $idProvincia, $provincia, 
															$idCanton, $canton, $idParroquia, $parroquia, 
															$numCertFiebreAftosa, $certificacion, 
															$x, $y, $z, $huso, $imagenMapa, $informe, 'Inspección 0001');
			
			/*$numInspeccion = pg_fetch_result($cbt->generarNumeroInspeccionCertificacionBT($conexion, 'Inspección ', $numeroSolicitud), 0, 'num_inspeccion');
			$tmpInspeccion= explode(" ", $numInspeccion);
			$incrementoInspeccion = end($tmpInspeccion)+1;
			$numeroInspeccion = 'Inspección '.str_pad($incrementoInspeccion, 4, "0", STR_PAD_LEFT);*/
				
		$conexion->ejecutarConsulta("commit;");

		switch ($certificacion){
			case 'Brucelosis':
				echo '<input type="hidden" id="' . pg_fetch_result($idCertificacionBT, 0, 'id_certificacion_bt') . '" data-rutaAplicacion="certificacionBrucelosisTuberculosis" data-opcion="abrirCertificacionBTBrucelosis" data-destino="detalleItem"/>';
				break;
		
			case 'Tuberculosis':
				echo '<input type="hidden" id="' . pg_fetch_result($idCertificacionBT, 0, 'id_certificacion_bt') . '" data-rutaAplicacion="certificacionBrucelosisTuberculosis" data-opcion="abrirCertificacionBTTuberculosis" data-destino="detalleItem"/>';
				break;
		
			default:
				break;
		}

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