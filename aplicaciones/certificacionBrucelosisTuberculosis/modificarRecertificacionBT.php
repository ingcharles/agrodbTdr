<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorBrucelosisTuberculosis.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
	
	$identificador = $_SESSION['usuario'];
	
	$idRecertificacionBT = htmlspecialchars ($_POST['idRecertificacionBT'],ENT_NOQUOTES,'UTF-8');
	$estado = htmlspecialchars ($_POST['estado'],ENT_NOQUOTES,'UTF-8');
	
	$fecha = htmlspecialchars ($_POST['fecha'],ENT_NOQUOTES,'UTF-8');
	$nombreEncuestado = htmlspecialchars ($_POST['nombreEncuestado'],ENT_NOQUOTES,'UTF-8');
	$nombrePredio = htmlspecialchars ($_POST['nombrePredio'],ENT_NOQUOTES,'UTF-8');
	$numCertFiebreAftosa = htmlspecialchars ($_POST['numCertFiebreAftosa'],ENT_NOQUOTES,'UTF-8');
	
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
	
	$latitud = htmlspecialchars ($_POST['latitud'],ENT_NOQUOTES,'UTF-8');
	$longitud = htmlspecialchars ($_POST['longitud'],ENT_NOQUOTES,'UTF-8');
	$zona = htmlspecialchars ($_POST['zona'],ENT_NOQUOTES,'UTF-8');
	
	$certificacion = htmlspecialchars ($_POST['certificacion'],ENT_NOQUOTES,'UTF-8');
	
	$fechaMuestreoBrucelosis = htmlspecialchars ($_POST['fechaMuestreoBrucelosis'],ENT_NOQUOTES,'UTF-8');
	$fechaTuberculinizacion = htmlspecialchars ($_POST['fechaTuberculinizacion'],ENT_NOQUOTES,'UTF-8');
	
	
		$conexion = new Conexion();
		$cbt = new ControladorBrucelosisTuberculosis();
		
		if(($identificador != null) || ($identificador != '')){
		
			$conexion->ejecutarConsulta("begin;");
		
			$cbt->modificarRecertificacionBT($conexion, $idRecertificacionBT, $identificador, $fecha, 
												$nombreEncuestado, $fechaMuestreoBrucelosis, 
												$fechaTuberculinizacion, $certificacion);
			
			$conexion->ejecutarConsulta("commit;");
		
			switch ($certificacion){
				case 'Brucelosis':
					if($estado == 'inspeccion'){
						echo '<input type="hidden" id="' . $idRecertificacionBT . '" data-rutaAplicacion="certificacionBrucelosisTuberculosis" data-opcion="abrirRecertificacionBTBrucelosisInspeccion" data-destino="detalleItem"/>';
					}else{
						echo '<input type="hidden" id="' . $idRecertificacionBT . '" data-rutaAplicacion="certificacionBrucelosisTuberculosis" data-opcion="abrirRecertificacionBTBrucelosis" data-destino="detalleItem"/>';
					}
					break;
			
				case 'Tuberculosis':
					if($estado == 'inspeccion'){
						echo '<input type="hidden" id="' . $idRecertificacionBT . '" data-rutaAplicacion="certificacionBrucelosisTuberculosis" data-opcion="abrirRecertificacionBTTuberculosisInspeccion" data-destino="detalleItem"/>';
					}else{
						echo '<input type="hidden" id="' . $idRecertificacionBT . '" data-rutaAplicacion="certificacionBrucelosisTuberculosis" data-opcion="abrirRecertificacionBTTuberculosis" data-destino="detalleItem"/>';
					}
					break;
			
				default:
					break;
			}
			
			$conexion->desconectar();
			
		}else{
			$conexion->desconectar();			
		}
?>

<script type="text/javascript">
	$("document").ready(function(){
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
		abrir($("#detalleItem input"),null,true);
	});	
</script>