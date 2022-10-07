<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProgramasControlOficial.php';

$fecha = getdate();
$anio = $fecha['year'];

$conexion = new Conexion();
$cpco = new ControladorProgramasControlOficial();

	$identificador = $_SESSION['usuario'];
	
	$fecha = htmlspecialchars ($_POST['fecha'],ENT_NOQUOTES,'UTF-8');
	$nombrePredio = htmlspecialchars ($_POST['nombrePredio'],ENT_NOQUOTES,'UTF-8');
	$nombrePropietario = htmlspecialchars ($_POST['nombrePropietario'],ENT_NOQUOTES,'UTF-8');
	$cedulaPropietario = htmlspecialchars ($_POST['cedulaPropietario'],ENT_NOQUOTES,'UTF-8');
	$telefonoPropietario = htmlspecialchars ($_POST['telefonoPropietario'],ENT_NOQUOTES,'UTF-8');
	$correoElectronicoPropietario = htmlspecialchars ($_POST['correoElectronicoPropietario'],ENT_NOQUOTES,'UTF-8');
	$nombreAdministrador = htmlspecialchars ($_POST['nombreAdministrador'],ENT_NOQUOTES,'UTF-8');
	$cedulaAdministrador = htmlspecialchars ($_POST['cedulaAdministrador'],ENT_NOQUOTES,'UTF-8');
	$telefonoAdministrador = htmlspecialchars ($_POST['telefonoAdministrador'],ENT_NOQUOTES,'UTF-8');
	$correoElectronicoAdministrador = htmlspecialchars ($_POST['correoElectronicoAdministrador'],ENT_NOQUOTES,'UTF-8');
	$idProvincia = htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8');
	$provincia = htmlspecialchars ($_POST['nombreProvincia'],ENT_NOQUOTES,'UTF-8');
	$idCanton = htmlspecialchars ($_POST['canton'],ENT_NOQUOTES,'UTF-8');
	$canton = htmlspecialchars ($_POST['nombreCanton'],ENT_NOQUOTES,'UTF-8');
	$idParroquia = htmlspecialchars ($_POST['parroquia'],ENT_NOQUOTES,'UTF-8');
	$parroquia = htmlspecialchars ($_POST['nombreParroquia'],ENT_NOQUOTES,'UTF-8');
	$codigoParroquia = htmlspecialchars ($_POST['codigoParroquia'],ENT_NOQUOTES,'UTF-8');
	$direccionPredio = htmlspecialchars ($_POST['direccionPredio'],ENT_NOQUOTES,'UTF-8');
	$x = htmlspecialchars ($_POST['x'],ENT_NOQUOTES,'UTF-8');
	$y = htmlspecialchars ($_POST['y'],ENT_NOQUOTES,'UTF-8');
	$z = htmlspecialchars ($_POST['z'],ENT_NOQUOTES,'UTF-8');
	$altitud = null;    //param no solicitado en form
	$latitud = null;    //param no solicitado en form
        $longitud = null;   //param no solicitado en form
        $zona = null;       //param no solicitado en form
	$extension = (integer) $_POST['extension'];
	$imagenMapa = htmlspecialchars ($_POST['archivo'],ENT_NOQUOTES,'UTF-8');
	$informe = htmlspecialchars ($_POST['archivoInforme'],ENT_NOQUOTES,'UTF-8');

	
	if(($identificador != null) || ($identificador != '')){
	
		$conexion->ejecutarConsulta("begin;");
		
			$numero = pg_fetch_result($cpco->generarNumeroCatastroPredioEquidos($conexion, 'PCO-CPE-'.$codigoParroquia), 0, 'num_solicitud');
			$tmp= explode("-", $numero);
			$incremento = end($tmp)+1;
			$numeroSolicitud = 'PCO-CPE-'.$codigoParroquia.'-'.str_pad($incremento, 4, "0", STR_PAD_LEFT);			
	
			$idCatastroPredioEquidos = $cpco->nuevoCatastroPredioEquidos($conexion, $identificador, $numeroSolicitud, 
																		$fecha, $nombrePredio, $nombrePropietario, 
																		$cedulaPropietario, $telefonoPropietario, 
																		$correoElectronicoPropietario, 
																		$nombreAdministrador, $cedulaAdministrador, 
																		$telefonoAdministrador, $correoElectronicoAdministrador, 
																		$idProvincia, $provincia, $idCanton, $canton, 
																		$idParroquia, $parroquia, $direccionPredio, 
																		$x, $y, $z, $altitud, $latitud, $longitud, $zona, $extension, $imagenMapa, $informe);
	
		$conexion->ejecutarConsulta("commit;");

		echo '<input type="hidden" id="' . pg_fetch_result($idCatastroPredioEquidos, 0, 'id_catastro_predio_equidos') . '" data-rutaAplicacion="programasControlOficial" data-opcion="abrirCatastroPredioEquidos" data-destino="detalleItem"/>';

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