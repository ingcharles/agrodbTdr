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
	$dato['conservacion'] = htmlspecialchars ($_POST['conservacion'],ENT_NOQUOTES,'UTF-8');
	$dato['almacenar_minimo'] = htmlspecialchars ($_POST['almacenar_minimo'],ENT_NOQUOTES,'UTF-8');
	$dato['almacenar_maximo'] = htmlspecialchars ($_POST['almacenar_maximo'],ENT_NOQUOTES,'UTF-8');
	$dato['humedad_minima'] = htmlspecialchars ($_POST['humedad_minima'],ENT_NOQUOTES,'UTF-8');
	$dato['humedad_maxima'] = htmlspecialchars ($_POST['humedad_maxima'],ENT_NOQUOTES,'UTF-8');

	$dato['control_producto'] = trim(htmlspecialchars ($_POST['control_producto'],ENT_NOQUOTES,'UTF-8'));
	$dato['deteccion_anticuerpos'] = trim(htmlspecialchars ($_POST['deteccion_anticuerpos'],ENT_NOQUOTES,'UTF-8'));
	$dato['linea_biologica'] = htmlspecialchars ($_POST['linea_biologica'],ENT_NOQUOTES,'UTF-8');
	$dato['tipos_anticuerpos'] = htmlspecialchars ($_POST['tipos_anticuerpos'],ENT_NOQUOTES,'UTF-8');
	$dato['interpretacion'] = trim(htmlspecialchars ($_POST['interpretacion'],ENT_NOQUOTES,'UTF-8'));
	$dato['eliminacion_envases'] = trim(htmlspecialchars ($_POST['eliminacion_envases'],ENT_NOQUOTES,'UTF-8'));
	$dato['riesgo'] = trim(htmlspecialchars ($_POST['riesgo'],ENT_NOQUOTES,'UTF-8'));
	$dato['mecanismo_accion'] = trim(htmlspecialchars ($_POST['mecanismo_accion'],ENT_NOQUOTES,'UTF-8'));
	$dato['mecanismo_accion_referencia'] = htmlspecialchars ($_POST['mecanismo_accion_referencia'],ENT_NOQUOTES,'UTF-8');

	$dato['microorganismos'] = trim(htmlspecialchars ($_POST['microorganismos'],ENT_NOQUOTES,'UTF-8'));
	$dato['modo_uso'] = trim(htmlspecialchars ($_POST['modo_uso'],ENT_NOQUOTES,'UTF-8'));

	$dato['observaciones'] = trim(htmlspecialchars ($_POST['observaciones'],ENT_NOQUOTES,'UTF-8'));

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

