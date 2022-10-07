<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorDossierFertilizante.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
try{
	$idUsuario= $_SESSION['usuario'];
	$id_solicitud = $_POST['id_solicitud'];

	
	$dato['tipo_producto'] = htmlspecialchars ($_POST['tipo_producto'],ENT_NOQUOTES,'UTF-8');        
	$dato['id_solicitud']=$id_solicitud;
	$dato['direccion_referencia'] = htmlspecialchars ($_POST['dirReferencia'],ENT_NOQUOTES,'UTF-8');
	$dato['ci_representante_legal'] = htmlspecialchars ($_POST['ciLegal'],ENT_NOQUOTES,'UTF-8');
	$dato['email_representante_legal'] = htmlspecialchars ($_POST['correoLegal'],ENT_NOQUOTES,'UTF-8');
	
   $dato['clon_registro_madre'] = htmlspecialchars ($_POST['clon_registro_madre'],ENT_NOQUOTES,'UTF-8');
	$dato['id_sitio'] = htmlspecialchars ($_POST['id_sitio'],ENT_NOQUOTES,'UTF-8');
	$dato['id_area'] = htmlspecialchars ($_POST['id_area'],ENT_NOQUOTES,'UTF-8');
	$dato['ci_representante_tecnico'] = htmlspecialchars ($_POST['ci_representante_tecnico'],ENT_NOQUOTES,'UTF-8');
	$dato['objetivo'] = htmlspecialchars ($_POST['objetivo'],ENT_NOQUOTES,'UTF-8');
	$dato['nivel']=intval($_POST['nivel']);

	try {
		$conexion = new Conexion();
		$cf=new ControladorDossierFertilizante();
		$res=$cf->guardarSolicitud($conexion,$dato);
		if($res['tipo']=="insert")
			$idSolicitud = $res['resultado'][0]['id_solicitud'];
		else
			$fila=$res['resultado'];

		$mensaje['id'] = $idSolicitud;
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