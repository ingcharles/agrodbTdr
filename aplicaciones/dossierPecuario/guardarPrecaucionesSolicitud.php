<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorDossierPecuario.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
try{
	$idUsuario= $_SESSION['usuario'];
	$id_solicitud = $_POST['id_solicitud'];


	$dato['id_solicitud']=$id_solicitud;
	$dato['residuos'] = trim(htmlspecialchars ($_POST['residuos'],ENT_NOQUOTES,'UTF-8'));
	$dato['precauciones'] = trim(htmlspecialchars ($_POST['precauciones'],ENT_NOQUOTES,'UTF-8'));
	$dato['precauciones_ref'] = htmlspecialchars ($_POST['precauciones_ref'],ENT_NOQUOTES,'UTF-8');
	$dato['calidad'] = trim(htmlspecialchars ($_POST['calidad'],ENT_NOQUOTES,'UTF-8'));
	$dato['validez'] = htmlspecialchars ($_POST['validez'],ENT_NOQUOTES,'UTF-8');
	$dato['validez_unidad'] = htmlspecialchars ($_POST['validez_unidad'],ENT_NOQUOTES,'UTF-8');
	$dato['linea_biologica'] = htmlspecialchars ($_POST['linea_biologica'],ENT_NOQUOTES,'UTF-8');
	$dato['humedad'] = htmlspecialchars ($_POST['humedad'],ENT_NOQUOTES,'UTF-8');
	$dato['inactivacion'] = trim(htmlspecialchars ($_POST['inactivacion'],ENT_NOQUOTES,'UTF-8'));
	$dato['estabilidad'] = htmlspecialchars ($_POST['estabilidad'],ENT_NOQUOTES,'UTF-8');
	$dato['estabilidad_unidad'] = htmlspecialchars ($_POST['estabilidad_unidad'],ENT_NOQUOTES,'UTF-8');
	$dato['inmunidad'] = htmlspecialchars ($_POST['inmunidad'],ENT_NOQUOTES,'UTF-8');
	$dato['inmunidad_unidad'] = htmlspecialchars ($_POST['inmunidad_unidad'],ENT_NOQUOTES,'UTF-8');
	$dato['inmunidad_min'] = htmlspecialchars ($_POST['inmunidad_min'],ENT_NOQUOTES,'UTF-8');
	$dato['inmunidad_min_unidad'] = htmlspecialchars ($_POST['inmunidad_min_unidad'],ENT_NOQUOTES,'UTF-8');
	$dato['inmunidad_ref'] = htmlspecialchars ($_POST['inmunidad_ref'],ENT_NOQUOTES,'UTF-8');

	$dato['nivel']=intval($_POST['nivel']);

	try {
		$conexion = new Conexion();
		$cp=new ControladorDossierPecuario();
		$res=$cp->guardarSolicitud($conexion,$dato);
		if($res['tipo']=="insert")
			$idProtocolo = $res['resultado'][0]['id_protocolo'];
		else
			$fila=$res['resultado'];

		$mensaje['id'] = $idProtocolo;
		$mensaje['dato'] = $res['resultado'];
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'La solicitud ha sido actualizada';

		$conexion->desconectar();

		echo json_encode($mensaje);

	}
	catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
}
catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}

?>

