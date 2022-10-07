<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<?php
	$identificadorUsuarioRegistro = htmlspecialchars ($_POST['identificadorUsuarioRegistro'],ENT_NOQUOTES,'UTF-8');

	$datos = array('tipo_siniestro' => htmlspecialchars ($_POST['tipo_siniestro'],ENT_NOQUOTES,'UTF-8'),
				   'observacion_siniestro' => htmlspecialchars ($_POST['observaciones'],ENT_NOQUOTES,'UTF-8'),
				   'fecha_siniestro' => htmlspecialchars ($_POST['fecha_siniestro'],ENT_NOQUOTES,'UTF-8'),
				   'lugar_siniestro' => htmlspecialchars ($_POST['lugar_siniestro'],ENT_NOQUOTES,'UTF-8'),
				   'magnitud_danio_siniestro' => htmlspecialchars ($_POST['magnitud_siniestro'],ENT_NOQUOTES,'UTF-8'),
				   'vehiculo' => htmlspecialchars ($_POST['vehiculo'],ENT_NOQUOTES,'UTF-8'),
				   'conductor' =>  htmlspecialchars ($_POST['ocupante'],ENT_NOQUOTES,'UTF-8'),
				   'idVehiculo' =>  htmlspecialchars ($_POST['id_vehiculo'],ENT_NOQUOTES,'UTF-8'),
				   'kilometraje' =>  htmlspecialchars ($_POST['kilometraje'],ENT_NOQUOTES,'UTF-8')); 
	
	$conexion = new Conexion();
	$cv = new ControladorVehiculos();
	
	if ($identificadorUsuarioRegistro != ''){
		$res = $cv->generarNumeroSiniestro($conexion, '%'.$_SESSION ['codigoLocalizacion'].'%', "'".'SIN-'.$_SESSION['codigoLocalizacion'].'-'."'");
		$siniestro = pg_fetch_assoc($res);
		$incremento = $siniestro['numero'] + 1;
		$numero = 'SIN-'.$_SESSION['codigoLocalizacion'].'-'.str_pad($incremento, 8, "0", STR_PAD_LEFT);
				
		$cv -> guardarNuevoSiniestro($conexion,$numero, $datos['fecha_siniestro'], $datos['lugar_siniestro'], $datos['observacion_siniestro'], $datos['vehiculo'], $datos['conductor'], $_SESSION['nombreLocalizacion'], $datos['tipo_siniestro'], $datos['magnitud_danio_siniestro'], $datos['idVehiculo'], $datos['kilometraje'], $identificadorUsuarioRegistro);
		$cv ->actualizarEstadoVehiculo($conexion, $datos['vehiculo'], 'Siniestro');
		
		echo '<input type="hidden" id="'.$numero.'" data-rutaAplicacion="transportes" data-opcion="mostrarFotosVehiculoSiniestro" data-destino="detalleItem"/>';
	}else{
		echo '<script type="text/javascript">
		$("document").ready(function(){
		$(#estado).html("Su sesión expiró, por favor ingrese nuevamente al sistema");
	});
	</script>';
	}
	?>
</body>
	<script type="text/javascript">
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
		abrir($("input:hidden"),null,false);
	</script>
</html>