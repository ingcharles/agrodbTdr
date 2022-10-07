<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorBrucelosisTuberculosis.php';

$conexion = new Conexion();
$cbt = new ControladorBrucelosisTuberculosis();

	$identificador = $_SESSION['usuario'];
	
	$idCertificacionBT = htmlspecialchars ($_POST['idCertificacionBT'],ENT_NOQUOTES,'UTF-8');
	$idInformacionPredio = htmlspecialchars ($_POST['idInformacionPredio'],ENT_NOQUOTES,'UTF-8');
	
	$superficiePredio = htmlspecialchars ($_POST['superficiePredio'],ENT_NOQUOTES,'UTF-8');
	$superficiePastos = htmlspecialchars ($_POST['superficiePastos'],ENT_NOQUOTES,'UTF-8');
	$cerramientoExterno = htmlspecialchars ($_POST['cerramientoExterno'],ENT_NOQUOTES,'UTF-8');
	$controlIngresoPersonas = htmlspecialchars ($_POST['controlIngresoPersonas'],ENT_NOQUOTES,'UTF-8');
	$controlIngresoAnimales = htmlspecialchars ($_POST['controlIngresoAnimales'],ENT_NOQUOTES,'UTF-8');
	$identificacionBovinos = htmlspecialchars ($_POST['identificacionBovinos'],ENT_NOQUOTES,'UTF-8');
	$mangaEmbudoBrete = htmlspecialchars ($_POST['mangaEmbudoBrete'],ENT_NOQUOTES,'UTF-8');
	
	if(($identificador != null) || ($identificador != '')){
		
		$informacionPredio = $cbt->abrirInformacionPredioCertificacionBT($conexion, $idCertificacionBT);
			
		if(pg_num_rows($informacionPredio) == 0){
	
			$conexion->ejecutarConsulta("begin;");
			
				$idCertificacionBT = $cbt->nuevaCertificacionBT($conexion, $identificador, $numeroSolicitud, $fecha, $nombreEncuestado, 
																$idPredio, $nombrePredio, $nombrePropietario, $cedulaPropietario, 
																$telefonoPropietario, $celularPropietario, 
																$correoElectronicoPropietario, $idProvincia, $provincia, 
																$idCanton, $canton, $idParroquia, $parroquia, 
																$numCertFiebreAftosa, $certificacion, 
																$x, $y, $z, $huso, $latitud, $longitud, $zona, $imagenMapa, $informe);
				
			$conexion->ejecutarConsulta("commit;");
				
		}else{
			
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