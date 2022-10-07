<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorDossierPecuario.php';
require_once '../../clases/ControladorEnsayoEficacia.php';

$cp=new ControladorDossierPecuario();
$ce = new ControladorEnsayoEficacia();

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
try{
	$identificador= $_SESSION['usuario'];
	$id_solicitud = $_POST['id_solicitud'];

	$dato['id_solicitud']=$id_solicitud;
	$dato['nivel']=intval($_POST['nivel']);
	$paso_solicitud=$_POST['paso_solicitud'];
	$esGuardar=true;
	try {
		$conexion = new Conexion();
		switch($paso_solicitud){
			case "P2":
				break;
			case "P3":
				break;
			case "P4":
				
				break;
			case "P11":
				break;
		}
		if($esGuardar){
			$res=$cp->guardarSolicitud($conexion,$dato);
			
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'La solicitud ha sido actualizada';
		}
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

