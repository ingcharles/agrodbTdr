<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorBrucelosisTuberculosis.php';

$conexion = new Conexion();
$cbt = new ControladorBrucelosisTuberculosis();

$idCertificacionBT = htmlspecialchars ($_POST['idCertificacionBT'],ENT_NOQUOTES,'UTF-8');
$certificacionBT = pg_fetch_assoc($cbt->abrirCertificacionBT($conexion, $idCertificacionBT));

	$identificador = $_SESSION['usuario'];
	
	$fecha = htmlspecialchars ($_POST['fecha'],ENT_NOQUOTES,'UTF-8');
	$nombreEncuestado = htmlspecialchars ($_POST['nombreEncuestado'],ENT_NOQUOTES,'UTF-8');
	$idPredio = $certificacionBT['id_predio'];
	$nombrePredio = $certificacionBT['nombre_predio'];
	$numCertFiebreAftosa = $certificacionBT['numero_certificado_fiebre_aftosa'];
	$certificacion = $certificacionBT['certificacion_bt'];
	$numSolicitud = $certificacionBT['num_solicitud'];
	
	$nombrePropietario = $certificacionBT['nombre_propietario'];
	$cedulaPropietario = $certificacionBT['cedula_propietario'];
	$telefonoPropietario = $certificacionBT['telefono_propietario'];
	$celularPropietario = $certificacionBT['celular_propietario'];
	$correoElectronicoPropietario = $certificacionBT['correo_electronico_propietario'];
	
	$idProvincia = $certificacionBT['id_provincia'];
	$provincia = $certificacionBT['provincia'];
	$idCanton = $certificacionBT['id_canton'];
	$canton = $certificacionBT['canton'];
	$idParroquia = $certificacionBT['id_parroquia'];
	$parroquia = $certificacionBT['parroquia'];
	
	$x = $certificacionBT['utm_x'];
	$y = $certificacionBT['utm_y'];
	$z = $certificacionBT['utm_z'];
	$huso = $certificacionBT['huso_zona'];
	
	$imagenMapa = $certificacionBT['imagen_mapa'];
	$informe = htmlspecialchars ($_POST['archivoInforme'],ENT_NOQUOTES,'UTF-8');
	
	$fechaMuestreoBrucelosis = htmlspecialchars ($_POST['fechaMuestreoBrucelosis'],ENT_NOQUOTES,'UTF-8');
	$fechaTuberculinizacion = htmlspecialchars ($_POST['fechaTuberculinizacion'],ENT_NOQUOTES,'UTF-8');
	$nombreTecnicoResponsable = htmlspecialchars ($_POST['nombreTecnicoResponsable'],ENT_NOQUOTES,'UTF-8');
	
	if(($identificador != null) || ($identificador != '')){
	
		$conexion->ejecutarConsulta("begin;");
		
			$numero = pg_fetch_result($cbt->generarNumeroRecertificacionBT($conexion, 'Recertificacion_', $numSolicitud), 0, 'num_recertificacion');
			$tmp= explode("_", $numero);
			$incremento = end($tmp)+1;
			$numRecertificacion = 'Recertificacion_' . str_pad($incremento, 4, "0", STR_PAD_LEFT);			
	
			$idRecertificacionBT = $cbt->nuevaRecertificacionBT($conexion, $identificador, $idCertificacionBT, $numSolicitud, $fecha, $nombreEncuestado, 
																$idPredio, $nombrePredio, $nombrePropietario, $cedulaPropietario, 
																$telefonoPropietario, $celularPropietario, 
																$correoElectronicoPropietario, $idProvincia, $provincia, 
																$idCanton, $canton, $idParroquia, $parroquia, 
																$numCertFiebreAftosa, $certificacion, 
																$x, $y, $z, $huso, $imagenMapa, $informe, 'Inspección 0001', $numRecertificacion,
																$fechaMuestreoBrucelosis, $fechaTuberculinizacion, $nombreTecnicoResponsable);
			
			$cbt->actualizarEstadoCertificacionBT($conexion, $idCertificacionBT, 'recertificacion');
			
		$conexion->ejecutarConsulta("commit;");

		switch ($certificacion){
			case 'Brucelosis':
				echo '<input type="hidden" id="' . pg_fetch_result($idRecertificacionBT, 0, 'id_recertificacion_bt') . '" data-rutaAplicacion="certificacionBrucelosisTuberculosis" data-opcion="abrirRecertificacionBTBrucelosis" data-destino="detalleItem"/>';
				break;
		
			case 'Tuberculosis':
				echo '<input type="hidden" id="' . pg_fetch_result($idRecertificacionBT, 0, 'id_recertificacion_bt') . '" data-rutaAplicacion="certificacionBrucelosisTuberculosis" data-opcion="abrirRecertificacionBTTuberculosis" data-destino="detalleItem"/>';
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