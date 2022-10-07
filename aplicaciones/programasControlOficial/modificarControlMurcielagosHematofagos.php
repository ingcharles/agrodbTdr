<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProgramasControlOficial.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

	
	$identificador = $_SESSION['usuario'];
	
	$idMurcielagosHematofagos = htmlspecialchars ($_POST['idMurcielagosHematofagos'],ENT_NOQUOTES,'UTF-8');
	
	$fecha = htmlspecialchars ($_POST['fecha'],ENT_NOQUOTES,'UTF-8');
	$nombrePredio = htmlspecialchars ($_POST['nombrePredio'],ENT_NOQUOTES,'UTF-8');
	$nombrePropietario = htmlspecialchars ($_POST['nombrePropietario'],ENT_NOQUOTES,'UTF-8');
	$personaRefugio = htmlspecialchars ($_POST['personaRefugio'],ENT_NOQUOTES,'UTF-8');
	$idTipoRefugio = htmlspecialchars ($_POST['refugio'],ENT_NOQUOTES,'UTF-8');
	$tipoRefugio = htmlspecialchars ($_POST['nombreRefugio'],ENT_NOQUOTES,'UTF-8');
	$idProvincia = htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8');
	$provincia = htmlspecialchars ($_POST['nombreProvincia'],ENT_NOQUOTES,'UTF-8');
	$idCanton = htmlspecialchars ($_POST['canton'],ENT_NOQUOTES,'UTF-8');
	$canton = htmlspecialchars ($_POST['nombreCanton'],ENT_NOQUOTES,'UTF-8');
	$idParroquia = htmlspecialchars ($_POST['parroquia'],ENT_NOQUOTES,'UTF-8');
	$parroquia = htmlspecialchars ($_POST['nombreParroquia'],ENT_NOQUOTES,'UTF-8');
	$sitio = htmlspecialchars ($_POST['sitio'],ENT_NOQUOTES,'UTF-8');
	$idOficina = htmlspecialchars ($_POST['oficina'],ENT_NOQUOTES,'UTF-8');
	$oficina = htmlspecialchars ($_POST['nombreOficina'],ENT_NOQUOTES,'UTF-8');
	$x = htmlspecialchars ($_POST['x'],ENT_NOQUOTES,'UTF-8');
	$y = htmlspecialchars ($_POST['y'],ENT_NOQUOTES,'UTF-8');
	$z = htmlspecialchars ($_POST['z'],ENT_NOQUOTES,'UTF-8');
	$altitud = null;    //param no solicitado en form
	$latitud = null;    //param no solicitado en form
        $longitud = null;   //param no solicitado en form
        $zona = null;       //param no solicitado en form
	
	

		$conexion = new Conexion();
		$cpco = new ControladorProgramasControlOficial();
		
		if(($identificador != null) || ($identificador != '')){
		
			$conexion->ejecutarConsulta("begin;");
		
			$cpco->modificarControlMurcielagosHematofagos(  $conexion, $idMurcielagosHematofagos, $identificador, 
															$fecha, $nombrePredio,
															$nombrePropietario, $personaRefugio,
															$idTipoRefugio, $tipoRefugio,
															$sitio,
															$idOficina, $oficina, $x, $y,
															$z, $altitud, $latitud, $longitud, $zona);
			
			$conexion->ejecutarConsulta("commit;");
		
			echo '<input type="hidden" id="' . $idMurcielagosHematofagos . '" data-rutaAplicacion="programasControlOficial" data-opcion="abrirControlMurcielagosHematofagos" data-destino="detalleItem"/>';
			
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