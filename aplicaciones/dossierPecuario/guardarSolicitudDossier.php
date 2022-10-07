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
	$dato['direccion_referencia'] = htmlspecialchars ($_POST['dirReferencia'],ENT_NOQUOTES,'UTF-8');
	$dato['ci_representante_legal'] = htmlspecialchars ($_POST['ciLegal'],ENT_NOQUOTES,'UTF-8');
	$dato['email_representante_legal'] = htmlspecialchars ($_POST['correoLegal'],ENT_NOQUOTES,'UTF-8');
	$dato['nombre'] = htmlspecialchars ($_POST['nombreProducto'],ENT_NOQUOTES,'UTF-8');


	$dato['id_subtipo_producto'] = htmlspecialchars ($_POST['id_subtipo_producto'],ENT_NOQUOTES,'UTF-8');
	$dato['tipo_solicitud'] = htmlspecialchars ($_POST['tipo_solicitud'],ENT_NOQUOTES,'UTF-8');
	$dato['id_sitio'] = htmlspecialchars ($_POST['id_sitio'],ENT_NOQUOTES,'UTF-8');
	$dato['id_area'] = htmlspecialchars ($_POST['id_area'],ENT_NOQUOTES,'UTF-8');
	$dato['ci_representante_tecnico'] = htmlspecialchars ($_POST['ci_representante_tecnico'],ENT_NOQUOTES,'UTF-8');
	$dato['registro_oficial'] = htmlspecialchars ($_POST['registro_oficial'],ENT_NOQUOTES,'UTF-8');

	$dato['tecnico_matricula'] = htmlspecialchars ($_POST['registroSenesyt'],ENT_NOQUOTES,'UTF-8');

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

