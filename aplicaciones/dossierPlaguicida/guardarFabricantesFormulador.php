<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorDossierPlaguicida.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
try{
	$idUsuario= $_SESSION['usuario'];
	$id_solicitud = $_POST['id_solicitud'];

	
	$dato['id_solicitud']=$id_solicitud;

	$dato['nivel']=intval($_POST['nivel']);

	try {
		$conexion = new Conexion();
		$cg=new ControladorDossierPlaguicida();
		$res=$cg->guardarSolicitud($conexion,$dato);
		if($res['tipo']=="insert")
			$idProtocolo = $res['resultado'][0]['id_solicitud'];
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

