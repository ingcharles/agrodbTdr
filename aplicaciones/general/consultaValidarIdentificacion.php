<?php

session_start();

require_once '../../clases/ControladorValidarIdentificacion.php';

$mensaje = array();
$mensaje['estado'] = 'exito';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$identificadorConsulta = $_POST['numero'];
$tipoIdentificacion = $_POST['clasificacion'];

try {
	$cvi = new ControladorValidarIdentificacion();

	switch ($tipoIdentificacion)
	{
		case 'Cédula':
			$nombreIdentificacion = 'Cédula incorrecta: ';
			break;
		case 'Natural':
			$nombreIdentificacion = 'RUC - Persona natural incorrecto: ';
			break;
		case 'Juridica':
			$nombreIdentificacion = 'RUC - Persona jurídica incorrecto: ';
			break;
		case 'Publica':
			$nombreIdentificacion = 'RUC - Sociedad Pública incorrecta: ';
			break;
	}

	if ($cvi->validadorIdentificacion($identificadorConsulta, $tipoIdentificacion)) {
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje']="Cédula correcta";
	} else {
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = $nombreIdentificacion.$cvi->getMessage();
	}
	echo json_encode($mensaje);
}
catch (Exception $ex){
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error al ejecutar sentencia';
	echo json_encode($mensaje);
}

?>