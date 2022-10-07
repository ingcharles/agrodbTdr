<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProgramasControlOficial.php';

$conexion = new Conexion();
$cpco = new ControladorProgramasControlOficial();

	$identificador = $_SESSION['usuario'];
	
	$fecha = htmlspecialchars ($_POST['fecha'],ENT_NOQUOTES,'UTF-8');
	$idFaseLunar = htmlspecialchars ($_POST['faseLunar'],ENT_NOQUOTES,'UTF-8');
	$faseLunar = htmlspecialchars ($_POST['nombreFaseLunar'],ENT_NOQUOTES,'UTF-8');
	$duracion = htmlspecialchars ($_POST['duracion'],ENT_NOQUOTES,'UTF-8');
	$fechaDesde = htmlspecialchars ($_POST['fechaDesde'],ENT_NOQUOTES,'UTF-8');
	$fechaHasta = htmlspecialchars ($_POST['fechaHasta'],ENT_NOQUOTES,'UTF-8');
	$nombrePredio = htmlspecialchars ($_POST['nombrePredio'],ENT_NOQUOTES,'UTF-8');
	$nombrePropietario = htmlspecialchars ($_POST['nombrePropietario'],ENT_NOQUOTES,'UTF-8');
	$idProvincia = htmlspecialchars ($_POST['provincia'],ENT_NOQUOTES,'UTF-8');
	$provincia = htmlspecialchars ($_POST['nombreProvincia'],ENT_NOQUOTES,'UTF-8');
	$idCanton = htmlspecialchars ($_POST['canton'],ENT_NOQUOTES,'UTF-8');
	$canton = htmlspecialchars ($_POST['nombreCanton'],ENT_NOQUOTES,'UTF-8');
	$idParroquia = htmlspecialchars ($_POST['parroquia'],ENT_NOQUOTES,'UTF-8');
	$parroquia = htmlspecialchars ($_POST['nombreParroquia'],ENT_NOQUOTES,'UTF-8');
	$codigoParroquia = htmlspecialchars ($_POST['codigoParroquia'],ENT_NOQUOTES,'UTF-8');
	$sitio = htmlspecialchars ($_POST['sitio'],ENT_NOQUOTES,'UTF-8');	
	$idSitioCaptura = htmlspecialchars ($_POST['sitioCaptura'],ENT_NOQUOTES,'UTF-8');
	$nombreSitioCaptura = htmlspecialchars ($_POST['nombreSitioCaptura'],ENT_NOQUOTES,'UTF-8');
	$coberturaCorral = htmlspecialchars ($_POST['coberturaCorral'],ENT_NOQUOTES,'UTF-8');
	$x = htmlspecialchars ($_POST['x'],ENT_NOQUOTES,'UTF-8');
	$y = htmlspecialchars ($_POST['y'],ENT_NOQUOTES,'UTF-8');
	$z = htmlspecialchars ($_POST['z'],ENT_NOQUOTES,'UTF-8');
	$altitud = null;    //param no solicitado en form
	$latitud = null;    //param no solicitado en form
        $longitud = null;   //param no solicitado en form
        $zona = null;       //param no solicitado en form
	$imagenMapa = htmlspecialchars ($_POST['archivo'],ENT_NOQUOTES,'UTF-8');
	$informe = htmlspecialchars ($_POST['archivoInforme'],ENT_NOQUOTES,'UTF-8');

	
	if(($identificador != null) || ($identificador != '')){
	
		$conexion->ejecutarConsulta("begin;");
		
			$numero = pg_fetch_result($cpco->generarNumeroControlVectores($conexion, 'PCO-CV-'.$codigoParroquia), 0, 'num_solicitud');
			$tmp= explode("-", $numero);
			$incremento = end($tmp)+1;
			$numeroSolicitud = 'PCO-CV-'.$codigoParroquia.'-'.str_pad($incremento, 4, "0", STR_PAD_LEFT);			
	
			$idControlVectores = $cpco->nuevoControlVectores($conexion, $identificador, $numeroSolicitud, $fecha,  
															$duracion, $idFaseLunar, $faseLunar, $fechaDesde, $fechaHasta, 
															$nombrePredio, $nombrePropietario, $idProvincia, $provincia, 
															$idCanton, $canton, $idParroquia, $parroquia, $sitio, 
															$idSitioCaptura, $nombreSitioCaptura, 
															$coberturaCorral, $x, $y, $z, $altitud, $latitud, $longitud, $zona,
															$imagenMapa, $informe);

		$conexion->ejecutarConsulta("commit;");

		echo '<input type="hidden" id="' . pg_fetch_result($idControlVectores, 0, 'id_control_vectores') . '" data-rutaAplicacion="programasControlOficial" data-opcion="abrirControlVectores" data-destino="detalleItem"/>';

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