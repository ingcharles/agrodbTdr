<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProgramasControlOficial.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
	
	$identificador = $_SESSION['usuario'];
	
	$idCatastroPredioEquidos = htmlspecialchars ($_POST['idCatastroPredioEquidos'],ENT_NOQUOTES,'UTF-8');
	
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
	$direccionPredio = htmlspecialchars ($_POST['direccionPredio'],ENT_NOQUOTES,'UTF-8');
	$x = htmlspecialchars ($_POST['x'],ENT_NOQUOTES,'UTF-8');
	$y = htmlspecialchars ($_POST['y'],ENT_NOQUOTES,'UTF-8');
	$z = htmlspecialchars ($_POST['z'],ENT_NOQUOTES,'UTF-8');
	$extension = (integer) $_POST['extension'];
	$altitud = null;    //param no solicitado en form
	$latitud = null;    //param no solicitado en form
        $longitud = null;   //param no solicitado en form
        $zona = null;       //param no solicitado en form
	
	
		$conexion = new Conexion();
		$cpco = new ControladorProgramasControlOficial();
		
		if(($identificador != null) || ($identificador != '')){
		
			$conexion->ejecutarConsulta("begin;");
		
			$cpco->modificarCatastroPredioEquidos($conexion, $idCatastroPredioEquidos, $identificador,
													$fecha, $nombrePredio, $nombrePropietario, $cedulaPropietario, 
													$telefonoPropietario, $correoElectronicoPropietario, 
													$nombreAdministrador, $cedulaAdministrador, 
													$telefonoAdministrador, $correoElectronicoAdministrador, 
													$direccionPredio, $x, $y, $z, $altitud, $latitud, $longitud, $zona,
													$extension);
			
			$conexion->ejecutarConsulta("commit;");
		
			echo '<input type="hidden" id="' . $idCatastroPredioEquidos . '" data-rutaAplicacion="programasControlOficial" data-opcion="abrirCatastroPredioEquidos" data-destino="detalleItem"/>';
			
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