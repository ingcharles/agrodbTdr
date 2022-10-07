<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMovilizacionAnimal.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	set_time_limit(240);

	$min = htmlspecialchars ($_POST["minimo"],ENT_NOQUOTES,'UTF-8');
	$max = htmlspecialchars ($_POST["maximo"],ENT_NOQUOTES,'UTF-8');

	try {
		
		$conexion = new Conexion();
		$vdr = new ControladorMovilizacionAnimal();
		

		for ($i = $min; $i <=$max; $i++) {
			
			$serie= str_pad((int) $i,9,"0",STR_PAD_LEFT);
			$numero_documento= "No.". $serie;
			
			$vdr->guardarGeneradorCertificados($conexion, '6', 'Porcinos', 'movilizacion', 'No.', $serie,$numero_documento,'ingresado');
			
		}
		
			
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente.';
		
			
		$conexion->desconectar();
		echo json_encode($mensaje);
	} catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}

?>






