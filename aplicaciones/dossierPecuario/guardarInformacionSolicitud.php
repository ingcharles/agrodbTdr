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
	$dato['ph'] = htmlspecialchars ($_POST['ph'],ENT_NOQUOTES,'UTF-8');
	$dato['viscosidad'] = htmlspecialchars ($_POST['viscosidad'],ENT_NOQUOTES,'UTF-8');
	$dato['densidad'] = htmlspecialchars ($_POST['densidad'],ENT_NOQUOTES,'UTF-8');

	$dato['modo_fabricacion'] = trim(htmlspecialchars ($_POST['modo_fabricacion'],ENT_NOQUOTES,'UTF-8'));
	$dato['especificacion'] = trim(htmlspecialchars ($_POST['especificacion'],ENT_NOQUOTES,'UTF-8'));
	$dato['prueba_biologica'] = trim(htmlspecialchars ($_POST['prueba_biologica'],ENT_NOQUOTES,'UTF-8'));
	$dato['prueba_biologica_ref'] = htmlspecialchars ($_POST['prueba_biologica_ref'],ENT_NOQUOTES,'UTF-8');

	$dato['identidad'] = trim(htmlspecialchars ($_POST['identidad'],ENT_NOQUOTES,'UTF-8'));
	$dato['identidad_referencia'] = htmlspecialchars ($_POST['identidad_referencia'],ENT_NOQUOTES,'UTF-8');

	$dato['esterilidad'] = trim(htmlspecialchars ($_POST['esterilidad'],ENT_NOQUOTES,'UTF-8'));
	$dato['esterilidad_referencia'] = htmlspecialchars ($_POST['esterilidad_referencia'],ENT_NOQUOTES,'UTF-8');

	$dato['agentes_extra'] =trim( htmlspecialchars ($_POST['agentes_extra'],ENT_NOQUOTES,'UTF-8'));
	$dato['inocuidad'] = trim(htmlspecialchars ($_POST['inocuidad'],ENT_NOQUOTES,'UTF-8'));
	$dato['inocuidad_referencia'] = htmlspecialchars ($_POST['inocuidad_referencia'],ENT_NOQUOTES,'UTF-8');

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

